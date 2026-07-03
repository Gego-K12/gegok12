<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Services;

use App\Models\Events;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class HolidaysReaderService
 *
 * Owns the read side of holiday listing, shared by every role dashboard
 * (Admin, Librarian, Teacher, Receptionist, Student, Accountant) and the
 * Teacher mobile API. Unlike notices, holidays have no per-role audience
 * scoping - every role sees the same school-wide list - so this service
 * is intentionally thin. Admin\HolidaysController keeps ownership of
 * create/update/delete.
 */
class HolidaysReaderService
{
    public function paginatedList(int $schoolId, int $academicYearId, int $perPage = 10): LengthAwarePaginator
    {
        return Events::where('school_id', $schoolId)
            ->where('academic_year_id', $academicYearId)
            ->where('category', 'holidays')
            ->orderBy('start_date', 'ASC')
            ->paginate($perPage);
    }

    /**
     * Look up a single holiday, scoped to a school so one tenant can never
     * edit or delete another tenant's holiday by guessing its id.
     */
    public function find(int $id, int $schoolId): ?Events
    {
        return Events::where('id', $id)
            ->where('school_id', $schoolId)
            ->where('category', 'holidays')
            ->first();
    }
}
