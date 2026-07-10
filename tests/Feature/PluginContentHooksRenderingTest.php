<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Models\Plugin;
use App\Models\School;
use App\Models\Standard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * End-to-end proof that layouts/admin/layout.blade.php actually renders the
 * before/after-content hooks — PluginContentHooksTest only covers the model
 * scoping in isolation. Writes a throwaway view into the real
 * resources/views/plugins/ directory (mirroring what a plugin's own
 * vendor:publish would do) and hits a real admin page to confirm it's
 * actually included, in the right place, only for the right portal.
 */
class PluginContentHooksRenderingTest extends TestCase
{
    use RefreshDatabase;

    private string $viewDir;

    protected function tearDown(): void
    {
        if (isset($this->viewDir) && is_dir($this->viewDir)) {
            array_map('unlink', glob($this->viewDir.'/*'));
            rmdir($this->viewDir);
            @rmdir(dirname($this->viewDir)); // the plugins/{slug} parent, if now empty
        }

        parent::tearDown();
    }

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

    private function publishHookViews(string $slug): void
    {
        $this->viewDir = resource_path('views/plugins/'.$slug.'/admin');
        mkdir($this->viewDir, 0755, true);
        file_put_contents($this->viewDir.'/before-content.blade.php', '<div id="e2e-before-content-marker">BEFORE-MARKER</div>');
        file_put_contents($this->viewDir.'/after-content.blade.php', '<div id="e2e-after-content-marker">AFTER-MARKER</div>');
    }

    private function makePlugin(array $overrides = []): Plugin
    {
        $requester = User::factory()->create();

        return Plugin::create(array_merge([
            'slug' => 'e2econtenthook',
            'name' => 'E2E Content Hook',
            'version' => '1.0.0',
            'source_type' => 'git',
            'source_ref' => 'https://example.com/repo.git',
            'composer_package' => 'gegok12/e2e-content-hook',
            'provider_class' => 'Gegok12\\E2eContentHook\\E2eContentHookServiceProvider',
            'portal' => 'admin',
            'status' => 'installed',
            'requested_by' => $requester->id,
        ], $overrides));
    }

    public function test_before_and_after_content_render_on_a_real_admin_page_around_its_own_content()
    {
        $this->publishHookViews('e2econtenthook');
        $this->makePlugin(['has_before_content' => true, 'has_after_content' => true]);

        $school = School::factory()->create();
        $this->satisfyAdminOnboarding($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();

        $response = $this->actingAs($admin)->get('/admin/changepassword');

        $response->assertOk();
        $response->assertSeeInOrder(['BEFORE-MARKER', '<change-password', 'AFTER-MARKER'], false);
    }

    public function test_content_hooks_do_not_render_for_a_portal_the_plugin_does_not_target()
    {
        $this->publishHookViews('e2econtenthook');
        $this->makePlugin(['has_before_content' => true, 'has_after_content' => true, 'portal' => 'teacher']);

        $school = School::factory()->create();
        $this->satisfyAdminOnboarding($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();

        $response = $this->actingAs($admin)->get('/admin/changepassword');

        $response->assertOk();
        $response->assertDontSee('BEFORE-MARKER');
        $response->assertDontSee('AFTER-MARKER');
    }

    public function test_content_hooks_do_not_render_when_the_plugin_is_not_installed()
    {
        $this->publishHookViews('e2econtenthook');
        $this->makePlugin(['has_before_content' => true, 'has_after_content' => true, 'status' => 'staged']);

        $school = School::factory()->create();
        $this->satisfyAdminOnboarding($school);
        $admin = User::factory()->schoolAdmin()->for($school)->create();

        $response = $this->actingAs($admin)->get('/admin/changepassword');

        $response->assertOk();
        $response->assertDontSee('BEFORE-MARKER');
        $response->assertDontSee('AFTER-MARKER');
    }
}
