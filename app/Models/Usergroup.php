<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Usergroup
 *
 * Model for managing user groups/roles.
 *
 * @property int $id
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property-read Collection|User[] $user
 * @property-read Collection|Userprofile[] $userprofile
 *
 * @mixin \Eloquent
 */
class Usergroup extends Model
{
    protected $table = 'usergroups';

    /**
     * Get users in this user group.
     *
     * @return HasMany
     */
    public function user()
    {
        return $this->hasMany('App\Models\User', 'usergroup_id', 'id');
    }

    /**
     * Get user profiles in this user group.
     *
     * @return HasMany
     */
    public function userprofile()
    {
        return $this->hasMany('App\Models\Userprofile', 'usergroup_id', 'id');
    }
}
