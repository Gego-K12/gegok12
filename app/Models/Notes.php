<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Notes
 *
 * Model for managing notes and comments on entities.
 *
 * @property int $id
 * @property int $user_id
 * @property int $entity_id
 * @property string $entity_name
 * @property string $notes
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property-read User $user
 * @property-read Events $event
 *
 * @mixin \Eloquent
 */
class Notes extends Model
{
    protected $table = 'notes';

    /**
     * Get the user who created this note.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\\Models\\User', 'user_id');
    }

    /**
     * Get the event this note is attached to.
     *
     * @return BelongsTo
     */
    public function event()
    {
        return $this->belongsTo('App\\Models\\Events', 'entity_id');
    }
}
