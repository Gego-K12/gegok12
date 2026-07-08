<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LibraryCard
 *
 * Model for managing library card issuance and tracking.
 *
 * @property int $id
 * @property int $school_id
 * @property int $user_id
 * @property string $library_card_no
 * @property int $book_limit
 * @property int $status
 * @property \DateTime|null $expiry_date
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 *
 * @mixin \Eloquent
 */
class LibraryCard extends Model
{
    use HasFactory;

    protected $table = 'library_card';

    protected $fillable = [
        'school_id', 'user_id', 'library_card_no', 'book_limit', 'status', 'expiry_date',
    ];
}
