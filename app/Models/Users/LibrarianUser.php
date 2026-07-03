<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models\Users;

use App\Models\BookLending;
use App\Models\LibraryCard;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class LibrarianUser
 *
 * Specialized User model for librarian-specific functionality.
 * Extends the base User model with library management features.
 *
 * @property-read LibraryCard $librarycard
 * @property-read Collection|BookLending[] $lending
 *
 * @mixin \Eloquent
 */
class LibrarianUser extends User
{
    /**
     * Get book lending records managed by this librarian.
     *
     * @return Collection
     */
    public function getLibraryCirculation()
    {
        return $this->school->lending()->paginate(50);
    }

    /**
     * Get all active book borrowings (not returned).
     *
     * @return Collection
     */
    public function getActiveLending()
    {
        return $this->lending()
            ->where('return_date', null)
            ->get();
    }

    /**
     * Get overdue book borrowings.
     *
     * @return Collection
     */
    public function getOverdueBooks()
    {
        return $this->lending()
            ->where('return_date', null)
            ->where('expected_return_date', '<', now())
            ->get();
    }

    /**
     * Get library card holders by school.
     *
     * @return Collection
     */
    public function getLibraryMembers()
    {
        return LibraryCard::where('school_id', $this->school_id)->get();
    }
}
