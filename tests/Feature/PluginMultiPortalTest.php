<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Models\Plugin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Covers the multi-portal extension to the Plugin model: `portal` moved
 * from a single value to a comma-separated list, and the menu/dashboard-
 * widget/tools-menu scopes had to switch from a plain equality check to a
 * delimiter-aware LIKE match — an easy place to introduce a false positive
 * (e.g. a search for "admin" matching a plugin whose portal is
 * "superadmin") or a false negative (missing a match at the very start or
 * end of the list).
 */
class PluginMultiPortalTest extends TestCase
{
    use RefreshDatabase;

    private function makePlugin(array $overrides = []): Plugin
    {
        $requester = User::factory()->create();

        return Plugin::create(array_merge([
            'slug' => 'workpermission',
            'name' => 'Work Permission',
            'version' => '1.0.0',
            'source_type' => 'git',
            'source_ref' => 'https://example.com/repo.git',
            'composer_package' => 'gegok12/work-permission',
            'provider_class' => 'Gegok12\\WorkPermission\\WorkPermissionServiceProvider',
            'portal' => 'teacher,admin',
            'has_menu' => true,
            'has_dashboard_widget' => true,
            'has_tools_menu' => true,
            'status' => 'installed',
            'requested_by' => $requester->id,
        ], $overrides));
    }

    public function test_portals_array_splits_and_trims_the_comma_list()
    {
        $plugin = $this->makePlugin(['portal' => 'teacher, admin']);

        $this->assertSame(['teacher', 'admin'], $plugin->portalsArray());
    }

    public function test_portals_array_handles_a_single_portal()
    {
        $plugin = $this->makePlugin(['portal' => 'teacher']);

        $this->assertSame(['teacher'], $plugin->portalsArray());
    }

    public function test_has_portal_matches_any_portal_in_the_list()
    {
        $plugin = $this->makePlugin(['portal' => 'teacher,admin']);

        $this->assertTrue($plugin->hasPortal('teacher'));
        $this->assertTrue($plugin->hasPortal('admin'));
        $this->assertFalse($plugin->hasPortal('student'));
    }

    public function test_with_menu_for_matches_a_portal_at_the_start_middle_and_end_of_the_list()
    {
        $this->makePlugin(['slug' => 'start', 'portal' => 'teacher,admin,web']);
        $this->makePlugin(['slug' => 'middle', 'portal' => 'web,teacher,admin']);
        $this->makePlugin(['slug' => 'end', 'portal' => 'web,admin,teacher']);
        $this->makePlugin(['slug' => 'only', 'portal' => 'teacher']);

        $slugs = Plugin::withMenuFor('teacher')->pluck('slug')->sort()->values()->all();

        $this->assertSame(['end', 'middle', 'only', 'start'], $slugs);
    }

    public function test_with_menu_for_does_not_false_positive_on_a_portal_name_that_is_a_substring()
    {
        // A naive LIKE '%admin%' would wrongly match "superadmin" when
        // searching for "admin" — the delimiter-aware match must not.
        $this->makePlugin(['slug' => 'decoy', 'portal' => 'superadmin']);
        $this->makePlugin(['slug' => 'real', 'portal' => 'admin']);

        $slugs = Plugin::withMenuFor('admin')->pluck('slug')->all();

        $this->assertSame(['real'], $slugs);
    }

    public function test_with_menu_for_excludes_plugins_missing_the_requested_portal()
    {
        $this->makePlugin(['slug' => 'wp', 'portal' => 'teacher,admin']);

        $this->assertSame(0, Plugin::withMenuFor('student')->count());
    }

    public function test_with_menu_for_excludes_uninstalled_or_menu_less_plugins()
    {
        $this->makePlugin(['slug' => 'staged', 'portal' => 'teacher', 'status' => 'staged']);
        $this->makePlugin(['slug' => 'no-menu', 'portal' => 'teacher', 'has_menu' => false]);
        $this->makePlugin(['slug' => 'live', 'portal' => 'teacher']);

        $this->assertSame(['live'], Plugin::withMenuFor('teacher')->pluck('slug')->all());
    }

    public function test_with_dashboard_widget_for_and_with_tools_menu_for_are_independent_of_with_menu_for()
    {
        $this->makePlugin([
            'slug' => 'wp',
            'portal' => 'teacher,admin',
            'has_menu' => false,
            'has_dashboard_widget' => true,
            'has_tools_menu' => true,
        ]);

        $this->assertSame(0, Plugin::withMenuFor('teacher')->count());
        $this->assertSame(['wp'], Plugin::withDashboardWidgetFor('teacher')->pluck('slug')->all());
        $this->assertSame(['wp'], Plugin::withToolsMenuFor('admin')->pluck('slug')->all());
    }

    public function test_view_name_helpers_are_namespaced_by_portal_and_slug()
    {
        $plugin = $this->makePlugin(['slug' => 'workpermission']);

        $this->assertSame('plugins.workpermission.teacher.menu', $plugin->menuViewName('teacher'));
        $this->assertSame('plugins.workpermission.admin.menu', $plugin->menuViewName('admin'));
        $this->assertSame('plugins.workpermission.teacher.dashboard-widget', $plugin->dashboardWidgetViewName('teacher'));
        $this->assertSame('plugins.workpermission.tools-menu', $plugin->toolsMenuViewName());
    }
}
