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
use Tests\TestCase;

/**
 * Covers Student\TaskController::claim(): the original code called
 * ->first() on the TaskAssignee lookup and then immediately dereferenced
 * it with no null check (fatal error for a bogus/foreign id), and its
 * "already claimed" branch referenced an undefined $task variable instead
 * of $taskAssignee (fatal error the very first time that branch ran).
 */
class StudentTaskClaimTest extends TestCase
{
    use RefreshDatabase;

    private function activeAcademicYear(School $school): AcademicYear
    {
        return AcademicYear::where('school_id', $school->id)->where('status', 1)->firstOrFail();
    }

    private function createOpenTask(School $school, AcademicYear $year, User $creator): Task
    {
        return Task::create([
            'school_id' => $school->id,
            'academic_year_id' => $year->id,
            'user_id' => $creator->id,
            'title' => 'Open Task',
            'type' => 'student',
            'task_type' => 'open',
            'task_date' => now(),
            'reminder' => 'others',
            'reminder_date' => now(),
            'to_do_list' => 'Do the thing',
            'task_status' => 0,
            'task_flag' => 1,
        ]);
    }

    public function test_claim_404s_instead_of_crashing_for_a_bogus_task_id(): void
    {
        $school = School::factory()->create();
        $student = User::factory()->student()->for($school)->create();

        $this->actingAs($student)->post('/student/task/claim/999999')->assertNotFound();
    }

    public function test_student_can_claim_an_open_task(): void
    {
        $school = School::factory()->create();
        $year = $this->activeAcademicYear($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();
        $student = User::factory()->student()->for($school)->create();
        $task = $this->createOpenTask($school, $year, $admin);

        $assignee = TaskAssignee::create([
            'task_id' => $task->id,
            'user_id' => $student->id,
            'assigned_type' => 'user',
            'status' => 'pending',
        ]);

        $this->actingAs($student)->post('/student/task/claim/'.$task->id)
            ->assertOk()
            ->assertJson(['success' => 'Task claimed successfully.']);

        $this->assertSame($student->id, $assignee->fresh()->claimed_by);
    }

    public function test_claiming_an_already_claimed_task_reports_the_claimer_instead_of_crashing(): void
    {
        $school = School::factory()->create();
        $year = $this->activeAcademicYear($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();
        $firstStudent = User::factory()->student()->for($school)->create();
        $secondStudent = User::factory()->student()->for($school)->create();
        $task = $this->createOpenTask($school, $year, $admin);

        TaskAssignee::create([
            'task_id' => $task->id,
            'user_id' => $secondStudent->id,
            'assigned_type' => 'user',
            'status' => 'pending',
            'claimed_by' => $firstStudent->id,
        ]);

        $this->actingAs($secondStudent)->post('/student/task/claim/'.$task->id)
            ->assertStatus(422)
            ->assertJsonFragment([
                'errors' => ['This task has already been claimed by '.$firstStudent->FullName.'.'],
            ]);
    }
}
