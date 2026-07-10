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
 * Covers the before/after-content hooks (has_before_content/has_after_content):
 * the model-level scoping/view-naming that layouts/{admin,teacher,student}/layout.blade.php
 * relies on to include a plugin's before-content.blade.php/after-content.blade.php
 * on every page of a portal. See PluginContentHooksRenderingTest for the
 * actual end-to-end HTTP-level render.
 */
class PluginContentHooksTest extends TestCase
{
    use RefreshDatabase;

    private function makePlugin(array $overrides = []): Plugin
    {
        $requester = User::factory()->create();

        return Plugin::create(array_merge([
            'slug' => 'contenthook',
            'name' => 'Content Hook Plugin',
            'version' => '1.0.0',
            'source_type' => 'git',
            'source_ref' => 'https://example.com/repo.git',
            'composer_package' => 'gegok12/content-hook',
            'provider_class' => 'Gegok12\\ContentHook\\ContentHookServiceProvider',
            'portal' => 'admin',
            'status' => 'installed',
            'requested_by' => $requester->id,
        ], $overrides));
    }

    public function test_with_before_content_for_matches_installed_plugins_with_the_flag_and_portal()
    {
        $this->makePlugin(['slug' => 'a', 'has_before_content' => true, 'portal' => 'admin']);
        $this->makePlugin(['slug' => 'b', 'has_before_content' => false, 'portal' => 'admin']);
        $this->makePlugin(['slug' => 'c', 'has_before_content' => true, 'portal' => 'teacher']);
        $this->makePlugin(['slug' => 'd', 'has_before_content' => true, 'portal' => 'admin', 'status' => 'staged']);

        $this->assertSame(['a'], Plugin::withBeforeContentFor('admin')->pluck('slug')->all());
    }

    public function test_with_after_content_for_is_independent_of_before_content()
    {
        $this->makePlugin(['slug' => 'before-only', 'has_before_content' => true, 'has_after_content' => false]);
        $this->makePlugin(['slug' => 'after-only', 'has_before_content' => false, 'has_after_content' => true]);

        $this->assertSame(['before-only'], Plugin::withBeforeContentFor('admin')->pluck('slug')->all());
        $this->assertSame(['after-only'], Plugin::withAfterContentFor('admin')->pluck('slug')->all());
    }

    public function test_with_before_content_for_respects_multi_portal_plugins()
    {
        $this->makePlugin(['slug' => 'multi', 'has_before_content' => true, 'portal' => 'teacher,admin']);

        $this->assertSame(['multi'], Plugin::withBeforeContentFor('admin')->pluck('slug')->all());
        $this->assertSame(['multi'], Plugin::withBeforeContentFor('teacher')->pluck('slug')->all());
        $this->assertSame(0, Plugin::withBeforeContentFor('student')->count());
    }

    public function test_view_name_helpers_are_namespaced_by_portal_and_slug()
    {
        $plugin = $this->makePlugin(['slug' => 'contenthook']);

        $this->assertSame('plugins.contenthook.admin.before-content', $plugin->beforeContentViewName('admin'));
        $this->assertSame('plugins.contenthook.teacher.after-content', $plugin->afterContentViewName('teacher'));
    }

    public function test_profile_tab_scope_now_accepts_student_without_affecting_both()
    {
        $this->makePlugin(['slug' => 'student-only', 'has_profile_tab' => true, 'profile_tab_scope' => 'student']);
        $this->makePlugin(['slug' => 'both', 'has_profile_tab' => true, 'profile_tab_scope' => 'both']);
        $this->makePlugin(['slug' => 'teacher-only', 'has_profile_tab' => true, 'profile_tab_scope' => 'teacher']);

        $studentRows = Plugin::withProfileTabFor('student')->pluck('slug')->sort()->values()->all();
        $teacherRows = Plugin::withProfileTabFor('teacher')->pluck('slug')->sort()->values()->all();

        // 'both' still means teacher+staff only — it must NOT show up for 'student'.
        $this->assertSame(['student-only'], $studentRows);
        $this->assertSame(['both', 'teacher-only'], $teacherRows);
    }
}
