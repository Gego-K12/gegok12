<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Subject
 *
 * Model for managing school subjects.
 *
 * @property int $id
 * @property int $school_id
 * @property int $academic_year_id
 * @property int $standard_id
 * @property int $section_id
 * @property string $name
 * @property string $code
 * @property string $type
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read School $school
 * @property-read AcademicYear $academicYear
 * @property-read Standard $standard
 * @property-read Section $section
 * @property-read Collection|Teacherlink[] $teacherlink
 * @property-read Collection|Mark[] $mark
 * @property-read Collection|ExamSchedule[] $schedule
 *
 * @mixin \Eloquent
 */
class Subject extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subjects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'school_id', 'academic_year_id', 'standard_id', 'section_id', 'name', 'code', 'type',
    ];

    /**
     * Get the uppercase name for this subject.
     *
     * @return string
     */
    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }

    /**
     * Get the school for this subject.
     *
     * @return BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('\App\Models\School', 'school_id');
    }

    /**
     * Get the academic year for this subject.
     *
     * @return BelongsTo
     */
    public function academicYear()
    {
        return $this->belongsTo('\App\Models\AcademicYear', 'academic_year_id');
    }

    /**
     * Get the standard for this subject.
     *
     * @return BelongsTo
     */
    public function standard()
    {
        return $this->belongsTo('\App\Models\Standard', 'standard_id');
    }

    /**
     * Get the section for this subject.
     *
     * @return BelongsTo
     */
    public function section()
    {
        return $this->belongsTo('\App\Models\Section', 'section_id');
    }

    /**
     * Get teacher links for this subject.
     *
     * @return HasMany
     */
    public function teacherlink()
    {
        return $this->hasMany('\App\Models\Teacherlink', 'subject_id', 'id');
    }

    /**
     * Get marks for this subject.
     *
     * @return HasMany
     */
    public function mark()
    {
        return $this->hasMany('App\Models\Mark', 'subject_id', 'id');
    }

    /**
     * Get exam schedules for this subject.
     *
     * @return HasMany
     */
    public function schedule()
    {
        return $this->hasMany('App\Models\ExamSchedule', 'subject_id', 'id');
    }
}
