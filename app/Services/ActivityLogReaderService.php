<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class ActivityLogReaderService
 *
 * Owns the read side shared byte-for-byte by all 6 ActivityLogController
 * copies (Admin, Accountant, Librarian, Receptionist, Student, Teacher):
 * a paginated list of activity log entries caused by the given user.
 * Inherently self-scoped by `causer_id` - no cross-tenant risk, since a
 * user can only ever see their own activity.
 */
class ActivityLogReaderService
{
    public function forUser(int $userId): LengthAwarePaginator
    {
        return ActivityLog::where('causer_id', $userId)
            ->orderBy('id', 'desc')
            ->paginate(10);
    }
}
