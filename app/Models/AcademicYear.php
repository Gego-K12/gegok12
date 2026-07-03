<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors
/**
 * Class AcademicYear
 *
 * Model for academic years.
 *
 * @property int $id
 * @property int $school_id
 * @property string $name
 * @property string|null $description
 * @property \Carbon\Carbon $start_date
 * @property \Carbon\Carbon $end_date
 * @property int $status
 * @property-read \App\Models\School $school
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Standard[] $standard
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StudentAcademic[] $studentAcademic
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Mark[] $marks
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TeacherProfile[] $teacherprofile
 * @property-read \App\Models\Promotion $currentPromotion
 * @property-read \App\Models\Promotion $nextPromotion
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Discipline[] $discipline
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bulletin[] $bulletin
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attendance[] $attendance
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Assignment[] $assignment
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Subject[] $subject
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LeaveType[] $leaveType
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TeacherLeaveApplication[] $teacherLeaveApplication
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Fee[] $fee
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SendMail[] $sendMail
 *
 * @mixin \Eloquent
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicYear extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'academic_years';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'name', 'description', 'start_date', 'end_date', 'status',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    // protected $dates = ['start_date','end_date'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get the status display text based on the status value.
     *
     * @return string
     */
    public function getStatusDisplayAttribute()
    {
        if ($this->status == 1) {
            $status = 'Current Academic Year';
        } elseif ($this->status == 2) {
            $status = 'New Academic Year';
        } elseif ($this->status == 0) {
            $status = 'Old Academic Year';
        }

        return $status;
    }

    /**
     * Get the school that owns the academic year.
     *
     * @return BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id');
    }

    /**
     * Get the standards for this academic year.
     *
     * @return HasMany
     */
    public function standard()
    {
        return $this->hasMany('App\Models\Standard', 'academic_year_id', 'id');
    }

    /**
     * Get the student academics for this academic year.
     *
     * @return HasMany
     */
    public function studentAcademic()
    {
        return $this->hasMany('App\Models\StudentAcademic', 'academic_year_id', 'id');
    }

    /**
     * Get the marks for this academic year.
     *
     * @return HasMany
     */
    public function marks()
    {
        return $this->hasMany('App\Models\Mark', 'academic_year_id', 'id');
    }

    /**
     * Get the teacher profiles for this academic year.
     *
     * @return HasMany
     */
    public function teacherprofile()
    {
        return $this->hasMany('App\Models\TeacherProfile', 'academic_year_id', 'id');
    }

    /**
     * Get the current promotion for this academic year.
     *
     * @return BelongsTo
     */
    public function currentPromotion()
    {
        return $this->belongsTo('App\Models\Promotion', 'current_academic_year_id', 'id');
    }

    /**
     * Get the next promotion for this academic year.
     *
     * @return BelongsTo
     */
    public function nextPromotion()
    {
        return $this->belongsTo('App\Models\Promotion', 'next_academic_year_id', 'id');
    }

    /**
     * Get the disciplines for this academic year.
     *
     * @return HasMany
     */
    public function discipline()
    {
        return $this->hasMany('App\Models\Discipline', 'academic_year_id', 'id');
    }

    /**
     * Get the bulletins for this academic year.
     *
     * @return HasMany
     */
    public function bulletin()
    {
        return $this->hasMany('App\Models\Bulletin', 'academic_year_id', 'id');
    }

    /**
     * Get the attendance records for this academic year.
     *
     * @return HasMany
     */
    public function attendance()
    {
        return $this->hasMany('\App\Models\Attendance', 'academic_year_id', 'id');
    }

    /**
     * Get the assignments for this academic year.
     *
     * @return HasMany
     */
    public function assignment()
    {
        return $this->hasMany('\App\Models\Assignment', 'academic_year_id', 'id');
    }

    /**
     * Get the subjects for this academic year.
     *
     * @return HasMany
     */
    public function subject()
    {
        return $this->hasMany('\App\Models\Subject', 'academic_year_id', 'id');
    }

    /**
     * Get the leave types for this academic year.
     *
     * @return HasMany
     */
    public function leaveType()
    {
        return $this->hasMany('\App\Models\LeaveType', 'academic_year_id', 'id');
    }

    /**
     * Get the teacher leave applications for this academic year.
     *
     * @return HasMany
     */
    public function teacherLeaveApplication()
    {
        return $this->hasMany('\App\Models\TeacherLeaveApplication', 'academic_year_id', 'id');
    }

    /**
     * Get the fees for this academic year.
     *
     * @return HasMany
     */
    public function fee()
    {
        return $this->hasMany('\App\Models\Fee', 'academic_year_id', 'id');
    }

    /**
     * Get the sent mails for this academic year.
     *
     * @return HasMany
     */
    public function sendMail()
    {
        return $this->hasMany('App\Models\SendMail','academic_year_id','id');
    }
}
