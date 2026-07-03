<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class LessonPlanApproval
 *
 * Model for managing lesson plan approvals.
 *
 * @property int $id
 * @property int $lesson_plan_id
 * @property string|null $comments
 * @property int $approved_by
 * @property \DateTime $approved_at
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read LessonPlan $lessonPlan
 * @property-read User $user
 *
 * @mixin \Eloquent
 */
class LessonPlanApproval extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lesson_plan_approvals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lesson_plan_id', 'comments', 'approved_by', 'approved_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['approved_at', 'deleted_at'];

    /**
     * Get the lesson plan being approved.
     *
     * @return BelongsTo
     */
    public function lessonPlan()
    {
        return $this->belongsTo('\App\Models\LessonPlan', 'lesson_plan_id');
    }

    /**
     * Get the user who approved this lesson plan.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('\App\Models\User', 'approved_by');
    }
}
