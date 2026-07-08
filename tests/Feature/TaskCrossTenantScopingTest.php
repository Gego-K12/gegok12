<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\School;
use App\Models\Standard;
use App\Models\Task;
use App\Models\TaskAssignee;
use App\Models\User;
use App\Services\TaskReaderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Covers TaskReaderService: show()/editList()/edit()/update()/snooze()/
 * destroy() in all 8 TaskController copies used to do a bare
 * Task::where('id', $id)->first() with no school_id check, letting
 * anyone view, edit, snooze, or delete another school's task by
 * guessing/incrementing its id.
 */
class TaskCrossTenantScopingTest extends TestCase
{
    use RefreshDatabase;

    private function activeAcademicYear(School $school): AcademicYear
    {
        return AcademicYear::where('school_id', $school->id)->where('status', 1)->firstOrFail();
    }

    private function satisfyAdminOnboarding(School $school): void
    {
        Standard::create([
            'school_id' => $school->id,
            'name' => 'Grade 1',
            'slug' => 'grade-1-'.uniqid(),
            'status' => 1,
        ]);
    }

    private function createSelfTask(School $school, AcademicYear $year, User $owner): Task
    {
        $task = Task::create([
            'school_id' => $school->id,
            'academic_year_id' => $year->id,
            'user_id' => $owner->id,
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
            'user_id' => $owner->id,
            'assigned_type' => 'user',
            'status' => 'pending',
        ]);

        return $task;
    }

    public function test_task_reader_service_only_finds_tasks_for_the_given_school(): void
    {
        $schoolA = School::factory()->create();
        $schoolB = School::factory()->create();
        $adminA = User::factory()->schoolAdmin()->for($schoolA)->create();
        $taskB = $this->createSelfTask($schoolB, $this->activeAcademicYear($schoolB), $adminA);

        $service = app(TaskReaderService::class);

        $this->assertNull($service->find($taskB->id, $schoolA->id));
        $this->assertNotNull($service->find($taskB->id, $schoolB->id));
    }

    public function test_admin_cannot_view_or_delete_another_schools_task(): void
    {
        $school = School::factory()->create();
        $this->satisfyAdminOnboarding($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();

        $otherSchool = School::factory()->create();
        $otherAdmin = User::factory()->schoolAdmin()->for($otherSchool)->create();
        $otherTask = $this->createSelfTask($otherSchool, $this->activeAcademicYear($otherSchool), $otherAdmin);

        $this->actingAs($admin)->get('/admin/task/show/'.$otherTask->id)->assertNotFound();
        $this->actingAs($admin)->get('/admin/task/edit/'.$otherTask->id)->assertNotFound();
        $this->actingAs($admin)->get('/admin/task/'.$otherTask->id.'/delete')->assertNotFound();

        $this->assertNotNull($otherTask->fresh());
    }

    public function test_admin_update_and_snooze_404_for_another_schools_task_instead_of_500(): void
    {
        // Regression test for the try/catch-swallows-abort(404) pattern
        // already found and fixed in Holidays/Events: the existence
        // check has to happen before entering the try block, or
        // abort(404) (a NotFoundHttpException, which extends Exception)
        // gets silently caught and turned into a generic error response.
        $school = School::factory()->create();
        $this->satisfyAdminOnboarding($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();

        $otherSchool = School::factory()->create();
        $otherAdmin = User::factory()->schoolAdmin()->for($otherSchool)->create();
        $otherTask = $this->createSelfTask($otherSchool, $this->activeAcademicYear($otherSchool), $otherAdmin);

        $this->actingAs($admin)->post('/admin/task/edit/'.$otherTask->id, [
            'assignee' => 'self',
            'title' => 'Hijacked',
            'to_do_list' => 'Hijacked',
            'task_date' => now()->addDay()->toDateTimeString(),
            'reminder' => 'others',
            'reminder_date' => now()->addDay()->toDateTimeString(),
            'priority' => 'normal',
        ])->assertNotFound();

        $this->actingAs($admin)->post('/admin/task/snooze/'.$otherTask->id)->assertNotFound();

        $this->assertSame('Some Task', $otherTask->fresh()->title);
        $this->assertSame(0, (int) $otherTask->fresh()->snooze);
    }

    public function test_admin_can_manage_their_own_schools_task(): void
    {
        $school = School::factory()->create();
        $this->satisfyAdminOnboarding($school);
        $year = $this->activeAcademicYear($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();
        $task = $this->createSelfTask($school, $year, $admin);

        $this->actingAs($admin)->get('/admin/task/show/'.$task->id)->assertOk();
        $this->actingAs($admin)->get('/admin/task/'.$task->id.'/delete')->assertOk();
        $this->assertSoftDeleted('task', ['id' => $task->id]);
    }

    public function test_teacher_cannot_view_another_schools_task(): void
    {
        $school = School::factory()->create();
        $teacher = User::factory()->teacher()->for($school)->create();

        $otherSchool = School::factory()->create();
        $otherAdmin = User::factory()->schoolAdmin()->for($otherSchool)->create();
        $otherTask = $this->createSelfTask($otherSchool, $this->activeAcademicYear($otherSchool), $otherAdmin);

        $this->actingAs($teacher)->get('/teacher/task/show/'.$otherTask->id)->assertNotFound();
    }

    public function test_parent_app_cannot_view_another_schools_task(): void
    {
        $school = School::factory()->create();
        $parent = User::factory()->schoolAdmin()->for($school)->create();

        $otherSchool = School::factory()->create();
        $otherAdmin = User::factory()->schoolAdmin()->for($otherSchool)->create();
        $otherTask = $this->createSelfTask($otherSchool, $this->activeAcademicYear($otherSchool), $otherAdmin);

        Sanctum::actingAs($parent);
        $this->getJson('/api/v2/task/show/'.$otherTask->id)->assertNotFound();
    }

    public function test_teacher_app_cannot_view_another_schools_task(): void
    {
        $school = School::factory()->create();
        $teacher = User::factory()->teacher()->for($school)->create();

        $otherSchool = School::factory()->create();
        $otherAdmin = User::factory()->schoolAdmin()->for($otherSchool)->create();
        $otherTask = $this->createSelfTask($otherSchool, $this->activeAcademicYear($otherSchool), $otherAdmin);

        Sanctum::actingAs($teacher);
        $this->getJson('/api/teacher/task/show/'.$otherTask->id)->assertNotFound();
    }
}
