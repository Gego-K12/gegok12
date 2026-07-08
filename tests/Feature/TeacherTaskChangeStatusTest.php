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
use Tests\TestCase;

/**
 * Covers the fix to Api\Teacher\TaskController::changeStatus(): it used
 * to look up TaskAssignee::findOrFail($id) where $id is a Task id
 * (validated via exists:task,id) - a different, independently
 * auto-incrementing table - with no ownership check at all.
 */
class TeacherTaskChangeStatusTest extends TestCase
{
    use RefreshDatabase;

    private function activeAcademicYear(School $school): AcademicYear
    {
        return AcademicYear::where('school_id', $school->id)->where('status', 1)->firstOrFail();
    }

    private function createTaskWithAssignee(School $school, AcademicYear $year, User $creator, User $assignee): array
    {
        $task = Task::create([
            'school_id' => $school->id,
            'academic_year_id' => $year->id,
            'user_id' => $creator->id,
            'title' => 'Test Task',
            'type' => 'teacher',
            'task_date' => now(),
            'reminder' => 'others',
            'to_do_list' => 'Do the thing',
            'task_status' => 0,
            'task_flag' => 1,
        ]);

        $taskAssignee = TaskAssignee::create([
            'task_id' => $task->id,
            'user_id' => $assignee->id,
            'assigned_type' => 'user',
            'status' => 'pending',
        ]);

        return [$task, $taskAssignee];
    }

    public function test_teacher_can_complete_their_own_task_assignment(): void
    {
        $school = School::factory()->create();
        $year = $this->activeAcademicYear($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();
        $teacher = User::factory()->teacher()->for($school)->create();

        [$task, $assignee] = $this->createTaskWithAssignee($school, $year, $admin, $teacher);

        Sanctum::actingAs($teacher);
        $response = $this->postJson('/api/teacher/tasks/mark/complete', [
            'task_completed' => [$task->id],
        ]);

        $response->assertOk();
        $this->assertSame('completed', $assignee->fresh()->status);
        $this->assertSame(1, (int) $task->fresh()->task_status);
    }

    public function test_teacher_cannot_complete_another_users_task_assignment(): void
    {
        // Regression test: previously TaskAssignee::findOrFail($id) treated
        // a Task id as a TaskAssignee primary key with no ownership check,
        // so a teacher could complete (or corrupt) an unrelated user's
        // assignment if the ids happened to coincide across the two
        // independently auto-incrementing tables.
        $school = School::factory()->create();
        $year = $this->activeAcademicYear($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();
        $teacherA = User::factory()->teacher()->for($school)->create();
        $teacherB = User::factory()->teacher()->for($school)->create();

        [$taskB, $assigneeB] = $this->createTaskWithAssignee($school, $year, $admin, $teacherB);

        Sanctum::actingAs($teacherA);
        $response = $this->postJson('/api/teacher/tasks/mark/complete', [
            'task_completed' => [$taskB->id],
        ]);

        $response->assertStatus(500);
        $this->assertSame('pending', $assigneeB->fresh()->status);
        $this->assertSame(0, (int) $taskB->fresh()->task_status);
    }
}
