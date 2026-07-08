<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Book
 *
 * Model for managing library books and their metadata.
 *
 * @property int $id
 * @property int $school_id
 * @property int $academic_year_id
 * @property int $category_id
 * @property string $title
 * @property string $book_code
 * @property string $author
 * @property int $availability
 * @property string $isbn_number
 * @property string $cover_image
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property-read BookCategory $category
 * @property-read BookLending $lending
 *
 * @mixin \Eloquent
 */
class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    protected $fillable = [
        'school_id', 'academic_year_id', 'category_id', 'title', 'book_code', 'author', 'availability', 'isbn_number', 'cover_image',
    ];

    /**
     * Get the category for this book.
     *
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('\App\Models\BookCategory', 'category_id');
    }

    /**
     * Get the lending record for this book.
     *
     * @return BelongsTo
     */
    public function lending()
    {
        return $this->belongsTo('\App\Models\BookLending', 'book_code');
    }
}
