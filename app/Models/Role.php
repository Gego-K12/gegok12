<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laratrust\Models\Role as LaratrustRole;

/**
 * Class Role
 *
 * Model for managing application roles.
 *
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read Collection|RoleUser[] $roleUser
 *
 * @mixin \Eloquent
 */
class Role extends LaratrustRole
{
    protected $table = 'roles';

    protected $fillable = [
        'name', 'display_name', 'description',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Get role users associated with this role.
     *
     * @return HasMany
     */
    public function roleUser()
    {
        return $this->hasMany('App\Models\RoleUser', 'role_id');
    }
}
