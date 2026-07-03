<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TeacherLeaveApplication
 *
 * Model for managing teacher leave applications and approvals.
 *
 * @property int $id
 * @property int $school_id
 * @property int $academic_year_id
 * @property int $user_id
 * @property \DateTime $from_date
 * @property \DateTime $to_date
 * @property int $reason_id
 * @property string $remarks
 * @property int $leave_type_id
 * @property int $approved_by
 * @property \DateTime $approved_on
 * @property string $comments
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read School $school
 * @property-read AcademicYear $academicYear
 * @property-read User $teacher
 * @property-read StandardLink $standardLink
 * @property-read AbsentReason $absentReason
 * @property-read LeaveType $leaveType
 * @property-read User $approvedUser
 *
 * @mixin \Eloquent
 */
class TeacherLeaveApplication extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'teacher_leave_applications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'academic_year_id', 'user_id', 'from_date', 'to_date', 'reason_id', 'remarks', 'leave_type_id', 'approved_by', 'approved_on', 'comments', 'status',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    // protected $dates = ['from_date' ,'to_date', 'deleted_at' , 'approved_on'];

    protected $casts = [
        'from_date' => 'datetime',
        'to_date' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the school for this application.
     *
     * @return BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('\App\Models\School', 'school_id');
    }

    /**
     * Get the academic year for this application.
     *
     * @return BelongsTo
     */
    public function academicYear()
    {
        return $this->belongsTo('\App\Models\AcademicYear', 'academic_year_id');
    }

    /**
     * Get the teacher who applied for leave.
     *
     * @return BelongsTo
     */
    public function teacher()
    {
        return $this->belongsTo('\App\Models\User', 'user_id');
    }

    /**
     * Get the standard link for this application.
     *
     * @return BelongsTo
     */
    public function standardLink()
    {
        return $this->belongsTo('\App\Models\StandardLink', 'standardLink_id');
    }

    /**
     * Get the reason for absence if specified.
     *
     * @return BelongsTo
     */
    public function absentReason()
    {
        return $this->belongsTo('App\Models\AbsentReason', 'reason_id');
    }

    /**
     * Get the leave type for this application.
     *
     * @return BelongsTo
     */
    public function leaveType()
    {
        return $this->belongsTo('\App\Models\LeaveType', 'leave_type_id');
    }

    /**
     * Get the user who approved this application.
     *
     * @return BelongsTo
     */
    public function approvedUser()
    {
        return $this->belongsTo('\App\Models\User', 'approved_by');
    }

    /**
     * Scope to filter applications by date range (overlapping dates).
     *
     * @param  Builder  $query
     * @param  \DateTime  $from_date
     * @param  \DateTime  $to_date
     * @return Builder
     */
    public function scopeByDate($query, $from_date, $to_date)
    {
        $query->where(function ($q) use ($from_date, $to_date) {
            $q->where('from_date', '>=', $from_date)
                ->where('from_date', '<', $to_date);

        })->orWhere(function ($q) use ($from_date, $to_date) {
            $q->where('from_date', '<=', $from_date)
                ->where('to_date', '>', $to_date);

        })->orWhere(function ($q) use ($from_date, $to_date) {
            $q->where('to_date', '>', $from_date)
                ->where('to_date', '<=', $to_date);

        })->orWhere(function ($q) use ($from_date, $to_date) {
            $q->where('from_date', '>=', $from_date)
                ->where('to_date', '<=', $to_date);

        });

        return $query;
    }
}
