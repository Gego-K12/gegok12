<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors
/**
 * Class ActivityLog
 *
 * Model for activity logs.
 *
 * @property int $id
 * @property string $log_name
 * @property string $description
 * @property int $subject_id
 * @property string $subject_type
 * @property int $causer_id
 * @property string $causer_type
 * @property array $properties
 * @property string|null $batch_uuid
 * @property-read \App\Models\User $user
 *
 * @mixin \Eloquent
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    //

    protected $table = 'activity_log';

    protected $fillable = [
        'log_name', 'description', 'subject_id', 'subject_type', 'causer_id', 'causer_type', 'properties', 'batch_uuid',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    protected $with = ['user'];

    /**
     * Get the user who caused the activity.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'causer_id');
    }
}
