<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

/** Raw, tenant-scoped subqueries avoid recursive Eloquent eager-loading/scopes. */
class AcademyAssignments
{
    public function classIds(object $actor)
    {
        $period = app(AcademyContext::class)->period((int) $actor->school_id);
        return DB::table('standards_link as assigned')->select('assigned.id')
            ->where('assigned.school_id', $actor->school_id)->where('assigned.academic_year_id', $period->id)
            ->where('assigned.status', 1)->whereNull('assigned.deleted_at')
            ->where(function ($q) use ($actor, $period) {
                $q->where('assigned.class_teacher_id', $actor->id)->orWhereIn('assigned.id',
                    DB::table('class_teacher_links')->select('standardLink_id')->where('school_id', $actor->school_id)
                        ->where('academic_year_id', $period->id)->where('teacher_id', $actor->id)
                        ->where('status', 1)->whereNull('deleted_at'));
            });
    }

    public function allows(object $actor, int $classId): bool
    {
        return $this->classIds($actor)->where('assigned.id', $classId)->exists();
    }
}
