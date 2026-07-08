<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CallLog
 *
 * Model for managing call logs and communication records.
 *
 * @property int $id
 * @property int $school_id
 * @property int $academic_year_id
 * @property string $call_type
 * @property string $calling_purpose
 * @property string $name
 * @property \DateTime $call_date
 * @property string $start_time
 * @property string $end_time
 * @property string $duration
 * @property string $description
 * @property int $entry_by
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 *
 * @mixin \Eloquent
 */
class CallLog extends Model
{
    protected $table = 'call_log';

    protected $fillable = [
        'school_id', 'academic_year_id', 'call_type', 'calling_purpose', 'name', 'call_date', 'start_time', 'end_time', 'duration', 'description', 'entry_by',
    ];
}
