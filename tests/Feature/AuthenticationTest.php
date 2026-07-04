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
 * AuthenticationTest
 *
 * Tests core authentication functionality for all user roles:
 * - School Admin login
 * - School Librarian login
 * - Student login
 * - Teacher login
 * - Accountant login
 * - Exit status user cannot login
 * - Invalid credentials handling
 * - Non-existent user handling
 *
 * All tests use RefreshDatabase to ensure clean database state between tests.
 * Users are created with active schools and active status in userprofile.
 */
class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup test fixtures
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Seed or create necessary base data
    }

    /**
     * Test School Admin Can Login
     */
    public function test_school_admin_can_login(): void
    {
        // Create a school
        $school = School::factory()->create();

        // Create a school admin user
        $schoolAdmin = User::factory()
            ->schoolAdmin()
            ->for($school)
            ->create([
                'email' => 'admin@school.com',
                'password' => bcrypt('password123'),
            ]);

        // Attempt login
        $response = $this->post('/login', [
            'email' => 'admin@school.com',
            'password' => 'password123',
        ]);

        // Assert successful login
        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($schoolAdmin);
    }

    /**
     * Test School Librarian Can Login
     */
    public function test_school_librarian_can_login(): void
    {
        // Create a school
        $school = School::factory()->create();

        // Create a librarian user
        $librarian = User::factory()
            ->librarian()
            ->for($school)
            ->create([
                'email' => 'librarian@school.com',
                'password' => bcrypt('password123'),
            ]);

        // Attempt login
        $response = $this->post('/login', [
            'email' => 'librarian@school.com',
            'password' => 'password123',
        ]);

        // Assert successful login
        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($librarian);
    }

    /**
     * Test Student Can Login
     */
    public function test_student_can_login(): void
    {
        // Create a school
        $school = School::factory()->create();

        // Create a student user
        $student = User::factory()
            ->student()
            ->for($school)
            ->create([
                'email' => 'student@school.com',
                'password' => bcrypt('password123'),
            ]);

        // Attempt login
        $response = $this->post('/login', [
            'email' => 'student@school.com',
            'password' => 'password123',
        ]);

        // Assert successful login
        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($student);
    }

    /**
     * Test Teacher Can Login
     */
    public function test_teacher_can_login(): void
    {
        // Create a school
        $school = School::factory()->create();

        // Create a teacher user
        $teacher = User::factory()
            ->teacher()
            ->for($school)
            ->create([
                'email' => 'teacher@school.com',
                'password' => bcrypt('password123'),
            ]);

        // Attempt login
        $response = $this->post('/login', [
            'email' => 'teacher@school.com',
            'password' => 'password123',
        ]);

        // Assert successful login
        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($teacher);
    }

    /**
     * Test Accountant Can Login
     */
    public function test_accountant_can_login(): void
    {
        // Create a school
        $school = School::factory()->create();

        // Create an accountant user
        $accountant = User::factory()
            ->accountant()
            ->for($school)
            ->create([
                'email' => 'accountant@school.com',
                'password' => bcrypt('password123'),
            ]);

        // Attempt login
        $response = $this->post('/login', [
            'email' => 'accountant@school.com',
            'password' => 'password123',
        ]);

        // Assert successful login
        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($accountant);
    }

    /**
     * Test Non-Teaching Staff (driver/helper/peon/etc., usergroup 13) can
     * log in and land on a real working dashboard, not the 404 that used
     * to follow a successful login for this role.
     */
    public function test_non_teaching_staff_can_login_and_reach_dashboard(): void
    {
        $school = School::factory()->create();

        $driver = User::factory()
            ->nonTeachingStaff()
            ->for($school)
            ->create([
                'email' => 'driver@school.com',
                'password' => bcrypt('password123'),
            ]);

        Userprofile::create([
            'user_id' => $driver->id,
            'school_id' => $school->id,
            'usergroup_id' => $driver->usergroup_id,
            'firstname' => 'School',
            'lastname' => 'Driver',
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'email' => 'driver@school.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($driver);

        // Login always lands on /admin/dashboard first; the schooladmin
        // middleware then routes non-admin roles to their own dashboard.
        $dashboardResponse = $this->get('/admin/dashboard');
        $dashboardResponse->assertRedirect('/nonteaching/dashboard');

        $nonTeachingResponse = $this->get('/nonteaching/dashboard');
        $nonTeachingResponse->assertOk();
        $nonTeachingResponse->assertSee('Notice Board');
    }

    /**
     * Test Teacher with exit status cannot login
     * (She no longer works in school, login should be denied)
     */
    public function test_teacher_with_exit_status_cannot_login(): void
    {
        // Create a school
        $school = School::factory()->create();

        // Create a teacher
        $exitTeacher = User::factory()
            ->teacher()
            ->for($school)
            ->create([
                'email' => 'exit.teacher@school.com',
                'password' => bcrypt('password123'),
            ]);

        // Create userprofile with exit status (no longer works)
        Userprofile::create([
            'user_id' => $exitTeacher->id,
            'school_id' => $school->id,
            'usergroup_id' => $exitTeacher->usergroup_id,
            'firstname' => 'Exit',
            'lastname' => 'Teacher',
            'status' => 'exit',
        ]);

        // Attempt login
        $response = $this->post('/login', [
            'email' => 'exit.teacher@school.com',
            'password' => 'password123',
        ]);

        // Assert login fails because user has exit status
        $response->assertSessionHasErrors();
        $this->assertGuest();

        // Verify userprofile status is exit
        $this->assertEquals('exit', $exitTeacher->fresh()->userprofile->status);
    }

    /**
     * Test login with invalid credentials
     */
    public function test_login_fails_with_invalid_credentials(): void
    {
        $school = School::factory()->create();

        User::factory()
            ->schoolAdmin()
            ->for($school)
            ->create([
                'email' => 'admin@school.com',
                'password' => bcrypt('password123'),
            ]);

        $response = $this->post('/login', [
            'email' => 'admin@school.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    /**
     * Test login fails with non-existent user
     */
    public function test_login_fails_with_non_existent_user(): void
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@school.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }
}
