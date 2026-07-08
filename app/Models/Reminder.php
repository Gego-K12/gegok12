<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Reminder
 *
 * Model for managing reminders sent to users via email or SMS.
 *
 * @property int $id
 * @property int $school_id
 * @property string $from
 * @property string $to
 * @property string $subject
 * @property string $message
 * @property int $entity_id
 * @property string $entity_name
 * @property string $via
 * @property int $queue_status
 * @property array $sms_response
 * @property \DateTime $executed_at
 * @property int $template_id
 * @property array $data
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read Events $events
 * @property-read School $school
 * @property-read User $user
 * @property-read User $userSms
 *
 * @mixin \Eloquent
 */
class Reminder extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reminders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'from', 'to', 'subject', 'message', 'entity_id', 'entity_name', 'via', 'queue_status', 'sms_response', 'executed_at', 'template_id', 'data',
    ];

    protected $casts = ['data' => 'array', 'sms_response' => 'array'];

    /**
     * Get the event for this reminder.
     *
     * @return BelongsTo
     */
    public function events()
    {
        return $this->belongsTo('App\Models\Events', 'entity_id');
    }

    /**
     * Get the school for this reminder.
     *
     * @return BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id');
    }

    /**
     * Get the user for email delivery.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'to', 'email');
    }

    /**
     * Get the user for SMS delivery.
     *
     * @return BelongsTo
     */
    public function userSms()
    {
        return $this->belongsTo('App\Models\User', 'to', 'mobile_no');
    }
}
