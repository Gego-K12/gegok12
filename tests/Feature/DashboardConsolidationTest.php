<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\School;
use App\Models\Task;
use App\Models\TaskAssignee;
use App\Models\User;
use App\Services\DashboardReaderService;
use App\Traits\Dashboard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Covers two fixes made while consolidating the 6 DashboardControllers'
 * list()/listCount() task widgets into DashboardReaderService, and
 * accountantDashboard()'s cleanup:
 *
 * - list() (the widget itself) was missing the `user_id = $userId`
 *   (creator) filter that listCount() (its own count badge) already
 *   applied in 5 of the 6 controllers, so a task assigned to a user by
 *   someone else could appear in the widget's list while being excluded
 *   from its own count badge.
 * - accountantDashboard() computed library stats (book/lending/card
 *   holder/category counts) that resources/views/accountant/dashboard.blade.php
 *   never references - an unadapted copy-paste of librarianDashboard().
 */
class DashboardConsolidationTest extends TestCase
{
    use RefreshDatabase;

    private function activeAcademicYear(School $school): AcademicYear
    {
        return AcademicYear::where('school_id', $school->id)->where('status', 1)->firstOrFail();
    }

    private function createTaskAssignedTo(School $school, AcademicYear $year, User $creator, User $assignee): Task
    {
        $task = Task::create([
            'school_id' => $school->id,
            'academic_year_id' => $year->id,
            'user_id' => $creator->id,
            'title' => 'Some Task',
            'type' => 'self',
            'task_date' => now(),
            'reminder' => 'others',
            'reminder_date' => now(),
            'to_do_list' => 'Do the thing',
            'task_status' => 0,
            'task_flag' => 1,
        ]);

        TaskAssignee::create([
            'task_id' => $task->id,
            'user_id' => $assignee->id,
            'assigned_type' => 'user',
            'status' => 'pending',
        ]);

        return $task;
    }

    public function test_task_widget_list_and_count_agree_on_tasks_created_by_someone_else(): void
    {
        $school = School::factory()->create();
        $year = $this->activeAcademicYear($school);
        $accountant = User::factory()->accountant()->for($school)->create();
        $admin = User::factory()->schoolAdmin()->for($school)->create();

        // Assigned to the accountant, but created by someone else - the
        // bug made this show up in list() while being excluded from
        // listCount(), a widget/badge mismatch.
        $this->createTaskAssignedTo($school, $year, $admin, $accountant);

        // Self-created-and-assigned - should appear in both.
        $this->createTaskAssignedTo($school, $year, $accountant, $accountant);

        $reader = app(DashboardReaderService::class);

        $list = $reader->taskWidgetList($school->id, $accountant->id, 1);
        $counts = $reader->taskWidgetCounts($school->id, $accountant->id);

        $this->assertCount(1, $list);
        $this->assertSame($accountant->id, $list->first()->user_id);
        $this->assertSame(1, array_sum($counts->toArray()));
    }

    public function test_accountant_dashboard_no_longer_computes_unused_library_stats(): void
    {
        $school = School::factory()->create();
        $accountant = User::factory()->accountant()->for($school)->create();

        $dashboard = new class
        {
            use Dashboard;
        };

        $data = $dashboard->accountantDashboard($school->id, $accountant->id);

        $this->assertArrayNotHasKey('bookCount', $data);
        $this->assertArrayNotHasKey('booklendingCount', $data);
        $this->assertArrayNotHasKey('cardHolderCount', $data);
        $this->assertArrayNotHasKey('categoryCount', $data);
        $this->assertArrayHasKey('noticeboard', $data);
        $this->assertArrayHasKey('events', $data);
    }
}
