<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Models\Plugin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Covers the PluginConsole/StagePluginInstall split: /plugins is now a
 * list-only page (PluginConsole), and staging a new install moved to its
 * own page at /plugins/stage (StagePluginInstall), which redirects back to
 * the list on success.
 */
class PluginConsoleSplitTest extends TestCase
{
    use RefreshDatabase;

    public function test_plugins_list_page_renders_and_links_to_the_stage_page()
    {
        $siteAdmin = User::factory()->siteAdmin()->create();

        $response = $this->actingAs($siteAdmin)->get('/plugins');

        $response->assertOk();
        $response->assertSee('Plugins');
        $response->assertSee('Stage a Plugin');
        $response->assertSee('/plugins/stage', false);
        $response->assertDontSee('Stage a New Plugin Install');
    }

    public function test_stage_page_renders_the_form()
    {
        $siteAdmin = User::factory()->siteAdmin()->create();

        $response = $this->actingAs($siteAdmin)->get('/plugins/stage');

        $response->assertOk();
        $response->assertSee('Stage a New Plugin Install');
        $response->assertSee('Back to Plugins');
    }

    public function test_staging_a_plugin_redirects_to_the_list_and_creates_it()
    {
        $siteAdmin = User::factory()->siteAdmin()->create();

        Livewire::actingAs($siteAdmin)
            ->test('site-admin.stage-plugin-install')
            ->set('source_type', 'git')
            ->set('slug', 'newlyStaged')
            ->set('name', 'Newly Staged')
            ->set('version', '1.0.0')
            ->set('composer_package', 'gegok12/newly-staged')
            ->set('provider_class', 'Gegok12\\NewlyStaged\\NewlyStagedServiceProvider')
            ->set('portals', ['admin'])
            ->set('git_url', 'https://example.com/newly-staged.git')
            ->call('stageInstall')
            ->assertRedirect('/plugins');

        $this->assertDatabaseHas('plugins', [
            'slug' => 'newlyStaged',
            'status' => 'staged',
        ]);
    }

    public function test_plugins_list_shows_a_staged_plugin()
    {
        $siteAdmin = User::factory()->siteAdmin()->create();

        Plugin::create([
            'slug' => 'existing',
            'name' => 'Existing Plugin',
            'version' => '1.0.0',
            'source_type' => 'git',
            'source_ref' => 'https://example.com/existing.git',
            'composer_package' => 'gegok12/existing',
            'provider_class' => 'Gegok12\\Existing\\ExistingServiceProvider',
            'portal' => 'admin',
            'status' => 'staged',
            'requested_by' => $siteAdmin->id,
        ]);

        $response = $this->actingAs($siteAdmin)->get('/plugins');

        $response->assertOk();
        $response->assertSee('existing');
        $response->assertSee('Existing Plugin');
    }
}
