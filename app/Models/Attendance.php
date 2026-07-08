<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

/**
 * Class Attendance
 *
 * Model for attendance records.
 *
 * @property int $id
 * @property int $school_id
 * @property int $academic_year_id
 * @property int $standardLink_id
 * @property int $user_id
 * @property \Carbon\Carbon $date
 * @property string $session
 * @property int $status
 * @property int $reason_id
 * @property string $remarks
 * @property int $recorded_by
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \App\Models\School $school
 * @property-read \App\Models\AcademicYear $academicYear
 * @property-read \App\Models\StandardLink $standardLink
 * @property-read \App\Models\User $user
 * @property-read \App\Models\AbsentReason $absentReason
 * @property-read \App\Models\User $admin
 * @property-read \App\Models\User $recordedby
 *
 * @mixin \Eloquent
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attendances';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'academic_year_id', 'standardLink_id', 'user_id', 'date', 'session', 'status', 'reason_id', 'remarks', 'recorded_by',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    // protected $dates = [ 'date' , 'deleted_at'];

    protected $casts = [
        'date' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the school for this attendance record.
     *
     * @return BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id');
    }

    /**
     * Get the academic year for this attendance record.
     *
     * @return BelongsTo
     */
    public function academicYear()
    {
        return $this->belongsTo('App\Models\AcademicYear', 'academic_year_id');
    }

    /**
     * Get the standard link for this attendance record.
     *
     * @return BelongsTo
     */
    public function standardLink()
    {
        return $this->belongsTo('App\Models\StandardLink', 'standardLink_id');
    }

    /**
     * Get the user for this attendance record.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * Get the absence reason for this attendance record.
     *
     * @return BelongsTo
     */
    public function absentReason()
    {
        return $this->belongsTo('App\Models\AbsentReason', 'reason_id');
    }

    /**
     * Get the admin who recorded this attendance.
     *
     * @return BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo('App\Models\User', 'recorded_by')->where('usergroup_id', 3);
    }

    /**
     * Get the user who recorded this attendance.
     *
     * @return BelongsTo
     */
    public function recordedby()
    {
        return $this->belongsTo('App\Models\User', 'recorded_by');
    }

    /**
     * Scope to filter attendance by user role/usergroup.
     *
     * @param  Builder  $query
     * @param  int  $usergroup_id
     * @return Builder
     */
    public function scopeByRole($query, $usergroup_id)
    {
        $query->wherehas('user', function ($query) use ($usergroup_id) {
            $query->where('usergroup_id', $usergroup_id);
        });

        return $query;
    }
}
