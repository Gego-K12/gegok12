<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Models\School;
use App\Models\Standard;
use App\Models\User;
use App\Models\Userprofile;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * TeacherStatusLifecycleTest
 *
 * Tests the complete lifecycle of a teacher user's status transitions:
 * - Active teacher can login successfully
 * - Same teacher with inactive status cannot login
 * - Same teacher with exit status cannot login
 * - Teacher data persists after status changes (for historical records)
 *
 * This test demonstrates that users are never deleted from the system,
 * only their status changes to prevent future logins while preserving
 * historical data (marks, feedback, attendance records, etc.).
 */
class TeacherStatusLifecycleTest extends TestCase
{
    use RefreshDatabase;

    private $school;

    private $teacher;

    private $teacherEmail = 'lifecycle.teacher@school.com';

    private $teacherPassword = 'password123';

    /**
     * Setup - Create school and teacher once for the entire test suite
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create a school
        $this->school = School::factory()->create();

        // Create a teacher with active status
        $this->teacher = User::factory()
            ->teacher()
            ->for($this->school)
            ->create([
                'email' => $this->teacherEmail,
                'password' => bcrypt($this->teacherPassword),
            ]);

        // Create userprofile with active status
        Userprofile::create([
            'user_id' => $this->teacher->id,
            'school_id' => $this->school->id,
            'usergroup_id' => $this->teacher->usergroup_id,
            'firstname' => 'Active',
            'lastname' => 'Teacher',
            'status' => 'active',
        ]);
    }

    /**
     * Test 1: Teacher with ACTIVE status CAN login
     */
    public function test_step_1_active_teacher_can_login(): void
    {
        // Refresh teacher from database
        $teacher = User::find($this->teacher->id);

        // Verify userprofile status is active
        $this->assertEquals('active', $teacher->userprofile->status);

        // Attempt login
        $response = $this->post('/login', [
            'email' => $this->teacherEmail,
            'password' => $this->teacherPassword,
        ]);

        // Assert successful login
        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($teacher);

        // Logout for next test
        $this->post('/logout');
        $this->assertGuest();
    }

    /**
     * Test 2: After setting teacher to INACTIVE, login FAILS
     */
    public function test_step_2_inactive_teacher_cannot_login(): void
    {
        // Update the same teacher's userprofile to inactive
        $this->teacher->userprofile()->update(['status' => 'inactive']);

        // Verify status is now inactive
        $this->assertEquals('inactive', $this->teacher->fresh()->userprofile->status);

        // Attempt login with the same credentials
        $response = $this->post('/login', [
            'email' => $this->teacherEmail,
            'password' => $this->teacherPassword,
        ]);

        // Assert login fails with validation error
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    /**
     * Test 3: After setting teacher to EXIT, login FAILS
     */
    public function test_step_3_exit_teacher_cannot_login(): void
    {
        // Update the same teacher's userprofile to exit
        $this->teacher->userprofile()->update(['status' => 'exit']);

        // Verify status is now exit
        $this->assertEquals('exit', $this->teacher->fresh()->userprofile->status);

        // Attempt login with the same credentials
        $response = $this->post('/login', [
            'email' => $this->teacherEmail,
            'password' => $this->teacherPassword,
        ]);

        // Assert login fails with validation error
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    /**
     * Test 4: Verify teacher data remains intact (not deleted)
     * This ensures the user record exists in the system even though she cannot login
     */
    public function test_step_4_teacher_data_exists_after_exit(): void
    {
        // First, set teacher to exit status (simulating what previous tests did)
        $teacher = User::find($this->teacher->id);
        $teacher->userprofile()->update(['status' => 'exit']);

        // Verify the teacher record still exists in database
        $freshTeacher = User::where('email', $this->teacherEmail)->first();
        $this->assertNotNull($freshTeacher);

        // Verify teacher still has access to historical data
        $this->assertEquals($this->teacher->id, $freshTeacher->id);
        $this->assertEquals($this->school->id, $freshTeacher->school_id);

        // Verify userprofile exists with exit status
        $this->assertNotNull($freshTeacher->userprofile);
        $this->assertEquals('exit', $freshTeacher->userprofile->status);
    }

    /**
     * Test 5: Relieving a teacher through the real admin "Relieve" action
     * (not a direct DB update) sets relieved_at and immediately blocks
     * that teacher from logging in.
     */
    public function test_step_5_relieving_teacher_via_admin_action_sets_relieved_at_and_blocks_login(): void
    {
        // The admin routes require at least one Standard to pass the
        // MustBePrivilege middleware.
        Standard::create([
            'school_id' => $this->school->id,
            'name' => '1',
            'slug' => '1',
            'order' => '01',
            'status' => 1,
        ]);

        $admin = User::factory()->schoolAdmin()->for($this->school)->create();

        $teacher = User::find($this->teacher->id);
        $this->assertEquals('active', $teacher->userprofile->status);
        $this->assertNull($teacher->userprofile->relieved_at);

        // Relieve the teacher via the real admin action (same endpoint the
        // "Relieve" button on the teacher detail page calls), not a direct
        // database mutation.
        $response = $this->actingAs($admin)->post('/admin/user/updateStatus/'.$teacher->name, [
            'status' => 'exit',
        ]);

        $response->assertRedirect();

        $teacher->refresh();
        $this->assertEquals('exit', $teacher->userprofile->status);
        $this->assertNotNull($teacher->userprofile->relieved_at);
        $this->assertEquals(
            now()->format('Y-m-d'),
            Carbon::parse($teacher->userprofile->relieved_at)->format('Y-m-d')
        );

        // Log the admin out before attempting to log in as the relieved teacher.
        $this->post('/logout');

        $loginResponse = $this->post('/login', [
            'email' => $this->teacherEmail,
            'password' => $this->teacherPassword,
        ]);

        $loginResponse->assertSessionHasErrors();
        $this->assertGuest();
    }
}
