<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Services;

use App\Models\Task;

/**
 * Class TaskReaderService
 *
 * Owns the one school-scoped lookup shared by all 8 TaskController
 * copies (Admin, Accountant, Librarian, Receptionist, Student, Teacher,
 * Api, Api\Teacher). Every one of show()/editList()/edit()/destroy()/
 * update()/snooze() used to do a bare `Task::where('id', $id)->first()`
 * with no `school_id` check, letting anyone view, edit, snooze, or
 * delete another school's task by guessing/incrementing its id.
 *
 * Deliberately narrow for now - this fixes only the missing-scope bug.
 * The much larger duplication across these 8 controllers (show()/
 * editList() body shape, the "mark complete" family, dropdown data,
 * etc.) is a separate, not-yet-started pass, tracked in the todo list.
 */
class TaskReaderService
{
    public function find(int $id, int $schoolId): ?Task
    {
        return Task::where('id', $id)->where('school_id', $schoolId)->first();
    }
}
