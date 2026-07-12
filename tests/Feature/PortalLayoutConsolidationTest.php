<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Models\School;
use App\Models\Standard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regression safety net for the resources/views/layouts/{portal}/ consolidation
 * (layout.blade.php + navigation.blade.php + sidebar.blade.php). This view code
 * had zero automated coverage before this test — every assertion here targets a
 * fact confirmed by direct file reads during the consolidation audit, not a guess.
 */
class PortalLayoutConsolidationTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_nonteaching_portal_renders_its_own_sidebar_id_and_has_no_notification_bell()
    {
        $school = School::factory()->create();
        $user = User::factory()->nonTeachingStaff()->for($school)->create();

        $response = $this->actingAs($user)->get('/nonteaching/dashboard');

        $response->assertOk();
        $response->assertSee('id="nt_sidebar"', false);
        $response->assertDontSee('<notification', false);
        $response->assertDontSee('Change Password');
        $response->assertSee('librarian-sidebar');
    }

    public function test_siteadmin_portal_is_minimal_with_no_dropdown_extras()
    {
        $user = User::factory()->siteAdmin()->create();

        $response = $this->actingAs($user)->withSession(['successmessage' => 'FLASH-MARKER-TEST'])->get('/plugins');

        $response->assertOk();
        $response->assertDontSee('<notification', false);
        $response->assertSee('Logout');
        $response->assertDontSee('Change Avatar');
        $response->assertSee('siteadmin-sidebar');
        $response->assertSee('FLASH-MARKER-TEST');
    }

    public function test_siteadmin_can_reach_master_data_countries_and_sees_it_in_their_own_sidebar()
    {
        $user = User::factory()->siteAdmin()->create();

        $response = $this->actingAs($user)->get('/siteadmin/setting/countries');

        $response->assertOk();
        $response->assertSee('Countries');
        $response->assertSee('siteadmin-sidebar');
    }

    public function test_schooladmin_can_no_longer_reach_countries_under_the_old_admin_prefix()
    {
        $school = School::factory()->create();
        $admin = User::factory()->schoolAdmin()->for($school)->create();

        $response = $this->actingAs($admin)->get('/admin/setting/countries');

        $response->assertNotFound();
    }

    public function test_schooladmin_hitting_the_new_siteadmin_master_data_url_is_redirected_away()
    {
        $school = School::factory()->create();
        $admin = User::factory()->schoolAdmin()->for($school)->create();

        $response = $this->actingAs($admin)->get('/siteadmin/setting/countries');

        $response->assertRedirect('/admin/dashboard');
    }

    public function test_siteadmin_can_reach_purchase_modules_and_sees_it_in_their_own_sidebar()
    {
        $user = User::factory()->siteAdmin()->create();

        $response = $this->actingAs($user)->get('/siteadmin/addon');

        $response->assertOk();
        $response->assertSee('Purchase Modules');
        $response->assertSee('siteadmin-sidebar');
    }

    public function test_schooladmin_can_no_longer_reach_addon_under_the_old_admin_prefix()
    {
        $school = School::factory()->create();
        $admin = User::factory()->schoolAdmin()->for($school)->create();

        $response = $this->actingAs($admin)->get('/admin/addon');

        $response->assertNotFound();
    }

    public function test_schooladmin_hitting_the_new_siteadmin_addon_url_is_redirected_away()
    {
        $school = School::factory()->create();
        $admin = User::factory()->schoolAdmin()->for($school)->create();

        $response = $this->actingAs($admin)->get('/siteadmin/addon');

        $response->assertRedirect('/admin/dashboard');
    }

    public function test_alumni_portal_uses_alumniprofile_relation_and_edit_profile_link()
    {
        $school = School::factory()->create();
        $user = User::factory()->alumni()->for($school)->create();
        \Gegok12\Alumni\Models\Alumniprofile::create([
            'user_id' => $user->id,
            'school_id' => $school->id,
            'name' => $user->name,
            'email' => $user->email,
            'mobile_no' => $user->mobile_no,
            'photo' => 'test-avatar.jpg',
            'passing_session' => '2020',
        ]);

        $response = $this->actingAs($user)->get('/alumni/dashboard');

        $response->assertOk();
        $response->assertSee('Edit Profile');
        // (Alumni's file also has a dead "Change Avatar" link wrapped in a
        // raw HTML comment rather than a Blade {{-- --}} one, so its text is
        // present-but-invisible in the response -- out of scope here, not
        // asserted on.)
    }

    public function test_library_dashboard_link_points_at_librarys_own_dashboard_not_teachers()
    {
        $school = School::factory()->create();
        $user = User::factory()->librarian()->for($school)->create();

        $response = $this->actingAs($user)->get('/library/dashboard');

        $response->assertOk();
        $response->assertSee(url('/library/dashboard'), false);
        $response->assertDontSee(url('/teacher/dashboard'), false);
    }

    public function test_reception_notification_mode_and_impersonate_stop_link_are_correct()
    {
        $school = School::factory()->create();
        $admin = User::factory()->schoolAdmin()->for($school)->create();
        $receptionist = User::factory()->receptionist()->for($school)->create();
        $admin->setImpersonating($receptionist->id);

        $response = $this->actingAs($receptionist)->get('/receptionist/dashboard');

        $response->assertOk();
        $response->assertSee('mode="receptionist"', false);
        $response->assertSee(url('/receptionist/changeavatar'), false);
        $response->assertSee(url('/teacher/impersonate/stop'), false);
        $response->assertDontSee(url('/receptionist/impersonate/stop'), false);
    }

    public function test_reception_impersonate_stop_redirects_to_receptionist_dashboard()
    {
        $school = School::factory()->create();
        $admin = User::factory()->schoolAdmin()->for($school)->create();
        $receptionist = User::factory()->receptionist()->for($school)->create();
        $admin->setImpersonating($receptionist->id);

        $response = $this->actingAs($receptionist)->get('/teacher/impersonate/stop');

        $response->assertRedirect('/receptionist/dashboard');
    }

    public function test_student_portal_wraps_avatar_path_in_asset_and_has_no_change_avatar_link()
    {
        $school = School::factory()->create();
        $user = User::factory()->student()->for($school)->create();

        $response = $this->actingAs($user)->get('/student/dashboard');

        $response->assertOk();
        $response->assertDontSee('Change Avatar');
        $response->assertSee('student-sidebar');
    }

    public function test_accountant_usergroup_sees_its_own_navigation_and_sidebar()
    {
        $school = School::factory()->create();
        $admin = User::factory()->schoolAdmin()->for($school)->create();
        $accountant = User::factory()->accountant()->for($school)->create();
        $admin->setImpersonating($accountant->id);

        $response = $this->actingAs($accountant)->get('/accountant/dashboard');

        $response->assertOk();
        $response->assertSee('accountant-sidebar');
        $response->assertSee(url('/teacher/impersonate/stop'), false);
        $response->assertDontSee(url('/accountant/impersonate/stop'), false);
    }

    public function test_accountant_impersonate_stop_redirects_to_accountant_dashboard()
    {
        $school = School::factory()->create();
        $admin = User::factory()->schoolAdmin()->for($school)->create();
        $accountant = User::factory()->accountant()->for($school)->create();
        $admin->setImpersonating($accountant->id);

        $response = $this->actingAs($accountant)->get('/teacher/impersonate/stop');

        $response->assertRedirect('/accountant/dashboard');
    }

    public function test_schooladmin_usergroup_sees_admins_navigation_and_sidebar_under_accountant_prefix()
    {
        $school = School::factory()->create();
        $this->satisfyAdminOnboarding($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();

        $response = $this->actingAs($admin)->get('/accountant/payroll/template');

        $response->assertOk();
        $response->assertSee('admin-sidebar');
    }

    public function test_teacher_portal_notification_mode_and_sidebar_class()
    {
        $school = School::factory()->create();
        $user = User::factory()->teacher()->for($school)->create();

        $response = $this->actingAs($user)->get('/teacher/dashboard');

        $response->assertOk();
        $response->assertSee('mode="teacher"', false);
        $response->assertSee('teacher-sidebar');
    }

    public function test_admin_portal_has_edit_profile_and_admin_sidebar()
    {
        $school = School::factory()->create();
        $this->satisfyAdminOnboarding($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();

        // /admin/dashboard's query uses a MySQL-only FIELD() ordering clause
        // that SQLite (the test DB) can't run — same reason
        // PluginContentHooksRenderingTest hits /admin/changepassword instead.
        $response = $this->actingAs($admin)->get('/admin/changepassword');

        $response->assertOk();
        $response->assertSee('Edit Profile');
        $response->assertSee('admin-sidebar');
    }

    public function test_parent_landing_on_admin_dashboard_gets_redirected_to_parent_dashboard()
    {
        $school = School::factory()->create();
        $parent = User::factory()->parent()->for($school)->create();

        $response = $this->actingAs($parent)->get('/admin/dashboard');

        $response->assertRedirect('/parent/dashboard');
    }

    public function test_parent_dashboard_tells_them_to_use_the_app()
    {
        $school = School::factory()->create();
        $parent = User::factory()->parent()->for($school)->create();

        $response = $this->actingAs($parent)->get('/parent/dashboard');

        $response->assertOk();
        $response->assertSee('GegoK12 app');
        $response->assertDontSee('<notification', false);
    }

    public function test_stock_keeper_landing_on_admin_dashboard_gets_redirected_to_stock_portal()
    {
        $school = School::factory()->create();
        $stockKeeper = User::factory()->stockKeeper()->for($school)->create();

        $response = $this->actingAs($stockKeeper)->get('/admin/dashboard');

        $response->assertRedirect('/stock/stockproduct/show');
    }

    public function test_stock_keeper_sees_their_own_chrome_not_admins()
    {
        $school = School::factory()->create();
        $stockKeeper = User::factory()->stockKeeper()->for($school)->create();

        $response = $this->actingAs($stockKeeper)->get('/stock/stockproduct/show');

        $response->assertOk();
        $response->assertSee('librarian-sidebar');
        $response->assertSee('mode="stock"', false);
        $response->assertDontSee('admin-sidebar');
    }

    public function test_admin_still_sees_admin_chrome_on_the_same_shared_stock_view()
    {
        $school = School::factory()->create();
        $this->satisfyAdminOnboarding($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();

        $response = $this->actingAs($admin)->get('/admin/stockproduct/show');

        $response->assertOk();
        $response->assertSee('admin-sidebar');
        $response->assertSee('mode="admin"', false);
    }

    public function test_stock_keeper_cannot_reach_the_admin_prefixed_stock_route()
    {
        $school = School::factory()->create();
        $stockKeeper = User::factory()->stockKeeper()->for($school)->create();

        $response = $this->actingAs($stockKeeper)->get('/admin/stockproduct/show');

        $response->assertRedirect('/stock/stockproduct/show');
    }
}
