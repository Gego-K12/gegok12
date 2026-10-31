<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AcademyLessons
{
    public function group(object $actor, int $group): object
    {
        abort_unless(in_array((int) $actor->usergroup_id, [3, 5], true), 403);
        $query = DB::table('groups as g')->join('standards_link as c', 'c.id', '=', 'g.standardLink_id')
            ->leftJoin('academy_group_settings as s', 's.group_id', '=', 'g.id')
            ->where('c.school_id', $actor->school_id)->where('c.status', 1)->whereNull('g.deleted_at')->whereNull('c.deleted_at')
            ->where('c.academic_year_id', app(AcademyContext::class)->period((int) $actor->school_id)->id)->where('g.id', $group);
        if ((int) $actor->usergroup_id === 5) { $query->whereIn('c.id', app(AcademyAssignments::class)->classIds($actor)); }
        $record = $query->first(['g.id', 'g.group_name', 'g.standardLink_id', 'c.class_teacher_id', 's.level', 's.schedule', 's.meeting_days', 's.start_time', 's.end_time', 's.timezone']);
        abort_unless($record, 404);
        return $record;
    }

    public function roster(object $actor, int $group)
    {
        $record = $this->group($actor, $group);
        return DB::table('group_members as m')->join('users as u', 'u.id', '=', 'm.member_id')
            ->leftJoin('userprofiles as p', function ($j) { $j->on('p.user_id', '=', 'u.id')->whereNull('p.deleted_at'); })
            ->where('m.group_id', $group)->where('m.member_type', 'student')->whereNull('m.deleted_at')
            ->where('u.school_id', $actor->school_id)->where('u.usergroup_id', 6)->where('u.status', 'active')->whereNull('u.deleted_at')
            ->whereExists(fn ($q) => $q->selectRaw('1')->from('student_academics as a')->whereColumn('a.user_id', 'u.id')
                ->where('a.school_id', $actor->school_id)->where('a.standardLink_id', $record->standardLink_id)->whereNull('a.deleted_at'))
            ->select('u.id', 'u.name', 'p.firstname', 'p.lastname')->distinct();
    }

    public function rosterPreview(object $actor, array $groups)
    {
        abort_unless(in_array((int) $actor->usergroup_id, [3, 5], true), 403);
        $query = DB::table('group_members as m')->join('groups as g', 'g.id', '=', 'm.group_id')
            ->join('standards_link as c', 'c.id', '=', 'g.standardLink_id')->join('users as u', 'u.id', '=', 'm.member_id')
            ->leftJoin('userprofiles as p', function ($j) { $j->on('p.user_id', '=', 'u.id')->whereNull('p.deleted_at'); })
            ->whereIn('g.id', $groups)->where('c.school_id', $actor->school_id)->where('u.school_id', $actor->school_id)
            ->whereNull('g.deleted_at')->whereNull('c.deleted_at')->whereNull('m.deleted_at')->where('m.member_type', 'student')
            ->where('u.usergroup_id', 6)->where('u.status', 'active')->whereNull('u.deleted_at')
            ->whereExists(fn ($q) => $q->selectRaw('1')->from('student_academics as a')->whereColumn('a.user_id', 'u.id')
                ->whereColumn('a.standardLink_id', 'c.id')->whereNull('a.deleted_at'));
        if ((int) $actor->usergroup_id === 5) { $query->whereIn('c.id', app(AcademyAssignments::class)->classIds($actor)); }
        return $query->orderBy('p.firstname')->get(['g.id as group_id', 'u.id', 'u.name', 'p.firstname', 'p.lastname'])->groupBy('group_id');
    }

    public function addStudents(object $actor, int $group, array $input): void
    {
        app(AcademyStudents::class)->admin($actor);
        $data = Validator::make($input, ['students' => 'required|array|min:1|max:100', 'students.*' => 'required|integer|distinct', 'agree_starting_level' => 'nullable|boolean'])->validate();
        DB::transaction(function () use ($actor, $group, $data) {
            $g = $this->group($actor, $group);
            if (!$g->level) { throw ValidationException::withMessages(['group' => 'Set this group’s course level under Groups & teachers first.']); }
            foreach ($data['students'] as $student) {
                $service = app(AcademyStudents::class);
                $journey = $service->journey($actor, (int) $student);
                if (!in_array($journey->placement_status, ['completed', 'waived'], true) && !empty($data['agree_starting_level'])) {
                    $service->placement($actor, (int) $student, ['placement_status' => 'waived', 'level' => $g->level, 'unit' => 1,
                        'placement_notes' => 'Starting level agreed by administrator while adding to group. No placement test claimed.']);
                }
                $service->assign($actor, (int) $student, $group);
            }
        });
    }

    /** Snapshot the roster once; later transfers must not rewrite historical lessons. */
    public function start(object $actor, int $group, array $input): int
    {
        $g = $this->group($actor, $group);
        abort_unless((int) $actor->usergroup_id === 5, 403);
        $data = Validator::make($input, ['lesson_date' => ['required', 'date_format:Y-m-d', 'before_or_equal:'.now('Africa/Cairo')->toDateString()],
            'start_time' => 'required|date_format:H:i', 'topic' => 'nullable|string|max:255', 'homework_assigned' => 'required|boolean'])->validate();
        return DB::transaction(function () use ($actor, $g, $data) {
            DB::table('groups')->where('id', $g->id)->lockForUpdate()->first();
            $slot = ['group_id' => $g->id, 'lesson_date' => $data['lesson_date'], 'start_time' => $data['start_time'].':00'];
            $existing = DB::table('academy_group_lessons')->where($slot)->first();
            if ($existing) { return (int) $existing->id; }
            $students = $this->roster($actor, $g->id)->get();
            if ($students->isEmpty()) { throw ValidationException::withMessages(['lesson_date' => 'Add students to this group before recording a lesson.']); }
            $id = DB::table('academy_group_lessons')->insertGetId($slot + ['school_id' => $actor->school_id, 'standardLink_id' => $g->standardLink_id,
                'created_by' => $actor->id, 'timezone' => 'Africa/Cairo', 'topic' => $data['topic'] ?? null,
                'homework_assigned' => $data['homework_assigned'], 'created_at' => now(), 'updated_at' => now()]);
            foreach ($students as $student) {
                DB::table('academy_lesson_students')->insert(['school_id' => $actor->school_id, 'lesson_id' => $id, 'student_id' => $student->id,
                    'attendance' => 'pending', 'homework' => $data['homework_assigned'] ? 'pending' : 'not_assigned', 'created_at' => now(), 'updated_at' => now()]);
            }
            app(AcademyStudents::class)->log($actor, null, 'lesson_opened', ['group_id' => $g->id, 'lesson_id' => $id, 'date' => $data['lesson_date']]);
            return $id;
        });
    }

    public function lesson(object $actor, int $group, int $lesson): object
    {
        $this->group($actor, $group);
        $record = DB::table('academy_group_lessons')->where('school_id', $actor->school_id)->where('group_id', $group)->where('id', $lesson)->first();
        abort_unless($record, 404); return $record;
    }

    public function save(object $actor, int $group, int $lesson, array $input): void
    {
        abort_unless((int) $actor->usergroup_id === 5, 403);
        $record = $this->lesson($actor, $group, $lesson);
        $data = Validator::make($input, ['students' => 'required|array|min:1|max:500', 'students.*.student_id' => 'required|integer|distinct',
            'students.*.attendance' => ['required', Rule::in(['pending', 'present', 'absent', 'excused'])],
            'students.*.homework' => ['required', Rule::in(['pending', 'done', 'missing', 'not_assigned'])]])->validate();
        DB::transaction(function () use ($actor, $record, $data) {
            DB::table('academy_group_lessons')->where('id', $record->id)->lockForUpdate()->first();
            foreach ($data['students'] as $row) {
                $existing = DB::table('academy_lesson_students')->where('school_id', $actor->school_id)->where('lesson_id', $record->id)->where('student_id', $row['student_id'])->first();
                if (!$existing) { throw ValidationException::withMessages(['students' => 'Only students in this lesson’s saved roster can be marked.']); }
                if ($record->homework_assigned && $row['homework'] === 'not_assigned') {
                    throw ValidationException::withMessages(['students' => 'This lesson has homework. Choose pending, done or missing.']);
                }
                $changes = ['attendance' => $row['attendance'], 'homework' => $record->homework_assigned ? $row['homework'] : 'not_assigned', 'recorded_by' => $actor->id, 'updated_at' => now()];
                DB::table('academy_lesson_students')->where('id', $existing->id)->update($changes);
                if ($existing->attendance !== $changes['attendance'] || $existing->homework !== $changes['homework']) {
                    app(AcademyStudents::class)->log($actor, (int) $row['student_id'], 'lesson_record_updated', ['lesson_id' => $record->id, 'group_id' => $record->group_id,
                        'attendance' => $changes['attendance'], 'homework' => $changes['homework']]);
                }
            }
        });
    }

    public function message(object $actor, int $student, array $input): void
    {
        abort_unless((int) $actor->usergroup_id === 5, 403);
        app(LearningAccess::class)->student($actor, $student);
        $data = Validator::make($input, ['message' => 'required|string|min:3|max:5000'])->validate();
        DB::transaction(function () use ($actor, $student, $data) {
            $id = DB::table('academy_student_messages')->insertGetId(['school_id' => $actor->school_id, 'student_id' => $student,
                'teacher_id' => $actor->id, 'message' => $data['message'], 'created_at' => now(), 'updated_at' => now()]);
            app(AcademyStudents::class)->log($actor, $student, 'teacher_message_shared', ['message_id' => $id]);
        });
    }

    public function summary(int $academy, array $students)
    {
        return DB::table('academy_lesson_students')->where('school_id', $academy)->whereIn('student_id', $students)->select('student_id')
            ->selectRaw("SUM(CASE WHEN attendance = 'present' THEN 1 ELSE 0 END) as present_count, SUM(CASE WHEN attendance = 'absent' THEN 1 ELSE 0 END) as absent_count, SUM(CASE WHEN attendance = 'excused' THEN 1 ELSE 0 END) as excused_count, SUM(CASE WHEN homework = 'done' THEN 1 ELSE 0 END) as homework_done, SUM(CASE WHEN homework = 'missing' THEN 1 ELSE 0 END) as homework_missing, SUM(CASE WHEN homework = 'pending' THEN 1 ELSE 0 END) as homework_pending")
            ->groupBy('student_id')->get()->keyBy('student_id');
    }
}
