<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class LearningAccess
{
    /** One academy boundary and one role policy for learning reads. */
    public function students(object $actor)
    {
        abort_unless($actor->school_id && in_array((int) $actor->usergroup_id, [3, 5, 6, 7], true), 403);
        $query = DB::table('users')->where('users.school_id', $actor->school_id)->where('users.usergroup_id', 6)->whereNull('users.deleted_at');
        if (config('academy_backend.test_only')) { $query->where('users.email', config('academy_backend.demo_emails.6')); }
        return match ((int) $actor->usergroup_id) {
            3 => $query,
            6 => $query->where('users.id', $actor->id),
            7 => $query->whereIn('users.id', DB::table('student_parent_links')->select('student_id')
                ->where('school_id', $actor->school_id)->where('parent_id', $actor->id)->whereNull('deleted_at')),
            5 => $query->whereIn('users.id', DB::table('student_academics as a')->select('a.user_id')
                ->where('a.school_id', $actor->school_id)->whereNull('a.deleted_at')
                ->where('a.academic_year_id', app(AcademyContext::class)->period((int) $actor->school_id)->id)
                ->whereIn('a.standardLink_id', app(AcademyAssignments::class)->classIds($actor))),
        };
    }

    public function student(object $actor, int $id): object
    {
        $student = $this->students($actor)->where('id', $id)->first(['id', 'name']);
        abort_unless($student, 404);
        return $student;
    }
}
