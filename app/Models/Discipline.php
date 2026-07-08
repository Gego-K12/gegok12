<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Discipline
 *
 * Model for managing student discipline and incident records.
 *
 * @property int $id
 * @property int $school_id
 * @property int $academic_year_id
 * @property int $user_id
 * @property \DateTime $incident_date
 * @property int $reported_by
 * @property string $incident_detail
 * @property string $response
 * @property string $action_taken
 * @property string $attachments
 * @property int $notify_parents
 * @property int $is_seen
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property string $attachment_path
 * @property-read School $school
 * @property-read AcademicYear $academicYear
 * @property-read StandardLink $standardLink
 * @property-read User $user
 * @property-read User $teacher
 *
 * @mixin \Eloquent
 */
class Discipline extends Model
{
    use Common;
    use SoftDeletes;

    protected $table = 'disciplines';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'academic_year_id', 'user_id', 'incident_date', 'reported_by', 'incident_detail', 'response', 'action_taken', 'attachments', 'notify_parents', 'is_seen',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    // protected $dates = ['deleted_at'];

    protected $casts = [
        'incident_date' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Get the school for this discipline record.
     *
     * @return BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id');
    }

    /**
     * Get the academic year for this discipline record.
     *
     * @return BelongsTo
     */
    public function academicYear()
    {
        return $this->belongsTo('App\Models\AcademicYear', 'academic_year_id');
    }

    /**
     * Get the standard link for this discipline record.
     *
     * @return BelongsTo
     */
    public function standardLink()
    {
        return $this->belongsTo('\App\Models\StandardLink', 'standardLink_id');
    }

    /**
     * Get the student for this discipline record.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * Get the teacher who reported this incident.
     *
     * @return BelongsTo
     */
    public function teacher()
    {
        return $this->belongsTo('App\Models\User', 'reported_by');
    }

    /**
     * Get the full file path for the attachments.
     *
     * @return string
     */
    public function getAttachmentPathAttribute()
    {
        return $this->getFilePath($this->attachments);
    }
}
