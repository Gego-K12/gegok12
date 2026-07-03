<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Plan
 *
 * Model for managing subscription plans.
 *
 * @property int $id
 * @property int $cycle
 * @property string $name
 * @property int $order
 * @property int $active
 * @property float $amount
 * @property int $no_of_members
 * @property int $no_of_events
 * @property int $no_of_folders
 * @property int $no_of_files
 * @property int $no_of_bulletins
 * @property int $no_of_videos
 * @property int $no_of_audios
 * @property int $no_of_groups
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read Collection|Subscription[] $subscription
 *
 * @mixin \Eloquent
 */
class Plan extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cycle', 'name', 'order', 'active', 'amount', 'no_of_members', 'no_of_events', 'no_of_folders', 'no_of_files', 'no_of_bulletins', 'no_of_videos', 'no_of_audios', 'no_of_groups',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Get subscriptions for this plan.
     *
     * @return HasMany
     */
    public function subscription()
    {
        return $this->hasMany('App\\Models\\Subscription', 'plan_id', 'id');
    }
}
