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
 * Class Promotion
 *
 * Model for managing student promotions between standards.
 *
 * @property int $id
 * @property int $school_id
 * @property int $user_id
 * @property int $current_academic_year_id
 * @property int $current_standard_id
 * @property int $current_section_id
 * @property int|null $exam_id
 * @property int $next_academic_year_id
 * @property int $next_standard_id
 * @property int $next_section_id
 * @property string|null $comments
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read School $school
 * @property-read User $user
 * @property-read AcademicYear $currentAcademicYear
 * @property-read AcademicYear $nextAcademicYear
 * @property-read Standard $currentStandard
 * @property-read Standard $nextStandard
 * @property-read Section $currentSection
 * @property-read Section $nextSection
 *
 * @mixin \Eloquent
 */
class Promotion extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promotions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'user_id', 'current_academic_year_id', 'current_standard_id', 'current_section_id', 'exam_id', 'next_academic_year_id', 'next_standard_id', 'next_section_id', 'comments', 'status',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    /**
     * Get the school for this promotion.
     *
     * @return BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id');
    }

    /**
     * Get the student being promoted.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * Get the current academic year.
     *
     * @return BelongsTo
     */
    public function currentAcademicYear()
    {
        return $this->belongsTo('App\Models\AcademicYear', 'current_academic_year_id');
    }

    /**
     * Get the next academic year.
     *
     * @return BelongsTo
     */
    public function nextAcademicYear()
    {
        return $this->belongsTo('App\Models\AcademicYear', 'next_academic_year_id');
    }

    /**
     * Get the current standard (grade).
     *
     * @return BelongsTo
     */
    public function currentStandard()
    {
        return $this->belongsTo('App\Models\Standard', 'current_standard_id');
    }

    /**
     * Get the next standard (grade).
     *
     * @return BelongsTo
     */
    public function nextStandard()
    {
        return $this->belongsTo('App\Models\Standard', 'next_standard_id');
    }

    /**
     * Get the current section.
     *
     * @return BelongsTo
     */
    public function currentSection()
    {
        return $this->belongsTo('App\Models\Section', 'current_section_id');
    }

    /**
     * Get the next section.
     *
     * @return BelongsTo
     */
    public function nextSection()
    {
        return $this->belongsTo('App\Models\Section', 'next_section_id');
    }
}
