<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

/**
 * Class AbsentReason
 *
 * Model for absence reasons.
 *
 * @property int $id
 * @property string $title
 * @property int $status
 * @property \Carbon\Carbon|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|AbsentReason newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AbsentReason newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AbsentReason query()
 * @method static \Illuminate\Database\Eloquent\Builder|AbsentReason whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AbsentReason whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AbsentReason whereDeletedAt($value)
 *
 * @mixin \Eloquent
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AbsentReason extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'absent_reasons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'status',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the attendance records for this reason.
     *
     * @return BelongsTo
     */
    public function attendance()
    {
        return $this->belongsTo('App\Models\Attendance', 'reason_id', 'id');
    }

    /**
     * Get the teacher leave applications for this reason.
     *
     * @return HasMany
     */
    public function teacherLeaveApplication()
    {
        return $this->hasMany('\App\Models\TeacherLeaveApplication', 'reason_id', 'id');
    }
}
