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
use App\Services\HolidaysReaderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Covers HolidaysReaderService: holidays are school-wide (no per-role
 * audience scoping, unlike notices), so the main things worth testing
 * are multi-tenant isolation, ordering, and the two bugs found while
 * reading all 7 HolidaysController copies.
 */
class HolidaysVisibilityTest extends TestCase
{
    use RefreshDatabase;

    private function activeAcademicYear(School $school): AcademicYear
    {
        // SchoolObserver::created() already creates a "current" (status=1)
        // academic year for every new school.
        return AcademicYear::where('school_id', $school->id)->where('status', 1)->firstOrFail();
    }

    /**
     * MustBePrivilege middleware redirects admin routes to
     * /admin/standard/create until the school has at least one Standard -
     * an onboarding gate unrelated to holidays, but it applies to every
     * /admin/* route.
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

    private function createHoliday(School $school, AcademicYear $year, string $title, string $startDate): Events
    {
        $holiday = new Events([
            'school_id' => $school->id,
            'academic_year_id' => $year->id,
            'select_type' => 'school',
            'title' => $title,
            'category' => 'holidays',
            'start_date' => $startDate,
            'end_date' => $startDate,
            'status' => 'active',
        ]);
        // batch/color are required (NOT NULL) columns but aren't mass
        // assignable - Admin\HolidaysController::store() doesn't set them
        // either, a separate pre-existing bug on the write side.
        $holiday->batch = 'default';
        $holiday->color = '#000000';
        $holiday->save();

        return $holiday;
    }

    public function test_all_roles_see_the_same_school_holidays(): void
    {
        $school = School::factory()->create();
        $year = $this->activeAcademicYear($school);
        $holiday = $this->createHoliday($school, $year, 'Independence Day', '2026-08-15');

        $roles = [
            'librarian' => '/library/holidays/list',
            'receptionist' => '/receptionist/holidays/list',
            'student' => '/student/holidays/list',
            'accountant' => '/accountant/holidays/list',
            'teacher' => '/teacher/holidays/list',
        ];

        foreach ($roles as $role => $uri) {
            $user = User::factory()->$role()->for($school)->create();
            $response = $this->actingAs($user)->get($uri);

            $response->assertOk();
            $ids = collect($response->json('data'))->pluck('id')->all();
            $this->assertEqualsCanonicalizing([$holiday->id], $ids, "failed for role {$role}");
        }

        $this->satisfyAdminOnboarding($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();
        $adminResponse = $this->actingAs($admin)->get('/admin/holidays/list');
        $adminResponse->assertOk();
        $this->assertEqualsCanonicalizing([$holiday->id], collect($adminResponse->json('data'))->pluck('id')->all());
    }

    public function test_holidays_are_scoped_to_school_and_academic_year(): void
    {
        $school = School::factory()->create();
        $year = $this->activeAcademicYear($school);
        $ownHoliday = $this->createHoliday($school, $year, 'Own Holiday', '2026-08-15');

        $otherSchool = School::factory()->create();
        $otherYear = $this->activeAcademicYear($otherSchool);
        $this->createHoliday($otherSchool, $otherYear, 'Other School Holiday', '2026-08-15');

        $accountant = User::factory()->accountant()->for($school)->create();
        $response = $this->actingAs($accountant)->get('/accountant/holidays/list');

        $ids = collect($response->json('data'))->pluck('id')->all();
        $this->assertEqualsCanonicalizing([$ownHoliday->id], $ids);
    }

    public function test_holidays_are_ordered_by_start_date_ascending(): void
    {
        $school = School::factory()->create();
        $year = $this->activeAcademicYear($school);

        $later = $this->createHoliday($school, $year, 'Later Holiday', '2026-12-25');
        $earlier = $this->createHoliday($school, $year, 'Earlier Holiday', '2026-01-01');

        $teacher = User::factory()->teacher()->for($school)->create();
        $response = $this->actingAs($teacher)->get('/teacher/holidays/list');

        $ids = collect($response->json('data'))->pluck('id')->all();
        $this->assertSame([$earlier->id, $later->id], $ids);
    }

    public function test_admin_cannot_edit_another_schools_holiday(): void
    {
        // Regression test: Admin\HolidaysController::edit()/update()/
        // destroy() previously did Events::where('id', $id)->first() with
        // no school_id scoping at all.
        $school = School::factory()->create();
        $this->satisfyAdminOnboarding($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();

        $otherSchool = School::factory()->create();
        $otherYear = $this->activeAcademicYear($otherSchool);
        $otherHoliday = $this->createHoliday($otherSchool, $otherYear, 'Not Yours', '2026-08-15');

        $response = $this->actingAs($admin)->get('/admin/holiday/edit/'.$otherHoliday->id);

        $response->assertNotFound();
    }

    public function test_service_reports_total_count_not_just_current_page(): void
    {
        // Regression test for the pre-existing bug in
        // Api\Teacher\HolidaysController::index() - it computed count via
        // a separate unpaginated ->get() call, before ->paginate() even
        // ran. The paginator's own total() is the correct source of truth.
        $school = School::factory()->create();
        $year = $this->activeAcademicYear($school);

        for ($i = 0; $i < 15; $i++) {
            $this->createHoliday($school, $year, "Holiday {$i}", '2026-01-'.str_pad($i + 1, 2, '0', STR_PAD_LEFT));
        }

        $service = app(HolidaysReaderService::class);
        $page = $service->paginatedList($school->id, $year->id);

        $this->assertCount(10, $page->items());
        $this->assertSame(15, $page->total());
    }

    public function test_admin_can_edit_own_schools_holiday(): void
    {
        $school = School::factory()->create();
        $this->satisfyAdminOnboarding($school);
        $year = $this->activeAcademicYear($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();
        $holiday = $this->createHoliday($school, $year, 'Own Holiday', '2026-08-15');

        $response = $this->actingAs($admin)->get('/admin/holiday/edit/'.$holiday->id);

        $response->assertOk();
        $this->assertSame('Own Holiday', $response->json('title'));
    }
}
