<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laratrust\Models\Permission as LaratrustPermission;

/**
 * Class Permission
 *
 * Model for managing application permissions.
 *
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property-read Collection|PermissionUser[] $permissionUser
 *
 * @mixin \Eloquent
 */
class Permission extends LaratrustPermission
{
    protected $table = 'permissions';

    protected $fillable = [
        'name', 'display_name', 'description',
    ];

    /**
     * Get permission users associated with this permission.
     *
     * @return HasMany
     */
    public function permissionUser()
    {
        return $this->hasMany('App\Models\PermissionUser', 'permission_id');
    }
}
