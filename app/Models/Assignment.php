<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors
/**
 * Class Assignment
 *
 * Model for assignments.
 *
 * @property int $id
 * @property int $school_id
 * @property int $academic_year_id
 * @property int $standardLink_id
 * @property int $subject_id
 * @property int $teacher_id
 * @property string $title
 * @property string $description
 * @property string $attachment
 * @property int $marks
 * @property \Carbon\Carbon $assigned_date
 * @property \Carbon\Carbon $submission_date
 * @property int $status
 * @property-read \App\Models\School $school
 * @property-read \App\Models\AcademicYear $academicYear
 * @property-read \App\Models\StandardLink $standardLink
 * @property-read \App\Models\Subject $subject
 * @property-read \App\Models\User $teacher
 * @property-read \App\Models\StudentAssignment $studentAssignment
 * @property-read \App\Models\AssignmentApproval $assignmentApproval
 *
 * @mixin \Eloquent
 */

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    //
    use Common;
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'assignments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'academic_year_id', 'standardLink_id', 'subject_id', 'teacher_id', 'title', 'description', 'attachment', 'marks', 'assigned_date', 'submission_date', 'status',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    // protected $dates = ['assigned_date' , 'submission_date'];

    protected $casts = [
        'assigned_date' => 'datetime',
        'submission_date' => 'datetime',
    ];

    /**
     * Get the school that owns the assignment.
     *
     * @return BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id');
    }

    /**
     * Get the academic year for the assignment.
     *
     * @return BelongsTo
     */
    public function academicYear()
    {
        return $this->belongsTo('\App\Models\AcademicYear', 'academic_year_id');
    }

    /**
     * Get the standard link for the assignment.
     *
     * @return BelongsTo
     */
    public function standardLink()
    {
        return $this->belongsTo('\App\Models\StandardLink', 'standardLink_id');
    }

    /**
     * Get the subject for the assignment.
     *
     * @return BelongsTo
     */
    public function subject()
    {
        return $this->belongsTo('\App\Models\Subject', 'subject_id');
    }

    /**
     * Get the teacher who assigned this assignment.
     *
     * @return BelongsTo
     */
    public function teacher()
    {
        return $this->belongsTo('\App\Models\User', 'teacher_id');
    }

    /**
     * Get the student assignment for this assignment.
     *
     * @return HasOne
     */
    public function studentAssignment()
    {
        return $this->hasOne('\App\Models\StudentAssignment', 'assignment_id', 'id');
    }

    /**
     * Get the assignment approval for this assignment.
     *
     * @return HasOne
     */
    public function assignmentApproval()
    {
        return $this->hasOne('\App\Models\AssignmentApproval', 'assignment_id', 'id');
    }

    /**
     * Get the attachment file path for this assignment.
     *
     * @return string
     */
    public function getAttachmentPathAttribute()
    {
        return $this->getFilePath($this->attachment);
    }

    /**
     * Get the student history records for this assignment.
     *
     * @return MorphMany
     */
    public function viewers()
    {
        return $this->morphMany(StudentHistory::class, 'entity');
    }
}
