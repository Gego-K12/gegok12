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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Concerns\BuildsNoticeBoardFixtures;
use Tests\TestCase;

/**
 * Covers Api\Teacher\TaskController::edit() after the TaskReaderService
 * consolidation (the same resolveAssignees()-based shape now shared by
 * Admin/Librarian/Receptionist/Student/Teacher/Api\Teacher). Two
 * regressions were caught and fixed during that consolidation:
 *
 * - Admin's editList() briefly left `$array['selectedUsers'] = $selectedUsers;`
 *   and `$array['teachers'] = $selected_teachers;` referencing the old
 *   per-controller loop variables the consolidation had just removed -
 *   silently turning both into null for every task type. This test hits
 *   the same resolveAssignees()-based code shape in Api\Teacher (Admin's
 *   own edit-list endpoint can't be exercised under the sqlite test DB -
 *   it unconditionally calls SiteHelper::getStandardLinkList(), which
 *   orders by the MySQL-only FIELD() function).
 * - Before consolidation, class-type tasks lost their standardLink_id
 *   entirely in several controllers: the old code set it inside the
 *   assignee loop, then immediately did `$array = [];` right after the
 *   loop, discarding it - so the edit form never pre-selected the class
 *   dropdown.
 */
class TaskEditListConsolidationTest extends TestCase
{
    use BuildsNoticeBoardFixtures;
    use RefreshDatabase;

    private function createTask(School $school, AcademicYear $year, User $owner, string $type): Task
    {
        return Task::create([
            'school_id' => $school->id,
            'academic_year_id' => $year->id,
            'user_id' => $owner->id,
            'title' => 'Task',
            'type' => $type,
            'task_date' => now(),
            'reminder' => 'others',
            'reminder_date' => now(),
            'to_do_list' => 'Do the thing',
            'task_status' => 0,
            'task_flag' => 1,
        ]);
    }

    public function test_edit_returns_selected_student_ids_for_a_student_task(): void
    {
        $school = School::factory()->create();
        $year = $this->createActiveAcademicYear($school);
        $teacher = User::factory()->teacher()->for($school)->create();
        $link = $this->createStandardLink($school, $year, $teacher);
        $student = User::factory()->student()->for($school)->create();

        $task = $this->createTask($school, $year, $teacher, 'student');
        TaskAssignee::create([
            'task_id' => $task->id,
            'user_id' => $student->id,
            'standardLink_id' => $link->id,
            'assigned_type' => 'user',
            'status' => 'pending',
        ]);

        Sanctum::actingAs($teacher);
        $response = $this->getJson('/api/teacher/task/edit/'.$task->id)->assertOk();

        $response->assertJson([
            'data' => [
                'selectedUsers' => [$student->id],
                'standardLink_id' => $link->id,
            ],
        ]);
    }

    public function test_edit_preserves_standard_link_id_for_a_class_task(): void
    {
        $school = School::factory()->create();
        $year = $this->createActiveAcademicYear($school);
        $teacher = User::factory()->teacher()->for($school)->create();
        $link = $this->createStandardLink($school, $year, $teacher);

        $task = $this->createTask($school, $year, $teacher, 'class');
        TaskAssignee::create([
            'task_id' => $task->id,
            'standardLink_id' => $link->id,
            'assigned_type' => 'class',
            'status' => 'pending',
        ]);

        Sanctum::actingAs($teacher);
        $response = $this->getJson('/api/teacher/task/edit/'.$task->id)->assertOk();

        $response->assertJson([
            'data' => [
                'standardLink_id' => $link->id,
            ],
        ]);
    }
}
