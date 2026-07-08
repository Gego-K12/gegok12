<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PermissionUser
 *
 * Model for managing user-specific permissions.
 *
 * @property int $id
 * @property int $permission_id
 * @property int $user_id
 * @property string $user_type
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read Permission $permission
 * @property-read User $user
 *
 * @mixin \Eloquent
 */
class PermissionUser extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permission_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'permission_id', 'user_id', 'user_type',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the permission for this user.
     *
     * @return BelongsTo
     */
    public function permission()
    {
        return $this->belongsTo('App\\Models\\Permission', 'permission_id');
    }

    /**
     * Get the user for this permission.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\\Models\\User', 'user_id');
    }
}
