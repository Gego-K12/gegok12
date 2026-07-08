<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Events\Notification\ClassNotificationEvent;
use App\Events\StandardPushEvent;
use App\Models\School;
use App\Models\StandardLink;
use App\Models\Task;
use App\Models\TaskAssignee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\Concerns\BuildsNoticeBoardFixtures;
use Tests\TestCase;

/**
 * Covers TodolistProcess::addTaskAssignee()/editTaskAssignee()'s class-type
 * branches: both reassigned the request's $data variable to a plain array
 * midway through (`$data = []; ... $data['standard_id'] = $data->standardLink_id;`),
 * then kept reading $data->standardLink_id afterwards - on an array, not
 * an object. That silently sent null standard/class ids in the push and
 * class-notification events instead of the real class id, and in
 * editTaskAssignee it also broke the addClassReminder() call that runs
 * after the if/else regardless of which branch was taken.
 */
class TaskClassPushNotificationTest extends TestCase
{
    use BuildsNoticeBoardFixtures;
    use RefreshDatabase;

    private function satisfyAdminOnboarding(School $school): void
    {
        \App\Models\Standard::create([
            'school_id' => $school->id,
            'name' => 'Onboarding Grade',
            'slug' => 'onboarding-grade-'.uniqid(),
            'status' => 1,
        ]);
    }

    public function test_creating_a_class_task_sends_push_events_with_the_real_class_id(): void
    {
        Event::fake([StandardPushEvent::class, ClassNotificationEvent::class]);

        $school = School::factory()->create();
        $this->satisfyAdminOnboarding($school);
        $year = $this->createActiveAcademicYear($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();
        $teacher = User::factory()->teacher()->for($school)->create();
        $link = $this->createStandardLink($school, $year, $teacher);

        $this->actingAs($admin)->post('/admin/task/add', [
            'assignee' => 'class',
            'class_ids' => [$link->id],
            'standardLink_id' => $link->id,
            'title' => 'Class Task',
            'to_do_list' => 'Do the class thing',
            'task_date' => now()->addDay()->toDateTimeString(),
            'reminder' => 'others',
            'reminder_date' => now()->addDay()->toDateTimeString(),
            'priority' => 'normal',
            'task_type' => 'individual',
        ])->assertOk();

        Event::assertDispatched(StandardPushEvent::class, function ($event) use ($link) {
            return $event->data['standard_id'] === $link->id;
        });

        Event::assertDispatched(ClassNotificationEvent::class, function ($event) use ($link) {
            return $event->data['standardLink_id'] === $link->id;
        });
    }

    public function test_updating_a_class_task_sends_push_events_with_the_real_class_id(): void
    {
        $school = School::factory()->create();
        $this->satisfyAdminOnboarding($school);
        $year = $this->createActiveAcademicYear($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();
        $teacher = User::factory()->teacher()->for($school)->create();
        $link = $this->createStandardLink($school, $year, $teacher);

        $task = Task::create([
            'school_id' => $school->id,
            'academic_year_id' => $year->id,
            'user_id' => $admin->id,
            'title' => 'Class Task',
            'type' => 'class',
            'task_date' => now(),
            'reminder' => 'others',
            'reminder_date' => now(),
            'to_do_list' => 'Do the class thing',
            'task_status' => 0,
            'task_flag' => 1,
        ]);

        TaskAssignee::create([
            'task_id' => $task->id,
            'standardLink_id' => $link->id,
            'assigned_type' => 'class',
            'status' => 'pending',
        ]);

        Event::fake([StandardPushEvent::class, ClassNotificationEvent::class]);

        $this->actingAs($admin)->post('/admin/task/edit/'.$task->id, [
            'assignee' => 'class',
            'standardLink_id' => $link->id,
            'title' => 'Class Task Updated',
            'to_do_list' => 'Do the class thing',
            'task_date' => now()->addDay()->toDateTimeString(),
            'reminder' => 'others',
            'reminder_date' => now()->addDay()->toDateTimeString(),
            'priority' => 'normal',
            'task_type' => 'individual',
        ])->assertOk();

        Event::assertDispatched(StandardPushEvent::class, function ($event) use ($link) {
            return $event->data['standard_id'] === $link->id;
        });

        Event::assertDispatched(ClassNotificationEvent::class, function ($event) use ($link) {
            return $event->data['standardLink_id'] === $link->id;
        });
    }
}
