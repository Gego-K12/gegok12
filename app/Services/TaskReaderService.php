<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Services;

use App\Models\Task;
use App\Models\TaskAssignee;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class TaskReaderService
 *
 * Owns the read side shared by all 8 TaskController copies (Admin,
 * Accountant, Librarian, Receptionist, Student, Teacher, Api,
 * Api\Teacher): the school-scoped single-task lookup, the
 * showlist()/myActiveList()/etc. query family, and the assignee
 * resolution used by show()/editList(). The "mark complete" family and
 * dropdown/create-form data are deliberately NOT here - they're
 * genuinely different per role (three different completion-check
 * styles; some roles' create UI is self-task-only by design) rather
 * than incidental duplication, so unifying them would be guessing at
 * product behavior instead of just deduplicating code.
 */
class TaskReaderService
{
    public function find(int $id, int $schoolId): ?Task
    {
        return Task::where('id', $id)->where('school_id', $schoolId)->first();
    }

    /**
     * The showlist()/myActiveList()/myCompletedList()/activeList()/
     * completedList() query, shared by all 8 controllers. $search and
     * $orderByIdDesc preserve each role's exact current behavior (some
     * roles support neither, some support one or both) rather than
     * guessing at a single "correct" shape.
     *
     * The search filter is now properly grouped - the original code in
     * Admin/Student/Teacher did a top-level ->orWhere() that broke out
     * of the school/year/type/status AND-grouping entirely, leaking
     * tasks from any school or user whose to_do_list matched the search
     * term. Same anti-pattern already fixed in NoticeBoard/Holidays/
     * Events.
     */
    public function listByType(
        int $schoolId,
        int $academicYearId,
        ?string $type,
        int $userId,
        $status,
        ?string $search = null,
        bool $orderByIdDesc = false
    ): Collection {
        $query = Task::where('school_id', $schoolId)
            ->where('academic_year_id', $academicYearId)
            ->ByType($type, $userId)
            ->ByStatus($status);

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('to_do_list', 'LIKE', "%{$search}%");
            });
        }

        if ($orderByIdDesc) {
            $query->orderBy('id', 'desc');
        }

        return $query->get();
    }

    /**
     * Resolves which teachers/students/class a task's TaskAssignee rows
     * point at - the loop shared near-identically by show()/editList()
     * across 6 of the 8 controllers. Matches the original loop exactly:
     * for a multi-class or multi-student assignment, standardLinkId/
     * className end up reflecting the *last* matching assignee row, not
     * an aggregate - that's the pre-existing behavior, not something
     * this consolidation changes.
     *
     * Fixes a data-loss bug present in Admin/Librarian/Receptionist/
     * Student's editList(): the original code set
     * $array['standardLink_id'] for type='class' *inside* the loop, then
     * immediately did `$array = [];` right after the loop, silently
     * discarding it - so editing a class-type task never pre-selected
     * its class in the edit form. Returning standardLinkId separately
     * here means callers can't lose it that way.
     *
     * @return array{selectedTeachers: array<int>, selectedUsers: array<int>, standardLinkId: ?int, className: ?string, lastTaskAssigneeId: ?int}
     */
    public function resolveAssignees(Task $task): array
    {
        $selectedTeachers = [];
        $selectedUsers = [];
        $standardLinkId = null;
        $className = null;
        $lastTaskAssigneeId = null;

        $taskAssignees = TaskAssignee::where('task_id', $task->id)->with('standardLink')->get();

        foreach ($taskAssignees as $taskAssignee) {
            $lastTaskAssigneeId = $taskAssignee->id;

            if ($task->type == 'teacher') {
                $selectedTeachers[] = $taskAssignee->user_id;
            } elseif ($task->type == 'student') {
                $selectedUsers[] = $taskAssignee->user_id;
                $standardLinkId = $taskAssignee->standardLink_id;
                $className = $taskAssignee->standardLink?->StandardSection;
            } elseif ($task->type == 'class') {
                $standardLinkId = $taskAssignee->standardLink_id;
                $className = $taskAssignee->standardLink?->StandardSection;
            }
        }

        return [
            'selectedTeachers' => $selectedTeachers,
            'selectedUsers' => $selectedUsers,
            'standardLinkId' => $standardLinkId,
            'className' => $className,
            'lastTaskAssigneeId' => $lastTaskAssigneeId,
        ];
    }
}
