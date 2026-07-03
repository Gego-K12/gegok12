<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;

/**
 * Class FeedbackMessage
 *
 * Model for managing individual messages within feedback conversations.
 *
 * @property int $id
 * @property int $school_id
 * @property int $user_id
 * @property int $feedback_id
 * @property string $message
 * @property string $file
 * @property string $category
 * @property int $is_seen
 * @property int $deleted_from_sender
 * @property int $deleted_from_receiver
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read School $school
 * @property-read User $user
 * @property-read Feedback $feedback
 *
 * @mixin \Eloquent
 */
class FeedbackMessage extends Model
{
    use PresentableTrait;
    use SoftDeletes;

    protected $presenter = "App\Presenters\UserPresenter";

    protected $table = 'feedback_messages';

    protected $appends = ['message'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'user_id', 'feedback_id', 'message', 'file', 'category', 'is_seen', 'deleted_from_sender', 'deleted_from_receiver',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the school for this feedback message.
     *
     * @return BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id');
    }

    /**
     * Get the user who sent this message.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * Get the feedback conversation this message belongs to.
     *
     * @return BelongsTo
     */
    public function feedback()
    {
        return $this->belongsTo('App\Models\Feedback', 'feedback_id');
    }

    /**
     * Get and sanitize the message content.
     *
     * @param  string  $message
     * @return string
     */
    public function getMessageAttribute($message)
    {
        return \Purify::clean($message);
    }
}
