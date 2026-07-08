<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class SendMail
 *
 * Model for managing outbound email messages.
 *
 * @property int $id
 * @property int $school_id
 * @property int $academic_year_id
 * @property int $user_id
 * @property string $from_address
 * @property string $from
 * @property string $to
 * @property string $subject
 * @property string $message
 * @property string|null $attachments
 * @property int $status
 * @property string $type
 * @property string|null $message_id
 * @property \DateTime|null $executed_at
 * @property int $is_executed
 * @property \DateTime|null $fired_at
 * @property \DateTime|null $read_at
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read School $school
 * @property-read AcademicYear $academicYear
 * @property-read User $user
 * @property-read User|null $student
 *
 * @mixin \Eloquent
 */
class SendMail extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'send_mail';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'academic_year_id', 'user_id', 'from_address', 'from', 'to', 'subject', 'message', 'attachments', 'status', 'type', 'message_id', 'executed_at', 'is_executed', 'fired_at', 'read_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['executed_at', 'fired_at', 'read_at', 'deleted_at'];

    /**
     * Get the school for this mail.
     *
     * @return BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id');
    }

    /**
     * Get the academic year for this mail.
     *
     * @return BelongsTo
     */
    public function academicYear()
    {
        return $this->belongsTo('App\Models\AcademicYear', 'academic_year_id');
    }

    /**
     * Get the user who initiated this mail.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * Get the student recipient for this mail.
     *
     * @return BelongsTo
     */
    public function student()
    {
        return $this->belongsTo('App\Models\User', 'student_id');
    }
}
