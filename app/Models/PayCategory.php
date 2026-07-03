<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PayCategory
 *
 * Model for managing pay categories.
 *
 * @property int $id
 * @property string $name
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 *
 * @mixin \Eloquent
 */
class PayCategory extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['name'];
}
