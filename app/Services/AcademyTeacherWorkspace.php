<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AcademyTeacherWorkspace
{
    public function leaves(object $actor)
    {
        abort_unless(in_array((int) $actor->usergroup_id, [3, 5], true), 403);
        $q = DB::table('teacher_leave_applications as l')->join('users as teacher', 'teacher.id', '=', 'l.user_id')
            ->leftJoin('userprofiles as p', function ($j) { $j->on('p.user_id', '=', 'teacher.id')->whereNull('p.deleted_at'); })
            ->where('l.school_id', $actor->school_id)->where('teacher.school_id', $actor->school_id)->where('teacher.usergroup_id', 5)->whereNull('l.deleted_at');
        if (config('academy_backend.test_only')) { $q->where('teacher.email', config('academy_backend.demo_emails.5')); }
        if ((int) $actor->usergroup_id === 5) { $q->where('l.user_id', $actor->id); }
        return $q;
    }

    public function requestLeave(object $actor, array $input): int
    {
        abort_unless((int) $actor->usergroup_id === 5, 403);
        $data = Validator::make($input, ['from_date' => 'required|date_format:Y-m-d', 'to_date' => 'required|date_format:Y-m-d|after_or_equal:from_date', 'remarks' => 'required|string|min:3|max:2000'])->validate();
        return DB::transaction(function () use ($actor, $data) {
            DB::table('users')->where('id', $actor->id)->lockForUpdate()->first();
            if (DB::table('teacher_leave_applications')->where('school_id', $actor->school_id)->where('user_id', $actor->id)
                ->whereIn('status', ['pending', 'approved'])->whereNull('deleted_at')->whereDate('from_date', '<=', $data['to_date'])->whereDate('to_date', '>=', $data['from_date'])->exists()) {
                throw ValidationException::withMessages(['from_date' => 'An existing pending or approved request overlaps these dates.']);
            }
            $id = DB::table('teacher_leave_applications')->insertGetId($data + ['school_id' => $actor->school_id,
                'academic_year_id' => app(AcademyContext::class)->period((int) $actor->school_id)->id, 'user_id' => $actor->id,
                'session' => 'day', 'status' => 'pending', 'created_at' => now(), 'updated_at' => now()]);
            app(AcademyStudents::class)->log($actor, null, 'teacher_leave_requested', ['leave_id' => $id]); return $id;
        });
    }

    public function review(object $actor, int $leave, array $input): void
    {
        app(AcademyStudents::class)->admin($actor);
        $data = Validator::make($input, ['decision' => ['required', Rule::in(['approved', 'rejected'])], 'comments' => 'nullable|required_if:decision,rejected|string|max:2000'])->validate();
        DB::transaction(function () use ($actor, $leave, $data) {
            $row = $this->leaves($actor)->where('l.id', $leave)->select('l.*')->lockForUpdate()->first();
            abort_unless($row, 404);
            if ($row->status !== 'pending') { throw ValidationException::withMessages(['decision' => 'This request has already been reviewed or withdrawn.']); }
            DB::table('teacher_leave_applications')->where('id', $leave)->update(['status' => $data['decision'] === 'approved' ? 'approved' : 'cancelled',
                'academy_decision' => $data['decision'], 'approved_by' => $actor->id, 'approved_on' => now('Africa/Cairo')->toDateString(), 'comments' => $data['comments'] ?? null, 'updated_at' => now()]);
            app(AcademyStudents::class)->log($actor, null, 'teacher_leave_'.$data['decision'], ['leave_id' => $leave, 'teacher_id' => $row->user_id]);
        });
    }

    public function withdraw(object $actor, int $leave): void
    {
        abort_unless((int) $actor->usergroup_id === 5, 403);
        DB::transaction(function () use ($actor, $leave) {
            $row = $this->leaves($actor)->where('l.id', $leave)->select('l.*')->lockForUpdate()->first(); abort_unless($row, 404);
            if ($row->status !== 'pending') { throw ValidationException::withMessages(['leave' => 'Only pending requests can be withdrawn.']); }
            DB::table('teacher_leave_applications')->where('id', $leave)->update(['status' => 'cancelled', 'updated_at' => now()]);
            app(AcademyStudents::class)->log($actor, null, 'teacher_leave_withdrawn', ['leave_id' => $leave]);
        });
    }

    public function attendance(object $actor, ?int $teacher = null)
    {
        abort_unless(in_array((int) $actor->usergroup_id, [3, 5], true), 403);
        $q = DB::table('attendances as a')->join('users as u', 'u.id', '=', 'a.user_id')
            ->leftJoin('userprofiles as p', function ($j) { $j->on('p.user_id', '=', 'u.id')->whereNull('p.deleted_at'); })
            ->where('a.school_id', $actor->school_id)->where('u.school_id', $actor->school_id)->where('u.usergroup_id', 5)->whereNull('a.deleted_at')->whereNull('a.standardLink_id');
        if (config('academy_backend.test_only')) { $q->where('u.email', config('academy_backend.demo_emails.5')); }
        if ((int) $actor->usergroup_id === 5) { $q->where('a.user_id', $actor->id); }
        elseif ($teacher) { $q->where('a.user_id', $teacher); }
        return $q;
    }

    public function markAttendance(object $actor, array $input): void
    {
        app(AcademyStudents::class)->admin($actor);
        $data = Validator::make($input, ['teacher_id' => ['required', 'integer', Rule::exists('users', 'id')->where('school_id', $actor->school_id)->where('usergroup_id', 5)->whereNull('deleted_at')],
            'date' => ['required', 'date_format:Y-m-d', 'before_or_equal:'.now('Africa/Cairo')->toDateString()], 'session' => ['required', Rule::in(['forenoon', 'afternoon', 'day'])], 'status' => 'required|boolean', 'remarks' => 'nullable|string|max:2000'])->validate();
        DB::transaction(function () use ($actor, $data) {
            DB::table('users')->where('id', $data['teacher_id'])->lockForUpdate()->first();
            foreach ($data['session'] === 'day' ? ['forenoon', 'afternoon'] : [$data['session']] as $session) {
                $query = DB::table('attendances')->where('school_id', $actor->school_id)->where('user_id', $data['teacher_id'])->whereDate('date', $data['date'])->where('session', $session)->whereNull('standardLink_id')->whereNull('deleted_at');
                $values = ['status' => $data['status'], 'remarks' => $data['remarks'] ?? null, 'recorded_by' => $actor->id, 'updated_at' => now()];
                if ($query->exists()) { $query->update($values); }
                else { DB::table('attendances')->insert($values + ['school_id' => $actor->school_id, 'academic_year_id' => app(AcademyContext::class)->period((int) $actor->school_id)->id,
                    'user_id' => $data['teacher_id'], 'date' => $data['date'], 'session' => $session, 'created_at' => now()]); }
            }
            app(AcademyStudents::class)->log($actor, null, 'teacher_attendance_recorded', ['teacher_id' => $data['teacher_id'], 'date' => $data['date'], 'session' => $data['session'], 'present' => (bool) $data['status']]);
        });
    }
}
