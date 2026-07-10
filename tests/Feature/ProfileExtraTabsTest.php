<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Livewire\Admin\ProfileExtraTabs;
use App\Models\Plugin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Covers the "Additional Info" panel rework: every installed plugin
 * declaring has_profile_tab=true for a given scope now renders as one row
 * inside a single panel (accordion-style, first row expanded by default),
 * instead of each plugin getting its own top-level tab — so the page's nav
 * stays at "native tabs + 1" no matter how many plugins hook in.
 */
class ProfileExtraTabsTest extends TestCase
{
    use RefreshDatabase;

    private function makePlugin(array $overrides = []): Plugin
    {
        $requester = User::factory()->create();

        return Plugin::create(array_merge([
            'slug' => 'extratab',
            'name' => 'Extra Tab Plugin',
            'version' => '1.0.0',
            'source_type' => 'git',
            'source_ref' => 'https://example.com/repo.git',
            'composer_package' => 'gegok12/extra-tab',
            'provider_class' => 'Gegok12\\ExtraTab\\ExtraTabServiceProvider',
            'portal' => 'admin',
            'status' => 'installed',
            'has_profile_tab' => true,
            'profile_tab_scope' => 'teacher',
            'profile_tab_label' => 'Extra Tab Plugin',
            'requested_by' => $requester->id,
        ], $overrides));
    }

    public function test_renders_nothing_when_no_plugin_matches_the_scope()
    {
        Livewire::test(ProfileExtraTabs::class, ['entityId' => 1, 'scope' => 'teacher'])
            ->assertDontSee('Additional Info');
    }

    public function test_shows_one_row_per_matching_plugin_sorted_alphabetically()
    {
        $this->makePlugin(['slug' => 'zzz', 'name' => 'Zzz Plugin', 'profile_tab_label' => 'Zzz Row']);
        $this->makePlugin(['slug' => 'aaa', 'name' => 'Aaa Plugin', 'profile_tab_label' => 'Aaa Row']);

        $component = Livewire::test(ProfileExtraTabs::class, ['entityId' => 1, 'scope' => 'teacher']);

        $component->assertSee('Additional Info')
            ->assertSeeInOrder(['Aaa Row', 'Zzz Row']);
    }

    public function test_only_the_first_row_is_expanded_by_default()
    {
        $this->makePlugin(['slug' => 'aaa', 'profile_tab_label' => 'Aaa Row']);
        $this->makePlugin(['slug' => 'bbb', 'profile_tab_label' => 'Bbb Row']);

        Livewire::test(ProfileExtraTabs::class, ['entityId' => 42, 'scope' => 'teacher'])
            ->assertSet('expandedSlug', 'aaa');
    }

    public function test_toggle_expands_and_collapses_a_row()
    {
        $this->makePlugin(['slug' => 'aaa']);
        $this->makePlugin(['slug' => 'bbb']);

        $component = Livewire::test(ProfileExtraTabs::class, ['entityId' => 1, 'scope' => 'teacher']);
        $component->assertSet('expandedSlug', 'aaa');

        $component->call('toggle', 'bbb')->assertSet('expandedSlug', 'bbb');
        $component->call('toggle', 'bbb')->assertSet('expandedSlug', null);
    }

    public function test_scope_filters_which_plugins_appear()
    {
        $this->makePlugin(['slug' => 'teacher-plugin', 'profile_tab_scope' => 'teacher', 'profile_tab_label' => 'Teacher Row']);
        $this->makePlugin(['slug' => 'student-plugin', 'profile_tab_scope' => 'student', 'profile_tab_label' => 'Student Row']);

        Livewire::test(ProfileExtraTabs::class, ['entityId' => 1, 'scope' => 'student'])
            ->assertSee('Student Row')
            ->assertDontSee('Teacher Row');
    }

    public function test_included_plugin_view_receives_the_entity_id()
    {
        $plugin = $this->makePlugin(['slug' => 'extratab']);

        // Publish a throwaway view so @includeIf actually finds something to render.
        $dir = resource_path('views/plugins/'.$plugin->slug);
        @mkdir($dir, 0755, true);
        file_put_contents($dir.'/profile-tab.blade.php', '<span>entity-{{ $entityId }}</span>');

        try {
            Livewire::test(ProfileExtraTabs::class, ['entityId' => 777, 'scope' => 'teacher'])
                ->assertSee('entity-777');
        } finally {
            @unlink($dir.'/profile-tab.blade.php');
            @rmdir($dir);
        }
    }
}
