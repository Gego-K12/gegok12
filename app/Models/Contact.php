<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Contact
 *
 * Model for managing contact information.
 *
 * @property int $id
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 *
 * @mixin \Eloquent
 */
class Contact extends Model
{
    protected $table = 'contacts';
}
