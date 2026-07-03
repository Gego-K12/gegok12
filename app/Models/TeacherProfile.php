<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TeacherProfile
 *
 * Model for managing teacher profile information.
 *
 * @property int $id
 * @property int $school_id
 * @property int $academic_year_id
 * @property int $user_id
 * @property int $qualification_id
 * @property int $ug_degree
 * @property int $pg_degree
 * @property string $sub_qualification
 * @property string $specialization
 * @property string $designation
 * @property string $sub_designation
 * @property string $employee_id
 * @property int $reporting_to
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property string $avatarPath
 * @property-read School $school
 * @property-read Userprofile $userprofile
 * @property-read AcademicYear $academicYear
 * @property-read User $user
 * @property-read User $reportingTo
 * @property-read Qualification $qualification
 * @property-read Qualification $ugDegree
 * @property-read Qualification $pgDegree
 *
 * @mixin \Eloquent
 */
class TeacherProfile extends Model
{
    use Common;
    use HasFactory;
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'teacherprofile';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'academic_year_id', 'user_id', 'qualification_id', 'ug_degree', 'pg_degree', 'sub_qualification', 'specialization', 'designation', 'sub_designation', 'employee_id', 'reporting_to', 'status',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the school for this teacher.
     *
     * @return BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id');
    }

    /**
     * Get the user profile for this teacher.
     *
     * @return BelongsTo
     */
    public function userprofile()
    {
        return $this->belongsTo('App\Models\Userprofile', 'user_id', 'user_id');
    }

    /**
     * Get the academic year for this profile.
     *
     * @return BelongsTo
     */
    public function academicYear()
    {
        return $this->belongsTo('\App\Models\AcademicYear', 'academic_year_id');
    }

    /**
     * Get the user for this profile.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('\App\Models\User', 'user_id');
    }

    /**
     * Get the user this teacher reports to.
     *
     * @return BelongsTo
     */
    public function reportingTo()
    {
        return $this->belongsTo('\App\Models\User', 'reporting_to');
    }

    /**
     * Get the qualification for this teacher.
     *
     * @return BelongsTo
     */
    public function qualification()
    {
        return $this->belongsTo('\App\Models\Qualification', 'qualification_id');
    }

    /**
     * Get the undergraduate degree for this teacher.
     *
     * @return BelongsTo
     */
    public function ugDegree()
    {
        return $this->belongsTo('\App\Models\Qualification', 'ug_degree');
    }

    /**
     * Get the postgraduate degree for this teacher.
     *
     * @return BelongsTo
     */
    public function pgDegree()
    {
        return $this->belongsTo('\App\Models\Qualification', 'pg_degree');
    }

    /**
     * Get the avatar file path for this profile.
     *
     * @return string
     */
    public function getAvatarPathAttribute()
    {
        return $this->getFilePath($this->avatar);
    }
}
