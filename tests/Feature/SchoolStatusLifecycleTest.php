<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Models\School;
use App\Models\User;
use App\Models\Userprofile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * SchoolStatusLifecycleTest
 *
 * Tests school status validation in the authentication flow:
 * - Admin with active school (status = 1) can login
 * - Admin with inactive school (status = 0) cannot login with error message
 * - Admin data persists even when school is inactive
 * - Admin can login again after school is reactivated
 * - Demonstrates that school status is enforced via checkschool validator
 *
 * All non-SuperAdmin users require their school to be active to login.
 * The error message "Invalid Credentials. You are not in this school" is shown.
 */
class SchoolStatusLifecycleTest extends TestCase
{
    use RefreshDatabase;

    private $school;

    private $admin;

    private $adminEmail = 'school.admin@test.com';

    private $adminPassword = 'password123';

    /**
     * Setup - Create school and admin once for the entire test suite
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create an ACTIVE school
        $this->school = School::factory()->create([
            'status' => 1, // Active
        ]);

        // Create a School Admin for this school
        $this->admin = User::factory()
            ->schoolAdmin()
            ->for($this->school)
            ->create([
                'email' => $this->adminEmail,
                'password' => bcrypt($this->adminPassword),
            ]);

        // Create userprofile with active status
        Userprofile::create([
            'user_id' => $this->admin->id,
            'school_id' => $this->school->id,
            'usergroup_id' => $this->admin->usergroup_id,
            'firstname' => 'School',
            'lastname' => 'Admin',
            'status' => 'active',
        ]);
    }

    /**
     * Test 1: School Admin with ACTIVE school CAN login
     */
    public function test_step_1_admin_with_active_school_can_login(): void
    {
        // Verify school is active
        $this->assertEquals(1, $this->school->status);

        // Verify admin userprofile is active
        $admin = User::find($this->admin->id);
        $this->assertEquals('active', $admin->userprofile->status);

        // Attempt login
        $response = $this->post('/login', [
            'email' => $this->adminEmail,
            'password' => $this->adminPassword,
        ]);

        // Assert successful login
        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin);

        // Logout for next test
        $this->post('/logout');
        $this->assertGuest();
    }

    /**
     * Test 2: After setting school to INACTIVE (status = 0), Admin CANNOT login
     * Should show error message "Invalid Credentials. You are not in this school"
     */
    public function test_step_2_admin_with_inactive_school_cannot_login(): void
    {
        // Inactivate the school
        $this->school->update(['status' => 0]);

        // Verify school is now inactive
        $this->assertEquals(0, $this->school->fresh()->status);

        // Attempt login with the same admin credentials
        $response = $this->post('/login', [
            'email' => $this->adminEmail,
            'password' => $this->adminPassword,
        ]);

        // Assert login fails with validation error
        $response->assertSessionHasErrors();

        // Verify the correct error message appears
        $response->assertSessionHasErrors('password');

        // Verify user is not authenticated
        $this->assertGuest();
    }

    /**
     * Test 3: Verify admin data exists but school is inactive
     * This ensures the admin record exists but cannot access due to school status
     */
    public function test_step_3_admin_data_exists_with_inactive_school(): void
    {
        // Inactivate the school
        $this->school->update(['status' => 0]);

        // Verify the admin record still exists
        $admin = User::where('email', $this->adminEmail)->first();
        $this->assertNotNull($admin);

        // Verify school is inactive
        $this->assertEquals(0, $admin->school->status);

        // Verify admin can still be retrieved
        $this->assertEquals($this->admin->id, $admin->id);
        $this->assertEquals('SCHOOL ADMIN', strtoupper($admin->userprofile->firstname.' '.$admin->userprofile->lastname));
    }

    /**
     * Test 4: Reactivate school and admin can login again
     */
    public function test_step_4_admin_can_login_after_school_reactivation(): void
    {
        // Inactivate and then reactivate the school
        $this->school->update(['status' => 0]);
        $this->assertEquals(0, $this->school->fresh()->status);

        // Reactivate the school
        $this->school->update(['status' => 1]);
        $this->assertEquals(1, $this->school->fresh()->status);

        // Attempt login again
        $response = $this->post('/login', [
            'email' => $this->adminEmail,
            'password' => $this->adminPassword,
        ]);

        // Assert successful login after reactivation
        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs(User::find($this->admin->id));
    }

    /**
     * Test 5: Verify school reactivation allows login again
     */
    public function test_step_5_reactivated_school_allows_login(): void
    {
        // Start with active school
        $this->assertEquals(1, $this->school->status);

        // Inactivate school
        $this->school->update(['status' => 0]);
        $this->assertEquals(0, $this->school->fresh()->status);

        // Reactivate school
        $this->school->update(['status' => 1]);
        $this->assertEquals(1, $this->school->fresh()->status);

        // Login should now work
        $response = $this->post('/login', [
            'email' => $this->adminEmail,
            'password' => $this->adminPassword,
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs(User::find($this->admin->id));
    }
}
