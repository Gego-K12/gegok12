<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Represents a member assigned to a group.
 *
 * Each record links a member (user, staff, etc.) to a group and tracks
 * the relationship specifics such as member type and soft delete state.
 */
class GroupMember extends Model
{
    use SoftDeletes;

    protected $table = 'group_members';

    protected $fillable = [
        'group_id', 'member_id', 'member_type',
    ];

    /**
     * Get the user that corresponds to this group member.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    /**
     * Retrieve the user profile associated with the member record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userprofile()
    {
        return $this->hasOne(Userprofile::class, 'user_id', 'member_id');
    }

    /**
     * Get the group that the member belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
}
