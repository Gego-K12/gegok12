<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class NonScholastic
 *
 * Model for non-scholastic grade information.
 *
 * @property int $id
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 *
 * @mixin \Eloquent
 */
class NonScholastic extends Model
{
    protected $table = 'non_sc_grade';
}
