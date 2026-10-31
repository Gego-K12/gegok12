<?php

namespace App\Models\Scopes;

use App\Services\AcademyAssignments;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/** Apply on each query, never capture a request/user in a persistent worker. */
class AcademyTeacherScope implements Scope
{
    public function __construct(private string $column = 'standardLink_id') {}

    public function apply(Builder $builder, Model $model): void
    {
        $user = auth()->user();
        if (!config('academy_backend.enabled') || !$user || (int) $user->usergroup_id !== 5) {
            return;
        }
        $builder->where($model->qualifyColumn('school_id'), $user->school_id)
            ->whereIn($model->qualifyColumn($this->column), app(AcademyAssignments::class)->classIds($user));
    }
}
