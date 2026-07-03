<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PayrollItem
 *
 * Model for managing payroll items (earnings/deductions).
 *
 * @property int $id
 * @property string $name
 * @property string $key
 * @property string $type
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 *
 * @mixin \Eloquent
 */
class PayrollItem extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'key', 'type'];
}
