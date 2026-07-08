<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Services;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

/**
 * Class DashboardReaderService
 *
 * Owns the "my tasks" mini-widget query shared byte-for-byte by all 6
 * dashboard controllers (Admin, Accountant, Librarian, Receptionist,
 * Student, Teacher): list() (the widget itself) and listCount() (its
 * flag-grouped count badges). The per-role dashboard summary methods
 * (adminDashboard(), teacherDashboard(), etc. in App\Traits\Dashboard)
 * are NOT here - each one is genuinely different content per role, not
 * incidental duplication.
 */
class DashboardReaderService
{
    /**
     * Admin's list()/listCount() were the only pair of the 6 that
     * consistently filtered by both `user_id` (creator) and
     * `ByType('to_me', ...)` (assignee) - the other 5 controllers had the
     * `user_id` filter in listCount() but not list(), so a task's list
     * and its own count badge could disagree. Both methods here always
     * apply both filters, matching Admin's original (correct) behavior.
     */
    public function taskWidgetList(int $schoolId, int $userId, $taskFlag, ?string $search = null): Collection
    {
        $query = Task::where([
            ['school_id', $schoolId],
            ['user_id', $userId],
            ['task_status', 0],
            ['task_flag', $taskFlag],
        ])->ByType('to_me', $userId);

        if (! empty($search)) {
            $query->where('title', 'LIKE', "%{$search}%");
        }

        return $query->get();
    }

    public function taskWidgetCounts(int $schoolId, int $userId): SupportCollection
    {
        $tasks = Task::where([
            ['school_id', $schoolId],
            ['user_id', $userId],
            ['task_status', 0],
        ])->ByType('to_me', $userId)->get()->groupBy('Flag');

        foreach ($tasks as $key => $value) {
            $tasks[$key] = count($value);
        }

        return $tasks;
    }
}
