<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BookLending
 *
 * Model for managing book lending transactions and history.
 *
 * @property int $id
 * @property int $user_id
 * @property string $book_code_no
 * @property string $library_card_no
 * @property \DateTime $issue_date
 * @property \DateTime $return_date
 * @property int $issued_by
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read Collection|User[] $user
 * @property-read Collection|Book[] $book
 * @property-read User $userlent
 *
 * @mixin \Eloquent
 */
class BookLending extends Model
{
    use SoftDeletes;

    protected $table = 'books_lending';

    protected $fillable = [
        'user_id', 'book_code_no', 'library_card_no', 'issue_date', 'return_date', 'issued_by', 'status',
    ];

    /**
     * Get the users associated with this lending record.
     *
     * @return HasMany
     */
    public function user()
    {
        return $this->hasMany('\App\Models\User', 'id', 'user_id');
    }

    /**
     * Get the books associated with this lending record.
     *
     * @return HasMany
     */
    public function book()
    {
        return $this->hasMany('\App\Models\Book', 'book_code', 'book_code_no');
    }

    /**
     * Get the user who borrowed this book.
     *
     * @return BelongsTo
     */
    public function userlent()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
