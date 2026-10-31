<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AcademyStudents
{
    public function admin(object $actor): void
    {
        abort_unless((int) $actor->usergroup_id === 3 && $actor->school_id, 403);
    }

    public function log(object $actor, ?int $student, string $event, array $details): void
    {
        DB::table('academy_learning_events')->insert([
            'school_id' => $actor->school_id, 'student_id' => $student, 'actor_id' => $actor->id,
            'event' => $event, 'details' => json_encode($details, JSON_THROW_ON_ERROR), 'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    public function register(object $actor, array $input, int $role = 6): int
    {
        $this->admin($actor);
        abort_unless(in_array($role, [5, 6, 7], true), 422);
        if (isset($input['email'])) { $input['email'] = mb_strtolower(trim($input['email'])); }
        $data = Validator::make($input, [
            'firstname' => 'required|string|max:100', 'lastname' => 'nullable|string|max:100',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => 'required|string|min:12|max:255', 'mobile_no' => 'nullable|string|max:30',
            'date_of_birth' => $role === 6 ? ['nullable', 'date', 'after:'.now()->subYears(16)->toDateString(), 'before_or_equal:'.now()->subYears(6)->toDateString()] : ['nullable', 'date', 'before:today'],
        ] + ($role === 6 ? $this->linkRules() : []))->validate();
        return DB::transaction(function () use ($actor, $data, $role) {
            $id = DB::table('users')->insertGetId([
                'school_id' => $actor->school_id, 'usergroup_id' => $role, 'name' => 'academy-'.Str::uuid(),
                'email' => mb_strtolower($data['email']), 'password' => Hash::make($data['password']),
                'mobile_no' => $data['mobile_no'] ?? null, 'status' => 'active', 'created_at' => now(), 'updated_at' => now(),
            ]);
            DB::table('userprofiles')->insert([
                'school_id' => $actor->school_id, 'user_id' => $id, 'usergroup_id' => $role,
                'firstname' => $data['firstname'], 'lastname' => $data['lastname'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null, 'status' => 'active', 'created_at' => now(), 'updated_at' => now(),
            ]);
            if ($role === 6) { $this->journey($actor, $id); $this->saveLinks($actor, $id, $data); }
            $this->log($actor, $role === 6 ? $id : null, 'account_registered', ['account_id' => $id, 'role' => $role]);
            return $id;
        });
    }

    private function linkRules(): array
    {
        return array_fill_keys(['homework_drive_url', 'recordings_url', 'report_card_url'], ['nullable', 'url:https', 'max:2048']);
    }

    private function saveLinks(object $actor, int $student, array $data): void
    {
        $links = array_intersect_key($data, $this->linkRules());
        if ($links) {
            $this->journey($actor, $student);
            DB::table('academy_student_journeys')->where('school_id', $actor->school_id)->where('student_id', $student)->update($links + ['updated_at' => now()]);
        }
    }

    public function updateProfile(object $actor, int $student, array $input): void
    {
        $this->admin($actor);
        app(LearningAccess::class)->student($actor, $student);
        if (isset($input['email'])) { $input['email'] = mb_strtolower(trim($input['email'])); }
        $data = Validator::make($input, [
            'firstname' => 'required|string|max:100', 'lastname' => 'nullable|string|max:100',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($student)],
            'mobile_no' => 'nullable|string|max:30', 'date_of_birth' => 'nullable|date|before:today',
        ] + $this->linkRules())->validate();
        DB::transaction(function () use ($actor, $student, $data) {
            DB::table('users')->where('id', $student)->where('school_id', $actor->school_id)->update([
                'email' => $data['email'], 'mobile_no' => $data['mobile_no'] ?? null, 'updated_at' => now(),
            ]);
            DB::table('userprofiles')->where('user_id', $student)->where('school_id', $actor->school_id)->whereNull('deleted_at')->update([
                'firstname' => $data['firstname'], 'lastname' => $data['lastname'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null, 'updated_at' => now(),
            ]);
            $this->saveLinks($actor, $student, $data);
            $this->log($actor, $student, 'student_details_updated', ['fields' => array_keys($data)]);
        });
    }

    public function unlinkParent(object $actor, int $student, int $parent): void
    {
        $this->admin($actor);
        app(LearningAccess::class)->student($actor, $student);
        DB::transaction(function () use ($actor, $student, $parent) {
            DB::table('student_parent_links')->where('school_id', $actor->school_id)->where('student_id', $student)
                ->where('parent_id', $parent)->whereNull('deleted_at')->update(['deleted_at' => now(), 'updated_at' => now()]);
            $this->log($actor, $student, 'parent_unlinked', ['parent_id' => $parent]);
        });
    }

    /** Lazily initialize existing students, without pretending they took a placement test. */
    public function journey(object $actor, int $student): object
    {
        app(LearningAccess::class)->student($actor, $student);
        DB::table('academy_student_journeys')->insertOrIgnore([
            'school_id' => $actor->school_id, 'student_id' => $student, 'placement_status' => 'pending',
            'unit' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);
        return DB::table('academy_student_journeys')->where('school_id', $actor->school_id)->where('student_id', $student)->first();
    }

    public function placement(object $actor, int $student, array $input): void
    {
        $this->admin($actor);
        $data = Validator::make($input, [
            'placement_status' => ['required', Rule::in(['pending', 'scheduled', 'completed', 'waived'])],
            'placement_date' => 'nullable|required_if:placement_status,scheduled,completed|date',
            'level' => 'nullable|required_if:placement_status,completed,waived|integer|between:1,6',
            'unit' => 'required|integer|between:1,2', 'placement_notes' => 'nullable|string|max:4000',
        ])->validate();
        DB::transaction(function () use ($actor, $student, $data) {
            $this->journey($actor, $student);
            $before = DB::table('academy_student_journeys')->where('student_id', $student)->lockForUpdate()->first();
            $enrolled = DB::table('student_academics')->where('school_id', $actor->school_id)->where('user_id', $student)->whereNull('deleted_at')->exists();
            if ($enrolled && ($before->level !== null) && ((int) ($data['level'] ?? 0) !== (int) $before->level || (int) $data['unit'] !== (int) $before->unit || !in_array($data['placement_status'], ['completed', 'waived'], true))) {
                throw ValidationException::withMessages(['level' => 'Use teacher feedback to advance an enrolled learner.']);
            }
            if (!in_array($data['placement_status'], ['completed', 'waived'], true)) { $data['level'] = null; }
            DB::table('academy_student_journeys')->where('student_id', $student)->update($data + ['updated_at' => now()]);
            $this->log($actor, $student, 'placement_updated', ['from' => $before->placement_status, 'to' => $data['placement_status'], 'level' => $data['level'] ?? null, 'unit' => $data['unit']]);
        });
    }

    public function linkParent(object $actor, int $student, int $parent): void
    {
        $this->admin($actor);
        app(LearningAccess::class)->student($actor, $student);
        abort_unless(DB::table('users')->where('id', $parent)->where('school_id', $actor->school_id)->where('usergroup_id', 7)->where('status', 'active')->whereNull('deleted_at')->exists(), 422);
        DB::transaction(function () use ($actor, $student, $parent) {
            DB::table('users')->where('id', $student)->lockForUpdate()->first();
            DB::table('student_parent_links')->updateOrInsert(['school_id' => $actor->school_id, 'student_id' => $student, 'parent_id' => $parent],
                ['status' => 1, 'deleted_at' => null, 'created_at' => now(), 'updated_at' => now()]);
            $this->log($actor, $student, 'parent_linked', ['parent_id' => $parent]);
        });
    }

    public function assign(object $actor, int $student, int $group): void
    {
        $this->admin($actor);
        DB::transaction(function () use ($actor, $student, $group) {
            $this->journey($actor, $student);
            $j = DB::table('academy_student_journeys')->where('student_id', $student)->lockForUpdate()->first();
            $g = DB::table('groups as g')->join('standards_link as c', 'c.id', '=', 'g.standardLink_id')
                ->join('academy_group_settings as s', 's.group_id', '=', 'g.id')->where('c.school_id', $actor->school_id)
                ->where('s.school_id', $actor->school_id)->where('g.id', $group)->where('c.status', 1)
                ->where('c.academic_year_id', app(AcademyContext::class)->period((int) $actor->school_id)->id)
                ->whereNull('g.deleted_at')->whereNull('c.deleted_at')->first(['g.id', 's.level', 'c.class_teacher_id']);
            if (!$g || !in_array($j->placement_status, ['completed', 'waived'], true) || !$j->level || (int) $g->level !== (int) $j->level || $j->completed_at) {
                throw ValidationException::withMessages(['group_id' => 'Complete placement and choose an active group at the learner’s current level.']);
            }
            abort_unless(DB::table('users')->where('id', $g->class_teacher_id)->where('school_id', $actor->school_id)->where('usergroup_id', 5)->where('status', 'active')->whereNull('deleted_at')->exists(), 422, 'Assign an active teacher first.');
            app(AcademyGroups::class)->enroll((int) $actor->school_id, $group, [$student]);
            $this->log($actor, $student, 'group_assigned', ['group_id' => $group, 'teacher_id' => $g->class_teacher_id]);
        });
    }

    public function feedback(object $actor, int $student, array $input): void
    {
        abort_unless((int) $actor->usergroup_id === 5, 403);
        app(LearningAccess::class)->student($actor, $student);
        $data = Validator::make($input, ['feedback' => 'required|string|min:3|max:5000', 'advance' => 'required|boolean', 'expected_level' => 'required|integer|between:1,6', 'expected_unit' => 'required|integer|between:1,2'])->validate();
        DB::transaction(function () use ($actor, $student, $data) {
            $j = $this->journey($actor, $student);
            $j = DB::table('academy_student_journeys')->where('student_id', $student)->lockForUpdate()->first();
            if (!$j->level || $j->completed_at || (int) $j->level !== (int) $data['expected_level'] || (int) $j->unit !== (int) $data['expected_unit']) {
                throw ValidationException::withMessages(['feedback' => 'Progress changed. Refresh the student record before sending feedback.']);
            }
            $enrollment = DB::table('student_academics')->where('school_id', $actor->school_id)->where('user_id', $student)->whereNull('deleted_at')->orderByDesc('id')->first();
            abort_unless($enrollment && app(AcademyAssignments::class)->allows($actor, (int) $enrollment->standardLink_id), 403);
            $groupLevel = DB::table('academy_group_settings as s')->join('groups as g', 'g.id', '=', 's.group_id')
                ->where('s.school_id', $actor->school_id)->where('g.standardLink_id', $enrollment->standardLink_id)->whereNull('g.deleted_at')->value('s.level');
            if ((int) $groupLevel !== (int) $j->level) {
                throw ValidationException::withMessages(['feedback' => 'Assign the student to a group at their current level first.']);
            }
            DB::table('academy_teacher_feedback')->insert([
                'school_id' => $actor->school_id, 'student_id' => $student, 'teacher_id' => $actor->id,
                'standardLink_id' => $enrollment->standardLink_id, 'level' => $j->level, 'unit' => $j->unit,
                'feedback' => $data['feedback'], 'advances' => (bool) $data['advance'], 'created_at' => now(), 'updated_at' => now(),
            ]);
            if ($data['advance']) {
                $next = (int) $j->unit === 1 ? ['unit' => 2] : ((int) $j->level < 6 ? ['level' => $j->level + 1, 'unit' => 1] : ['completed_at' => now()]);
                DB::table('academy_student_journeys')->where('student_id', $student)->update($next + ['updated_at' => now()]);
            }
            $this->log($actor, $student, $data['advance'] ? 'unit_completed' : 'feedback_sent', ['level' => $j->level, 'unit' => $j->unit]);
        });
    }
}
