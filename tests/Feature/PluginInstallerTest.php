<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Models\Plugin;
use App\Models\User;
use App\Services\PluginInstaller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\FakeProcessRunner;
use Tests\TestCase;
use ZipArchive;

/**
 * Exercises PluginInstaller::install()/uninstall() end-to-end. Made possible
 * by two seams added alongside this test: a ProcessRunner interface (fakes
 * composer/npm/artisan instead of shelling out for real) and overridable
 * base/storage/resource paths (redirects every filesystem write — composer.json,
 * custompackages/, routes/*.php, custom_addon.js — into a throwaway temp tree
 * instead of mutating this project's real source files).
 */
class PluginInstallerTest extends TestCase
{
    use RefreshDatabase;

    private string $basePath;

    private string $storagePath;

    private string $resourcePath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->basePath = $this->makeTempDir('plugin-installer-base');
        $this->storagePath = $this->makeTempDir('plugin-installer-storage');
        $this->resourcePath = $this->makeTempDir('plugin-installer-resource');

        file_put_contents($this->basePath.'/composer.json', json_encode(['require' => (object) []]));
        mkdir($this->basePath.'/routes', 0755, true);
        file_put_contents($this->basePath.'/routes/teacher.php', "<?php\n");

        mkdir($this->resourcePath.'/assets/js', 0755, true);
        file_put_contents(
            $this->resourcePath.'/assets/js/custom_addon.js',
            "export default function registerCustomAddon(app) {\n}\n"
        );
    }

    protected function tearDown(): void
    {
        $this->deleteTempDir($this->basePath);
        $this->deleteTempDir($this->storagePath);
        $this->deleteTempDir($this->resourcePath);

        parent::tearDown();
    }

    private function makeTempDir(string $prefix): string
    {
        $dir = sys_get_temp_dir().'/'.$prefix.'-'.uniqid();
        mkdir($dir, 0755, true);

        return $dir;
    }

    private function deleteTempDir(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }

        foreach (new \FilesystemIterator($dir) as $item) {
            $item->isDir() ? $this->deleteTempDir($item->getPathname()) : unlink($item->getPathname());
        }

        rmdir($dir);
    }

    private function makeInstaller(FakeProcessRunner $runner): PluginInstaller
    {
        return new PluginInstaller($runner, $this->basePath, $this->storagePath, $this->resourcePath);
    }

    private function makePlugin(array $overrides = []): Plugin
    {
        $requester = User::factory()->create();

        return Plugin::create(array_merge([
            'slug' => 'demoplugin',
            'name' => 'Demo Plugin',
            'version' => '1.0.0',
            'source_type' => 'zip',
            'source_ref' => 'plugin-uploads/demoplugin.zip',
            'composer_package' => 'acme/demoplugin',
            'provider_class' => 'Acme\\DemoPlugin\\DemoPluginServiceProvider',
            'portal' => 'teacher',
            'status' => 'staged',
            'requested_by' => $requester->id,
        ], $overrides));
    }

    /**
     * Builds a valid plugin zip (plugin.json at the archive root) and drops
     * it wherever prepareZipSource() expects to find the staged upload:
     * storagePath('app/'.source_ref).
     */
    private function putPluginZipFixture(Plugin $plugin, array $manifestOverrides = []): void
    {
        $manifest = array_merge([
            'slug' => $plugin->slug,
            'name' => $plugin->name,
            'version' => $plugin->version,
            'vendor' => 'acme',
            'composer_package' => $plugin->composer_package,
            'provider_class' => $plugin->provider_class,
            'portal' => $plugin->portal,
        ], $manifestOverrides);

        $zipPath = $this->storagePath.'/app/'.$plugin->source_ref;
        mkdir(dirname($zipPath), 0755, true);

        $zip = new ZipArchive;
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFromString('plugin.json', json_encode($manifest));
        $zip->close();
    }

    public function test_install_happy_path_for_a_zip_source_runs_every_step_and_marks_installed()
    {
        $plugin = $this->makePlugin();
        $this->putPluginZipFixture($plugin);

        $runner = new FakeProcessRunner;
        $this->makeInstaller($runner)->install($plugin);
        $plugin->refresh();

        $this->assertSame('installed', $plugin->status);
        $this->assertNotNull($plugin->installed_at);
        $this->assertStringContainsString('Install completed successfully.', $plugin->log);

        // Extracted into custompackages/{vendor}/{slug}, not left in quarantine.
        $this->assertFileExists($this->basePath.'/custompackages/acme/demoplugin/plugin.json');

        // Composer path repository registered.
        $composer = json_decode(file_get_contents($this->basePath.'/composer.json'), true);
        $this->assertContains(
            ['type' => 'path', 'url' => 'custompackages/acme/demoplugin'],
            $composer['repositories']
        );

        // Route wired for the plugin's one portal (teacher).
        $teacherRoutes = file_get_contents($this->basePath.'/routes/teacher.php');
        $this->assertStringContainsString('routes/gdemoplugin-teacher.php', $teacherRoutes);

        // custom_addon.js patched with the import + register call.
        $customAddonJs = file_get_contents($this->resourcePath.'/assets/js/custom_addon.js');
        $this->assertStringContainsString("import { registerDemoplugin } from './gdemoplugin'", $customAddonJs);
        $this->assertStringContainsString('registerDemoplugin(app)', $customAddonJs);

        // Every shell step actually ran, in a sane order.
        $this->assertTrue($runner->ranCommandStartingWith(['composer', 'require']));
        $this->assertTrue($runner->ranCommandStartingWith(['php', 'artisan', 'vendor:publish']));
        $this->assertTrue($runner->ranCommandStartingWith(['npm', 'install']));
        $this->assertTrue($runner->ranCommandStartingWith(['npm', 'run', 'prod']));
        $this->assertTrue($runner->ranCommandStartingWith(['php', 'artisan', 'migrate']));
    }

    public function test_a_failed_step_marks_the_plugin_failed_and_logs_the_failure_without_rolling_back_earlier_steps()
    {
        $plugin = $this->makePlugin([
            'source_type' => 'git',
            'source_ref' => 'https://example.com/acme/demoplugin.git',
        ]);

        $runner = (new FakeProcessRunner)->failWhenCommandStartsWith(['composer', 'require'], 'could not resolve package');

        $this->makeInstaller($runner)->install($plugin);
        $plugin->refresh();

        $this->assertSame('failed', $plugin->status);
        $this->assertStringContainsString('FAILED: Command failed: composer require', $plugin->log);
        $this->assertStringContainsString('could not resolve package', $plugin->log);

        // No automatic rollback: the git repository registration that ran
        // before the failing step is still there (matches the class docblock).
        $composer = json_decode(file_get_contents($this->basePath.'/composer.json'), true);
        $this->assertContains(
            ['type' => 'vcs', 'url' => 'https://example.com/acme/demoplugin.git'],
            $composer['repositories']
        );
    }

    public function test_uninstall_reverses_every_change_install_made_without_touching_unrelated_state()
    {
        $plugin = $this->makePlugin();
        $this->putPluginZipFixture($plugin);

        $installRunner = new FakeProcessRunner;
        $this->makeInstaller($installRunner)->install($plugin);
        $plugin->refresh();
        $this->assertSame('installed', $plugin->status);

        // The fake composer never actually rewrites composer.json's `require`
        // block the way the real binary would — seed it so runComposerRemove()'s
        // idempotency check (skip if already absent) doesn't short-circuit the
        // command we're trying to verify.
        $composerPath = $this->basePath.'/composer.json';
        $composer = json_decode(file_get_contents($composerPath), true);
        $composer['require']['acme/demoplugin'] = '1.0.0';
        file_put_contents($composerPath, json_encode($composer));

        $uninstallRunner = new FakeProcessRunner;
        $this->makeInstaller($uninstallRunner)->uninstall($plugin);
        $plugin->refresh();

        $this->assertSame('uninstalled', $plugin->status);
        $this->assertStringContainsString('Database tables/data were left untouched.', $plugin->log);

        $this->assertTrue($uninstallRunner->ranCommandStartingWith(['composer', 'remove']));

        // Repository entry removed.
        $composerAfter = json_decode(file_get_contents($composerPath), true);
        $this->assertArrayNotHasKey('repositories', $composerAfter);

        // Route wiring removed, but the rest of the file survives untouched
        // (unwireRoutes() leaves the blank line the guard used to occupy —
        // pre-existing, harmless cosmetic behavior, not something this pass
        // changes).
        $teacherRoutes = file_get_contents($this->basePath.'/routes/teacher.php');
        $this->assertStringNotContainsString('routes/gdemoplugin-teacher.php', $teacherRoutes);
        $this->assertSame("<?php\n\n", $teacherRoutes);

        // custom_addon.js unpatched — import and register call both gone.
        // (unpatchCustomAddonJs(), like unwireRoutes(), leaves behind the
        // blank line the removed call used to occupy — pre-existing,
        // harmless cosmetic behavior, not something this pass changes.)
        $customAddonJs = file_get_contents($this->resourcePath.'/assets/js/custom_addon.js');
        $this->assertStringNotContainsString('registerDemoplugin', $customAddonJs);
        $this->assertSame("export default function registerCustomAddon(app) {\n\n}\n", $customAddonJs);

        // Extracted source removed (zip installs only).
        $this->assertDirectoryDoesNotExist($this->basePath.'/custompackages/acme/demoplugin');
    }
}
