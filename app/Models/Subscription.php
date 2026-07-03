<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Subscription
 *
 * Model for managing school subscriptions to plans.
 *
 * @property int $id
 * @property int $school_id
 * @property int $user_id
 * @property int $plan_id
 * @property \DateTime $end_date
 * @property int $status
 * @property array $payment_details
 * @property array $plan_details
 * @property array $card_details
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read School $school
 * @property-read User $user
 * @property-read Plan $plan
 *
 * @mixin \Eloquent
 */
class Subscription extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subscriptions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'user_id', 'plan_id', 'end_date', 'status', 'payment_details', 'plan_details',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $casts = ['payment_details' => 'array', 'card_details' => 'array', 'plan_details' => 'array'];

    /**
     * Get the school for this subscription.
     *
     * @return BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id');
    }

    /**
     * Get the user who owns this subscription.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /**
     * Get the plan for this subscription.
     *
     * @return BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo('App\Models\Plan', 'plan_id');
    }

    /**
     * Scope to filter subscriptions by date range.
     *
     * @param  Builder  $query
     * @param  \DateTime  $start
     * @param  \DateTime  $end
     * @return Builder
     */
    public function scopeDate($query, $start, $end)
    {

        $query->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end);

        return $query;
    }
}
