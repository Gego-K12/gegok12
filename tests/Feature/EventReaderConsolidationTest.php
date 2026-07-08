<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Models\Events;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Concerns\BuildsNoticeBoardFixtures;
use Tests\TestCase;

/**
 * Covers the shared EventReaderService methods now used by Accountant,
 * Teacher, Student, Receptionist, and both mobile app EventsControllers.
 */
class EventReaderConsolidationTest extends TestCase
{
    use BuildsNoticeBoardFixtures;
    use RefreshDatabase;

    private function createEvent(School $school, string $category, ?int $standardId = null, string $selectType = 'school'): Events
    {
        $year = $this->createActiveAcademicYear($school);
        $event = new Events([
            'school_id' => $school->id,
            'academic_year_id' => $year->id,
            'select_type' => $selectType,
            'standard_id' => $standardId,
            'title' => 'Some Event',
            'description' => 'x',
            'category' => $category,
            'start_date' => now()->addWeek(),
            'end_date' => now()->addWeek()->addHour(),
            'status' => 'active',
        ]);
        $event->batch = 'default';
        $event->color = 'green';
        $event->save();

        return $event;
    }

    public function test_accountant_show_no_longer_crashes_on_non_exam_event(): void
    {
        // Regression test: Accountant\EventsController::show() previously
        // did an unconditional Exam lookup for every event, dereferencing
        // a null $exam->id for any non-exam category.
        $school = School::factory()->create();
        $accountant = User::factory()->accountant()->for($school)->create();
        $event = $this->createEvent($school, 'meeting');

        $response = $this->actingAs($accountant)->get('/accountant/events/show/details/'.$event->id);

        $response->assertOk();
    }

    public function test_events_expansion_consolidated_across_roles(): void
    {
        $school = School::factory()->create();
        $this->createEvent($school, 'culturals');

        $teacher = User::factory()->teacher()->for($school)->create();
        $student = User::factory()->student()->for($school)->create();
        $receptionist = User::factory()->receptionist()->for($school)->create();
        $accountant = User::factory()->accountant()->for($school)->create();

        $routes = [
            [$teacher, '/teacher/events/show'],
            [$student, '/student/events/show'],
            [$receptionist, '/receptionist/events/show'],
            [$accountant, '/accountant/events/show'],
        ];

        foreach ($routes as [$user, $uri]) {
            $response = $this->actingAs($user)->getJson($uri);
            $response->assertOk();
            $this->assertNotEmpty($response->json());
        }
    }

    public function test_teacher_app_class_events_uses_correct_standard_link_ids(): void
    {
        // Regression test: Api\Teacher\EventsController::class() previously
        // resolved a teacher's classes via pluck('standardLink.standard_id')
        // - the grade id, not the StandardLink id Events.standard_id
        // actually stores - so it never matched real class events.
        $school = School::factory()->create();
        $year = $this->createActiveAcademicYear($school);
        $teacherA = User::factory()->teacher()->for($school)->create();
        $teacherB = User::factory()->teacher()->for($school)->create();

        $linkA = $this->createStandardLink($school, $year, $teacherA);
        $linkB = $this->createStandardLink($school, $year, $teacherB);

        $classAEvent = $this->createEvent($school, 'meeting', $linkA->id, 'class');
        $classBEvent = $this->createEvent($school, 'meeting', $linkB->id, 'class');

        Sanctum::actingAs($teacherA);
        $response = $this->getJson('/api/teacher/my-events/'.$teacherA->id.'/class');

        $response->assertOk();
        $ids = collect($response->json('data'))->pluck('event_id')->all();
        $this->assertContains($classAEvent->id, $ids);
        $this->assertNotContains($classBEvent->id, $ids);
    }
}
