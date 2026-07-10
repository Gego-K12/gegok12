<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents a group within the platform, often tied to a standard or
 * class section and composed of multiple members.
 */
class Group extends Model
{
    protected $table = 'groups';

    protected $fillable = [
        'standardLink_id', 'group_name', 'type', 'status',
    ];

    /**
     * Get the members that belong to this group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members()
    {
        return $this->hasMany(GroupMember::class, 'id', 'group_id');
    }

    /**
     * Return the linked standard metadata for the group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function standardlink()
    {
        return $this->belongsTo(StandardLink::class, 'standardLink_id');
    }
}
