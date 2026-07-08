<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class VisitorLog
 *
 * Model for managing visitor logs for school.
 *
 * @property int $id
 * @property int $school_id
 * @property int $academic_year_id
 * @property string $email
 * @property string $name
 * @property string $relation
 * @property string $company_name
 * @property string $contact_number
 * @property string $address
 * @property int $student_id
 * @property string $relation_with_student
 * @property string $relation_name
 * @property int $number_of_visitors
 * @property string $visiting_purpose
 * @property int $employee_id
 * @property \DateTime $date_of_visit
 * @property string $entry_time
 * @property string $exit_time
 * @property string $remark
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read School $school
 * @property-read AcademicYear $academicYear
 * @property-read User $student
 * @property-read User $employee
 *
 * @mixin \Eloquent
 */
class VisitorLog extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'visitor_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'academic_year_id', 'email', 'name', 'relation', 'company_name', 'contact_number', 'address', 'student_id', 'relation_with_student', 'relation_name', 'number_of_visitors', 'visiting_purpose', 'employee_id', 'date_of_visit', 'entry_time', 'exit_time', 'remark',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'date_of_visit'];

    /**
     * Get the school for this visitor log.
     *
     * @return BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id');
    }

    /**
     * Get the academic year for this visitor log.
     *
     * @return BelongsTo
     */
    public function academicYear()
    {
        return $this->belongsTo('\App\Models\AcademicYear', 'academic_year_id');
    }

    /**
     * Get the student being visited.
     *
     * @return BelongsTo
     */
    public function student()
    {
        return $this->belongsTo('\App\Models\User', 'student_id');
    }

    /**
     * Get the employee recording this visitor log.
     *
     * @return BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo('\App\Models\User', 'employee_id');
    }

    /**
     * Get the standard/grade for the student being visited.
     *
     * @return int|null
     */
    public function getstandard($student_id)
    {
        return $this->student->studentAcademicLatest->standardLink_id;
    }
}
