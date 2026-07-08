<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Models\School;
use App\Models\User;
use App\Traits\Dashboard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Covers App\Traits\Dashboard: adminDashboard() and receptionDashboard()
 * used the same Cache::remember() keys ('studentCount_'.$school_id and
 * 'teacherCount_'.$school_id) for two DIFFERENT queries - admin's counts
 * exclude exited users, reception's don't. Whichever role's dashboard
 * loaded first "won" the cache for the other role until the entry
 * expired, so one role would see the other's count.
 */
class DashboardCacheKeyTest extends TestCase
{
    use RefreshDatabase;

    private function dashboardTrait()
    {
        return new class
        {
            use Dashboard;
        };
    }

    public function test_admin_and_reception_student_and_teacher_counts_dont_collide(): void
    {
        $school = School::factory()->create();
        User::factory()->student()->for($school)->create(['status' => 'active']);
        User::factory()->student()->for($school)->create(['status' => 'exit']);
        User::factory()->teacher()->for($school)->create(['status' => 'active']);
        User::factory()->teacher()->for($school)->create(['status' => 'exit']);

        $dashboard = $this->dashboardTrait();

        // Admin's counts exclude exited users; load admin's dashboard
        // first so its (smaller) counts would poison a shared cache key.
        $admin = $dashboard->adminDashboard($school->id, 1);
        $this->assertSame(1, $admin['studentCount']);
        $this->assertSame(1, $admin['teacherCount']);

        // Reception's counts include everyone regardless of status - if
        // this reads back admin's cached value instead of running its
        // own query, it will incorrectly report 1 instead of 2.
        $reception = $dashboard->receptionDashboard($school->id, 1);
        $this->assertSame(2, $reception['studentCount']);
        $this->assertSame(2, $reception['teacherCount']);

        // And loading admin's dashboard again must still reflect admin's
        // own (excluding-exited) counts, not get overwritten by
        // reception's broader query.
        $adminAgain = $dashboard->adminDashboard($school->id, 1);
        $this->assertSame(1, $adminAgain['studentCount']);
        $this->assertSame(1, $adminAgain['teacherCount']);
    }
}
