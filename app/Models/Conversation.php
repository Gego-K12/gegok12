<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Conversation
 *
 * Model for managing conversations between users.
 *
 * @property int $id
 * @property string $uuid
 * @property \DateTime $last_message_at
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property-read Collection|User[] $users
 * @property-read Collection|User[] $others
 * @property-read Collection|Message[] $messages
 *
 * @mixin \Eloquent
 */
class Conversation extends Model
{
    protected $fillable = [
        'last_message_at',
        'uuid',

    ];

    protected $dates = [
        'last_message_at',

    ];

    /**
     * Get the route key name for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Get users participating in this conversation.
     *
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User')->withPivot('read_at')
            ->withTimestamps()
            ->oldest();

    }

    /**
     * Get other users in this conversation (excluding authenticated user).
     *
     * @return Builder
     */
    public function others()
    {
        return $this->users()->where('user_id', '!=', auth()->id());
    }

    /**
     * Get messages in this conversation.
     *
     * @return HasMany
     */
    public function messages()
    {
        return $this->hasMany('App\Models\Message')->latest();
    }
}
