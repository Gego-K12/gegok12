<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Events;
use App\Models\School;
use App\Models\Standard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Covers EventReaderService/EventWriterService: Admin\EventsController's
 * show/edit/update/eventapprove/changeevent/destroy used to look up an
 * event by id with no school_id scoping at all, so an admin from one
 * school could view, edit, approve, reschedule, or delete another
 * school's event by guessing its id. Every one of those six is tested
 * here against a second school's event.
 */
class EventCrossTenantScopingTest extends TestCase
{
    use RefreshDatabase;

    private function activeAcademicYear(School $school): AcademicYear
    {
        return AcademicYear::where('school_id', $school->id)->where('status', 1)->firstOrFail();
    }

    /**
     * MustBePrivilege middleware redirects every /admin/* route to
     * /admin/standard/create until the school has at least one Standard.
     */
    private function satisfyAdminOnboarding(School $school): void
    {
        Standard::create([
            'school_id' => $school->id,
            'name' => 'Grade 1',
            'slug' => 'grade-1-'.uniqid(),
            'status' => 1,
        ]);
    }

    private function createEvent(School $school, AcademicYear $year, string $category = 'meeting'): Events
    {
        $event = new Events([
            'school_id' => $school->id,
            'academic_year_id' => $year->id,
            'select_type' => 'school',
            'title' => 'Staff Meeting',
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

    /** @return array{0: User, 1: Events} admin from school A, event belonging to school B */
    private function adminAndAnotherSchoolsEvent(): array
    {
        $school = School::factory()->create();
        $this->satisfyAdminOnboarding($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();

        $otherSchool = School::factory()->create();
        $otherYear = $this->activeAcademicYear($otherSchool);
        $otherEvent = $this->createEvent($otherSchool, $otherYear);

        return [$admin, $otherEvent];
    }

    public function test_show_404s_for_another_schools_event(): void
    {
        [$admin, $event] = $this->adminAndAnotherSchoolsEvent();

        $this->actingAs($admin)->get('/admin/events/show/details/'.$event->id)->assertNotFound();
    }

    public function test_edit_returns_empty_for_another_schools_event(): void
    {
        [$admin, $event] = $this->adminAndAnotherSchoolsEvent();

        $response = $this->actingAs($admin)->get('/admin/events/edit/'.$event->id);

        $response->assertOk();
        $this->assertEmpty($response->json('data'));
    }

    public function test_update_404s_for_another_schools_event(): void
    {
        [$admin, $event] = $this->adminAndAnotherSchoolsEvent();

        $response = $this->actingAs($admin)->post('/admin/events/update/'.$event->id, [
            'title' => 'Hijacked',
            'description' => 'x',
            'repeats' => '0',
            'select_type' => 'school',
            'category' => 'meeting',
            'organised_by' => 'x',
            'location' => 'x',
            'start_date' => now()->addWeek()->toDateTimeString(),
            'end_date' => now()->addWeek()->addHour()->toDateTimeString(),
        ]);

        $response->assertNotFound();
        $this->assertSame('Staff Meeting', $event->fresh()->title);
    }

    public function test_eventapprove_404s_for_another_schools_event(): void
    {
        [$admin, $event] = $this->adminAndAnotherSchoolsEvent();
        $event->update(['status' => 'inactive']);

        $this->actingAs($admin)->get('/admin/event/approve/'.$event->id)->assertNotFound();

        $this->assertSame('inactive', $event->fresh()->status);
    }

    public function test_changeevent_404s_for_another_schools_event(): void
    {
        [$admin, $event] = $this->adminAndAnotherSchoolsEvent();
        $originalStart = $event->start_date;

        $this->actingAs($admin)->post('/admin/events/changeevent/'.$event->id, [
            'start_date' => now()->addMonth()->toDateTimeString(),
            'end_date' => now()->addMonth()->addHour()->toDateTimeString(),
        ])->assertNotFound();

        $this->assertEquals($originalStart, $event->fresh()->start_date);
    }

    public function test_destroy_404s_for_another_schools_event(): void
    {
        [$admin, $event] = $this->adminAndAnotherSchoolsEvent();

        $this->actingAs($admin)->get('/admin/events/delete/'.$event->id)->assertNotFound();

        $this->assertNotNull($event->fresh());
    }

    public function test_admin_can_manage_their_own_schools_event(): void
    {
        $school = School::factory()->create();
        $this->satisfyAdminOnboarding($school);
        $year = $this->activeAcademicYear($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();
        $event = $this->createEvent($school, $year);

        $this->actingAs($admin)->get('/admin/events/show/details/'.$event->id)->assertOk();

        $editResponse = $this->actingAs($admin)->get('/admin/events/edit/'.$event->id);
        $editResponse->assertOk();
        $this->assertNotEmpty($editResponse->json('data'));

        $this->actingAs($admin)->get('/admin/event/approve/'.$event->id)->assertRedirect('/admin/dashboard');
        $this->assertSame('active', $event->fresh()->status);

        $this->actingAs($admin)->get('/admin/events/delete/'.$event->id)->assertRedirect('/admin/events');
        $this->assertSoftDeleted('events', ['id' => $event->id]);
    }

    public function test_store_creates_event_scoped_to_the_actors_school(): void
    {
        $school = School::factory()->create();
        $this->satisfyAdminOnboarding($school);
        $year = $this->activeAcademicYear($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();

        $response = $this->actingAs($admin)->post('/admin/events/create', [
            'title' => 'Alumni Meet',
            'description' => 'Annual gathering',
            'repeats' => '0',
            'select_type' => 'alumni',
            'batch' => '2020',
            'category' => 'meeting',
            'organised_by' => 'Admin',
            'location' => 'Auditorium',
            'start_date' => now()->addWeek()->toDateTimeString(),
            'end_date' => now()->addWeek()->addHour()->toDateTimeString(),
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('events', [
            'title' => 'Alumni Meet',
            'school_id' => $school->id,
            'academic_year_id' => $year->id,
            'select_type' => 'alumni',
        ]);
    }
}
