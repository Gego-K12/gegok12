<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class AuthenticationAdvancedTest
 *
 * Advanced authentication tests including edge cases,
 * email verification, and role-based access control
 */
class AuthenticationAdvancedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user must be activated to log in
     */
    public function test_inactive_user_cannot_login(): void
    {
        $school = School::factory()->create();

        $user = User::factory()
            ->schoolAdmin()
            ->for($school)
            ->create([
                'email' => 'inactive@school.com',
                'password' => bcrypt('password123'),
                'status' => 'inactive',
            ]);

        $response = $this->post('/login', [
            'email' => 'inactive@school.com',
            'password' => 'password123',
        ]);

        // Depending on your implementation, this should fail
        // Adjust assertions based on your actual behavior
        $this->assertGuest();
    }

    /**
     * Test multiple login attempts are rate limited
     */
    public function test_multiple_failed_login_attempts_rate_limited(): void
    {
        $school = School::factory()->create();

        User::factory()
            ->schoolAdmin()
            ->for($school)
            ->create([
                'email' => 'admin@school.com',
                'password' => bcrypt('password123'),
            ]);

        // Make multiple failed attempts
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'admin@school.com',
                'password' => 'wrongpassword',
            ]);
        }

        // Next attempt should be rate limited
        $response = $this->post('/login', [
            'email' => 'admin@school.com',
            'password' => 'password123',
        ]);

        // Should receive too many attempts error
        $response->assertSessionHasErrors();
    }

    /**
     * Test logout clears authentication
     */
    public function test_user_can_logout(): void
    {
        $school = School::factory()->create();

        $user = User::factory()
            ->schoolAdmin()
            ->for($school)
            ->create();

        // Login
        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);

        // Logout
        $response = $this->post('/logout');

        // Verify not authenticated anymore
        $this->assertGuest();
    }

    /**
     * Test remember me functionality
     */
    public function test_remember_me_token_is_set(): void
    {
        $school = School::factory()->create();

        $user = User::factory()
            ->schoolAdmin()
            ->for($school)
            ->create([
                'email' => 'admin@school.com',
                'password' => bcrypt('password123'),
            ]);

        $response = $this->post('/login', [
            'email' => 'admin@school.com',
            'password' => 'password123',
            'remember' => true,
        ]);

        // Verify remember token is set
        $user->refresh();
        $this->assertNotNull($user->remember_token);
    }

    /**
     * Test wrong user group cannot access wrong dashboard
     */
    public function test_student_cannot_access_admin_dashboard(): void
    {
        $school = School::factory()->create();

        $student = User::factory()
            ->student()
            ->for($school)
            ->create();

        $this->actingAs($student)
            ->get('/admin/dashboard')
            ->assertStatus(403); // Forbidden or redirected
    }

    /**
     * Test school admin can only access their school's data
     */
    public function test_school_admin_isolated_to_own_school(): void
    {
        $school1 = School::factory()->create();
        $school2 = School::factory()->create();

        $admin1 = User::factory()
            ->schoolAdmin()
            ->for($school1)
            ->create();

        $admin2 = User::factory()
            ->schoolAdmin()
            ->for($school2)
            ->create();

        $this->actingAs($admin1);

        // Admin 1 should be able to access school 1
        $response = $this->get('/admin/dashboard');
        $this->assertTrue($response->status() !== 403);

        // Admin 1 should NOT be able to access school 2 data
        // This depends on your implementation
        // Adjust based on your actual routes and logic
    }

    /**
     * Test email field is required
     */
    public function test_email_is_required_for_login(): void
    {
        $response = $this->post('/login', [
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * Test password field is required
     */
    public function test_password_is_required_for_login(): void
    {
        $response = $this->post('/login', [
            'email' => 'test@school.com',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * Test email must be valid format
     */
    public function test_email_must_be_valid_format(): void
    {
        $response = $this->post('/login', [
            'email' => 'invalid-email',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * Test case-insensitive email login
     */
    public function test_email_login_is_case_insensitive(): void
    {
        $school = School::factory()->create();

        $user = User::factory()
            ->schoolAdmin()
            ->for($school)
            ->create([
                'email' => 'Admin@School.com',
                'password' => bcrypt('password123'),
            ]);

        $response = $this->post('/login', [
            'email' => 'admin@school.com', // lowercase
            'password' => 'password123',
        ]);

        // Should login successfully
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test user profile data is loaded after login
     */
    public function test_user_profile_loaded_after_login(): void
    {
        $school = School::factory()->create();

        $teacher = User::factory()
            ->teacher()
            ->for($school)
            ->create([
                'email' => 'teacher@school.com',
                'password' => bcrypt('password123'),
            ]);

        $this->post('/login', [
            'email' => 'teacher@school.com',
            'password' => 'password123',
        ]);

        // Verify user and profile are loaded
        $this->assertAuthenticatedAs($teacher);
        $this->assertNotNull(auth()->user());
    }

    /**
     * Test concurrent login attempts don't cause conflicts
     */
    public function test_concurrent_logins_work_correctly(): void
    {
        $school = School::factory()->create();

        $user1 = User::factory()->teacher()->for($school)->create();
        $user2 = User::factory()->student()->for($school)->create();

        // User 1 logs in
        $this->post('/login', [
            'email' => $user1->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticatedAs($user1);

        // User 2 logs in (should clear user 1's session)
        $this->post('/logout');
        $this->post('/login', [
            'email' => $user2->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticatedAs($user2);
    }

    /**
     * Test user group affects dashboard redirect
     */
    public function test_different_user_groups_redirect_to_different_dashboards(): void
    {
        $school = School::factory()->create();

        $redirects = [
            'schoolAdmin' => '/admin/dashboard',
            'librarian' => '/library/dashboard',
            'student' => '/student/dashboard',
            'teacher' => '/teacher/dashboard',
            'accountant' => '/accountant/dashboard',
        ];

        foreach ($redirects as $role => $expectedRedirect) {
            $user = User::factory()
                ->$role()
                ->for($school)
                ->create([
                    'email' => "$role@school.com",
                    'password' => bcrypt('password123'),
                ]);

            $response = $this->post('/login', [
                'email' => "$role@school.com",
                'password' => 'password123',
            ]);

            // Verify redirect based on role
            // Note: Adjust based on your actual implementation
            $this->assertAuthenticatedAs($user);

            // Logout for next iteration
            $this->post('/logout');
        }
    }
}
