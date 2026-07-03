<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Models\NoticeBoard;
use App\Models\School;
use App\Models\User;
use App\Services\NoticeBoardReaderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Concerns\BuildsNoticeBoardFixtures;
use Tests\TestCase;

/**
 * Covers the domain rules NoticeBoardReaderService is responsible for:
 * school/class/teacher-type scoping, multi-tenant isolation, and the
 * active/expired toggle. These rules used to be reimplemented (with
 * drifting bugs) in 7 separate controllers.
 */
class NoticeBoardVisibilityTest extends TestCase
{
    use BuildsNoticeBoardFixtures;
    use RefreshDatabase;

    private function buildScenario(): array
    {
        $school = School::factory()->create();
        $year = $this->createActiveAcademicYear($school);

        $teacherA = User::factory()->teacher()->for($school)->create();
        $teacherB = User::factory()->teacher()->for($school)->create();
        $student = User::factory()->student()->for($school)->create();
        $receptionist = User::factory()->receptionist()->for($school)->create();
        $accountant = User::factory()->accountant()->for($school)->create();

        $linkA = $this->createStandardLink($school, $year, $teacherA);
        $linkB = $this->createStandardLink($school, $year, $teacherB);

        $this->enrollStudent($student, $linkA, $school, $year);

        $schoolNotice = NoticeBoard::factory()->create([
            'school_id' => $school->id, 'academic_year_id' => $year->id,
            'standardLink_id' => null, 'type' => 'school', 'status' => 1,
            'expire_date' => now()->addWeek(),
        ]);
        $teacherNotice = NoticeBoard::factory()->create([
            'school_id' => $school->id, 'academic_year_id' => $year->id,
            'standardLink_id' => null, 'type' => 'teacher', 'status' => 1,
            'expire_date' => now()->addWeek(),
        ]);
        $classANotice = NoticeBoard::factory()->create([
            'school_id' => $school->id, 'academic_year_id' => $year->id,
            'standardLink_id' => $linkA->id, 'type' => 'class', 'status' => 1,
            'expire_date' => now()->addWeek(),
        ]);
        $classBNotice = NoticeBoard::factory()->create([
            'school_id' => $school->id, 'academic_year_id' => $year->id,
            'standardLink_id' => $linkB->id, 'type' => 'class', 'status' => 1,
            'expire_date' => now()->addWeek(),
        ]);
        $expiredNotice = NoticeBoard::factory()->create([
            'school_id' => $school->id, 'academic_year_id' => $year->id,
            'standardLink_id' => null, 'type' => 'school', 'status' => 0,
            'expire_date' => now()->subWeek(),
        ]);

        return compact(
            'school', 'year', 'teacherA', 'teacherB', 'student', 'receptionist',
            'accountant', 'linkA', 'linkB', 'schoolNotice', 'teacherNotice',
            'classANotice', 'classBNotice', 'expiredNotice'
        );
    }

    private function ids($response): array
    {
        return collect($response->json('data'))->pluck('id')->sort()->values()->all();
    }

    public function test_teacher_web_sees_own_class_school_and_teacher_notices_not_other_class(): void
    {
        $s = $this->buildScenario();

        $response = $this->actingAs($s['teacherA'])->get('/teacher/notice/show/list');

        $response->assertOk();
        $this->assertEqualsCanonicalizing(
            [$s['schoolNotice']->id, $s['teacherNotice']->id, $s['classANotice']->id],
            $this->ids($response)
        );
    }

    public function test_teacher_web_sees_subject_taught_class_too(): void
    {
        $s = $this->buildScenario();
        $this->linkTeacherToSubject($s['teacherA'], $s['linkB'], $s['school'], $s['year']);

        $response = $this->actingAs($s['teacherA'])->get('/teacher/notice/show/list');

        $this->assertContains($s['classBNotice']->id, $this->ids($response));
    }

    public function test_student_web_sees_own_class_and_school_not_teacher_or_other_class(): void
    {
        $s = $this->buildScenario();

        // ->fresh(): UserObserver::created() touches studentAcademicLatest
        // at user-creation time (before enrollment exists), which caches a
        // stale null relation onto that in-memory object. actingAs() would
        // otherwise reuse that exact object for the whole test. A real
        // login always re-fetches the user fresh, so this is a test
        // artifact, not app behavior.
        $response = $this->actingAs($s['student']->fresh())->get('/student/notice/show/list');

        $response->assertOk();
        $this->assertEqualsCanonicalizing(
            [$s['schoolNotice']->id, $s['classANotice']->id],
            $this->ids($response)
        );
    }

    public function test_receptionist_accountant_and_admin_see_every_class_and_teacher_notices(): void
    {
        $s = $this->buildScenario();

        $expected = [$s['schoolNotice']->id, $s['teacherNotice']->id, $s['classANotice']->id, $s['classBNotice']->id];

        $admin = User::factory()->schoolAdmin()->for($s['school'])->create();

        $receptionistResponse = $this->actingAs($s['receptionist'])->get('/receptionist/notice/show/list');
        $accountantResponse = $this->actingAs($s['accountant'])->get('/accountant/notice/show/list');
        $adminResponse = $this->actingAs($admin)->get('/admin/notice/show/list');

        $this->assertEqualsCanonicalizing($expected, $this->ids($receptionistResponse));
        $this->assertEqualsCanonicalizing($expected, $this->ids($accountantResponse));
        $this->assertEqualsCanonicalizing($expected, $this->ids($adminResponse));
    }

    public function test_expired_notices_hidden_by_default_and_shown_with_flag(): void
    {
        $s = $this->buildScenario();

        $default = $this->actingAs($s['receptionist'])->get('/receptionist/notice/show/list');
        $this->assertNotContains($s['expiredNotice']->id, $this->ids($default));

        $withExpired = $this->actingAs($s['receptionist'])->get('/receptionist/notice/show/list?showExpired=true');
        $this->assertContains($s['expiredNotice']->id, $this->ids($withExpired));
    }

    public function test_showing_expired_notices_does_not_leak_another_school(): void
    {
        // Regression test for the pre-existing bug: showExpired=true used a
        // top-level orWhere() that broke out of the school/year scoping
        // entirely, so a second school's notices leaked into the result.
        $s = $this->buildScenario();

        $otherSchool = School::factory()->create();
        $otherYear = $this->createActiveAcademicYear($otherSchool);
        $otherSchoolNotice = NoticeBoard::factory()->create([
            'school_id' => $otherSchool->id, 'academic_year_id' => $otherYear->id,
            'standardLink_id' => null, 'type' => 'school', 'status' => 0,
            'expire_date' => now()->subWeek(),
        ]);

        $response = $this->actingAs($s['receptionist'])->get('/receptionist/notice/show/list?showExpired=true');

        $this->assertNotContains($otherSchoolNotice->id, $this->ids($response));
    }

    public function test_search_and_class_filter_query_params(): void
    {
        $s = $this->buildScenario();
        $s['classANotice']->update(['title' => 'Sports Day Announcement']);

        $bySearch = $this->actingAs($s['receptionist'])->get('/receptionist/notice/show/list?search=Sports');
        $this->assertEqualsCanonicalizing([$s['classANotice']->id], $this->ids($bySearch));

        $byClass = $this->actingAs($s['receptionist'])->get('/receptionist/notice/show/list?standardLink_id='.$s['linkB']->id);
        $this->assertEqualsCanonicalizing([$s['classBNotice']->id], $this->ids($byClass));
    }

    public function test_parent_api_school_tab_returns_only_school_wide_non_teacher_notices(): void
    {
        $s = $this->buildScenario();
        Sanctum::actingAs($s['student']);

        $response = $this->getJson('/api/v2/my-school/notices');

        $response->assertOk();
        $ids = collect($response->json('data'))->pluck('id')->all();
        $this->assertEqualsCanonicalizing([$s['schoolNotice']->id], $ids);
    }

    public function test_parent_api_class_tab_returns_only_that_students_class(): void
    {
        $s = $this->buildScenario();
        Sanctum::actingAs($s['student']);

        $response = $this->getJson('/api/v2/notices/'.$s['student']->id);

        $response->assertOk();
        $ids = collect($response->json('data'))->pluck('id')->all();
        $this->assertEqualsCanonicalizing([$s['classANotice']->id], $ids);
    }

    public function test_parent_api_show_is_scoped_to_the_students_school(): void
    {
        // Regression test: show($id) previously had no school_id scoping
        // at all, so any authenticated parent could fetch any school's
        // notice by id.
        $s = $this->buildScenario();

        $otherSchool = School::factory()->create();
        $otherYear = $this->createActiveAcademicYear($otherSchool);
        $otherSchoolNotice = NoticeBoard::factory()->create([
            'school_id' => $otherSchool->id, 'academic_year_id' => $otherYear->id,
            'standardLink_id' => null, 'type' => 'school', 'status' => 1,
            'expire_date' => now()->addWeek(),
        ]);

        Sanctum::actingAs($s['student']);

        $response = $this->getJson('/api/v2/notice/show/'.$otherSchoolNotice->id);

        $response->assertOk();
        $this->assertEmpty($response->json('data'));
    }

    public function test_standard_links_for_teacher_includes_class_teacher_and_subject_teacher_roles(): void
    {
        // Api\Teacher\NoticeBoardController::showNotices() previously only
        // checked subject-teacher assignments, missing classes where the
        // teacher is the class teacher but doesn't teach a subject there -
        // an inconsistency with the web dashboard. The service is used by
        // both now, so this one test covers both call sites.
        $s = $this->buildScenario();
        $this->linkTeacherToSubject($s['teacherB'], $s['linkA'], $s['school'], $s['year']);

        $service = app(NoticeBoardReaderService::class);

        $teacherAClasses = $service->standardLinksForTeacher($s['school']->id, $s['year']->id, $s['teacherA']->id);
        $teacherBClasses = $service->standardLinksForTeacher($s['school']->id, $s['year']->id, $s['teacherB']->id);

        $this->assertEqualsCanonicalizing([$s['linkA']->id], $teacherAClasses);
        $this->assertEqualsCanonicalizing([$s['linkB']->id, $s['linkA']->id], $teacherBClasses);
    }
}
