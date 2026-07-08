<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class BookCategory
 *
 * Model for managing book categories in the library system.
 *
 * @property int $id
 * @property int $school_id
 * @property string $category
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property-read Collection|Book[] $book
 *
 * @mixin \Eloquent
 */
class BookCategory extends Model
{
    protected $table = 'books_category';

    protected $fillable = [
        'school_id', 'category',
    ];

    /**
     * Get the books in this category.
     *
     * @return HasMany
     */
    public function book()
    {
        return $this->hasMany('App\Models\Book', 'id', 'category_id');
    }
}
