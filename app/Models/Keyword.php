<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Keyword
 *
 * Model for managing searchable keywords.
 *
 * @property int $id
 * @property string $name
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 *
 * @mixin \Eloquent
 */
class Keyword extends Model
{
    protected $table = 'keywords';

    protected $fillable = [
        'name',
    ];
}
