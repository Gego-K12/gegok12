<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Services;

use App\Models\Plugin;
use Exception;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use ZipArchive;

/**
 * Non-interactive plugin install executor. Extracted from the shared logic
 * duplicated across app/Console/Commands/Addon/Install*Module.php, minus the
 * interactive prompts, so it can run from the gego:processplugininstalls
 * scheduled command instead of a developer's terminal.
 *
 * Every step's output is appended to the Plugin's log; the first failing
 * step stops the run and marks the plugin failed. There is no automatic
 * rollback of partial composer/filesystem changes, matching the behavior of
 * the existing Install*Module commands.
 */
class PluginInstaller
{
    /** 50 MB — generous for a small plugin package, small enough to block zip-bomb abuse. */
    private const MAX_ZIP_UNCOMPRESSED_BYTES = 50 * 1024 * 1024;

    private const VALID_PORTALS = ['web', 'admin', 'teacher', 'student', 'api'];

    /**
     * Read plugin.json out of a zip WITHOUT extracting anything — used by the
     * SiteAdmin form to auto-fill fields the moment a file is chosen, so the
     * operator doesn't have to retype what's already in the manifest. Returns
     * null on anything unreadable/malformed; callers should fall back to
     * manual entry rather than treating this as a hard error, since the real
     * validation (zip-slip, size limit, manifest completeness) happens for
     * real in install() at execution time.
     */
    public static function peekManifest(string $zipPath): ?array
    {
        $zip = new ZipArchive;
        if ($zip->open($zipPath) !== true) {
            return null;
        }

        $contents = $zip->getFromName('plugin.json');

        if ($contents === false) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (preg_match('#^[^/]+/plugin\.json$#', $name)) {
                    $contents = $zip->getFromName($name);
                    break;
                }
            }
        }

        $zip->close();

        if (empty($contents)) {
            return null;
        }

        $manifest = json_decode($contents, true);

        return is_array($manifest) ? $manifest : null;
    }

    public function install(Plugin $plugin): void
    {
        try {
            if ($plugin->source_type === 'zip') {
                $this->prepareZipSource($plugin);
            } else {
                $this->prepareGitSource($plugin);
            }

            $this->runComposerRequire($plugin);
            $this->publishAssets($plugin);
            $this->checkHookPartials($plugin);
            $this->wireRoutes($plugin);
            $this->patchCustomAddonJs($plugin);
            $this->runNpmBuild($plugin);
            $this->runMigrations($plugin);
            $this->runSeeder($plugin);

            $plugin->status = 'installed';
            $plugin->installed_at = now();
            $plugin->save();
            $plugin->appendLog('Install completed successfully.');
        } catch (Exception $e) {
            $plugin->status = 'failed';
            $plugin->save();
            $plugin->appendLog('FAILED: '.$e->getMessage());
        }
    }

    /**
     * Mirror image of install(): composer remove, un-wire routes, un-patch
     * custom_addon.js, remove the extracted custompackages/ folder (zip
     * installs only), rebuild JS. Deliberately does NOT touch the plugin's
     * own database tables/data or its published views/config — those are
     * left in place so re-installing later finds everything still there.
     */
    public function uninstall(Plugin $plugin): void
    {
        try {
            $this->runComposerRemove($plugin);
            $this->removeRepositoryEntry($plugin);
            $this->unwireRoutes($plugin);
            $this->unpatchCustomAddonJs($plugin);
            $this->removeExtractedSource($plugin);
            $this->runNpmBuild($plugin);

            $plugin->status = 'uninstalled';
            $plugin->save();
            $plugin->appendLog('Uninstall completed successfully. Database tables/data were left untouched.');
        } catch (Exception $e) {
            $plugin->status = 'failed';
            $plugin->save();
            $plugin->appendLog('UNINSTALL FAILED: '.$e->getMessage());
        }
    }

    /**
     * Extract the uploaded zip into a quarantine dir, validate it (zip-slip,
     * size, manifest) before moving anything into custompackages/, then
     * register it as a Composer path repository.
     */
    private function prepareZipSource(Plugin $plugin): void
    {
        // Retrying a plugin that failed after this step already ran (e.g. a
        // later npm/migrate failure) would otherwise re-extract into a
        // directory that's already there. Detect that and resume instead of
        // re-doing the extraction.
        $existing = $this->findExistingExtraction($plugin->slug);
        if ($existing !== null) {
            $plugin->appendLog("custompackages/{$existing['vendor']}/{$plugin->slug} already exists from a previous attempt — resuming without re-extracting.");
            $this->addRepository($plugin, [
                'type' => 'path',
                'url' => "custompackages/{$existing['vendor']}/{$plugin->slug}",
            ]);

            return;
        }

        $zipPath = storage_path('app/'.$plugin->source_ref);

        if (! file_exists($zipPath)) {
            throw new Exception("Uploaded zip not found at {$plugin->source_ref}");
        }

        $zip = new ZipArchive;
        if ($zip->open($zipPath) !== true) {
            throw new Exception('Could not open uploaded zip file.');
        }

        $totalUncompressed = 0;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            $name = $stat['name'];

            if (str_contains($name, '..') || Str::startsWith($name, '/') || preg_match('#^[A-Za-z]:[\\\\/]#', $name)) {
                $zip->close();

                throw new Exception("Rejected zip: unsafe path entry '{$name}' (zip-slip).");
            }

            $totalUncompressed += $stat['size'];
            if ($totalUncompressed > self::MAX_ZIP_UNCOMPRESSED_BYTES) {
                $zip->close();

                throw new Exception('Rejected zip: extracted content exceeds the size limit.');
            }
        }

        $quarantineDir = storage_path('app/plugin-quarantine/'.Str::uuid());
        mkdir($quarantineDir, 0755, true);

        $zip->extractTo($quarantineDir);
        $zip->close();
        $plugin->appendLog('Zip extracted to quarantine and validated (no zip-slip, within size limit).');

        $manifestRoot = $this->locateManifestRoot($quarantineDir);
        if ($manifestRoot === null) {
            throw new Exception('Rejected zip: no plugin.json manifest found.');
        }

        $manifest = json_decode(file_get_contents($manifestRoot.'/plugin.json'), true);
        $this->validateManifest($manifest);

        if ($manifest['slug'] !== $plugin->slug) {
            throw new Exception("plugin.json slug '{$manifest['slug']}' does not match the staged slug '{$plugin->slug}'.");
        }

        $vendor = $manifest['vendor'];
        $destination = base_path("custompackages/{$vendor}/{$plugin->slug}");

        if (! is_dir(dirname($destination))) {
            mkdir(dirname($destination), 0755, true);
        }
        rename($manifestRoot, $destination);
        $plugin->appendLog("Moved validated plugin into custompackages/{$vendor}/{$plugin->slug}.");

        $this->addRepository($plugin, [
            'type' => 'path',
            'url' => "custompackages/{$vendor}/{$plugin->slug}",
        ]);
    }

    /**
     * Look for a prior, already-extracted copy of this plugin under
     * custompackages/*\/{slug} left behind by an earlier attempt.
     */
    private function findExistingExtraction(string $slug): ?array
    {
        foreach (glob(base_path('custompackages/*/'.$slug), GLOB_ONLYDIR) ?: [] as $dir) {
            if (! file_exists($dir.'/plugin.json')) {
                continue;
            }

            $manifest = json_decode(file_get_contents($dir.'/plugin.json'), true);
            if (is_array($manifest) && ($manifest['slug'] ?? null) === $slug) {
                return ['vendor' => basename(dirname($dir)), 'path' => $dir];
            }
        }

        return null;
    }

    /**
     * Zip archives (e.g. GitHub's "Download ZIP") commonly nest everything
     * under one top-level folder. Find wherever plugin.json actually lives.
     */
    private function locateManifestRoot(string $dir): ?string
    {
        if (file_exists($dir.'/plugin.json')) {
            return $dir;
        }

        foreach (glob($dir.'/*', GLOB_ONLYDIR) as $sub) {
            if (file_exists($sub.'/plugin.json')) {
                return $sub;
            }
        }

        return null;
    }

    private function validateManifest($manifest): void
    {
        if (! is_array($manifest)) {
            throw new Exception('plugin.json is not valid JSON.');
        }

        foreach (['slug', 'name', 'version', 'vendor', 'composer_package', 'provider_class', 'portal'] as $key) {
            if (empty($manifest[$key])) {
                throw new Exception("plugin.json is missing required key '{$key}'.");
            }
        }

        // 'portal' may be a single string ("teacher") or, for a plugin that
        // hooks into more than one portal, an array (["teacher", "admin"]).
        $portals = is_array($manifest['portal']) ? $manifest['portal'] : explode(',', $manifest['portal']);
        foreach ($portals as $portal) {
            if (! in_array(trim($portal), self::VALID_PORTALS)) {
                throw new Exception("plugin.json 'portal' must be one of: ".implode(', ', self::VALID_PORTALS));
            }
        }
    }

    private function prepareGitSource(Plugin $plugin): void
    {
        $this->addRepository($plugin, [
            'type' => 'vcs',
            'url' => $plugin->source_ref,
        ]);
        $plugin->appendLog("Registered git repository {$plugin->source_ref}.");
    }

    private function addRepository(Plugin $plugin, array $repository): void
    {
        $composerPath = base_path('composer.json');
        $composer = json_decode(file_get_contents($composerPath), true);

        $alreadyExists = collect($composer['repositories'] ?? [])
            ->contains(fn ($r) => $r['url'] === $repository['url']);

        if (! $alreadyExists) {
            $composer['repositories'][] = $repository;
            file_put_contents($composerPath, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }
    }

    private function runComposerRequire(Plugin $plugin): void
    {
        $this->runProcess($plugin, ['composer', 'require', $plugin->composer_package.':'.$plugin->version, '--no-interaction']);
    }

    /**
     * Idempotent so a retry after a later step fails (e.g. npm timing out)
     * doesn't re-run `composer remove` on a package that's already gone —
     * composer errors on that instead of no-op'ing.
     */
    private function runComposerRemove(Plugin $plugin): void
    {
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);

        if (! array_key_exists($plugin->composer_package, $composer['require'] ?? [])) {
            $plugin->appendLog("{$plugin->composer_package} is already absent from composer.json — skipping composer remove.");

            return;
        }

        $this->runProcess($plugin, ['composer', 'remove', $plugin->composer_package, '--no-interaction']);
    }

    /**
     * Reverse of addRepository() — composer remove doesn't clean up a
     * manually-added repositories entry on its own.
     */
    private function removeRepositoryEntry(Plugin $plugin): void
    {
        $composerPath = base_path('composer.json');
        $composer = json_decode(file_get_contents($composerPath), true);

        if (empty($composer['repositories'])) {
            return;
        }

        $composer['repositories'] = array_values(array_filter(
            $composer['repositories'],
            function ($r) use ($plugin) {
                $url = $r['url'] ?? '';

                if ($url === $plugin->source_ref) {
                    return false; // git
                }

                return ! preg_match('#^custompackages/[^/]+/'.preg_quote($plugin->slug, '#').'$#', $url);
            }
        ));

        if (empty($composer['repositories'])) {
            unset($composer['repositories']);
        }

        file_put_contents($composerPath, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Zip installs only — delete the extracted custompackages/{vendor}/{slug}
     * directory left behind by prepareZipSource(). Git installs leave no
     * local copy of the source to clean up (composer remove already deleted
     * vendor/{package}).
     */
    private function removeExtractedSource(Plugin $plugin): void
    {
        if ($plugin->source_type !== 'zip') {
            return;
        }

        $existing = $this->findExistingExtraction($plugin->slug);
        if ($existing === null) {
            return;
        }

        $this->deleteDirectory($existing['path']);
        $plugin->appendLog("Removed custompackages/{$existing['vendor']}/{$plugin->slug}.");
    }

    private function deleteDirectory(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }

        foreach (new \FilesystemIterator($dir) as $item) {
            $item->isDir() ? $this->deleteDirectory($item->getPathname()) : unlink($item->getPathname());
        }

        rmdir($dir);
    }

    private function publishAssets(Plugin $plugin): void
    {
        $this->runProcess($plugin, ['php', 'artisan', 'vendor:publish', '--provider='.$plugin->provider_class, '--force']);
    }

    /**
     * A plugin that declares has_menu/has_dashboard_widget is expected to
     * publish resources/views/plugins/{slug}/{portal}/menu.blade.php and/or
     * dashboard-widget.blade.php for each of its portals — one per portal,
     * since a plugin targeting several portals typically wants different
     * content/links in each (the WordPress-style hook convention — see
     * resources/views/layouts/{admin,teacher,student}/menu.blade.php and the
     * dashboard.blade.php equivalents, which @includeIf these paths). A
     * has_tools_menu plugin instead publishes a single, portal-agnostic
     * resources/views/plugins/{slug}/tools-menu.blade.php, since the Tools
     * flyout only exists in the Admin portal. Same for has_profile_tab's
     * resources/views/plugins/{slug}/profile-tab.blade.php, rendered by the
     * ProfileExtraTabs Livewire component on the Admin teacher/staff profile
     * pages. A missing partial doesn't fail the install (the @includeIf just
     * silently renders nothing), but it's almost certainly a plugin-author
     * mistake worth surfacing in the log rather than staying silent forever.
     */
    private function checkHookPartials(Plugin $plugin): void
    {
        foreach ($plugin->portalsArray() as $portal) {
            if ($plugin->has_menu && ! view()->exists($plugin->menuViewName($portal))) {
                $plugin->appendLog("WARNING: has_menu is true but resources/views/plugins/{$plugin->slug}/{$portal}/menu.blade.php was not published.");
            }

            if ($plugin->has_dashboard_widget && ! view()->exists($plugin->dashboardWidgetViewName($portal))) {
                $plugin->appendLog("WARNING: has_dashboard_widget is true but resources/views/plugins/{$plugin->slug}/{$portal}/dashboard-widget.blade.php was not published.");
            }
        }

        if ($plugin->has_tools_menu && ! view()->exists($plugin->toolsMenuViewName())) {
            $plugin->appendLog("WARNING: has_tools_menu is true but resources/views/plugins/{$plugin->slug}/tools-menu.blade.php was not published.");
        }

        if ($plugin->has_profile_tab && ! view()->exists($plugin->profileTabViewName())) {
            $plugin->appendLog("WARNING: has_profile_tab is true but resources/views/plugins/{$plugin->slug}/profile-tab.blade.php was not published.");
        }
    }

    private function routeFileFor(string $portal): string
    {
        return match ($portal) {
            'admin' => 'routes/admin.php',
            'teacher' => 'routes/teacher.php',
            'student' => 'routes/student.php',
            'api' => 'routes/api.php',
            default => 'routes/web.php',
        };
    }

    /**
     * A plugin targeting several portals publishes one route file per
     * portal (routes/g{slug}-{portal}.php), each wired into that portal's
     * own route file — a plugin's teacher-side and admin-side routes are
     * rarely the same set of endpoints/controllers, so they can't share one
     * published file the way a single-portal plugin's routes do.
     */
    private function publishedRouteFileFor(Plugin $plugin, string $portal): string
    {
        return 'routes/g'.$plugin->slug.'-'.$portal.'.php';
    }

    private function wireRoutes(Plugin $plugin): void
    {
        foreach ($plugin->portalsArray() as $portal) {
            $routeFile = $this->routeFileFor($portal);
            $publishedRouteFile = $this->publishedRouteFileFor($plugin, $portal);
            $guard = "if (file_exists(base_path('{$publishedRouteFile}'))) {\n    require base_path('{$publishedRouteFile}');\n}";

            $path = base_path($routeFile);
            $contents = file_get_contents($path);

            if (! str_contains($contents, $publishedRouteFile)) {
                file_put_contents($path, $contents."\n".$guard."\n");
                $plugin->appendLog("Wired {$publishedRouteFile} into {$routeFile}.");
            }
        }
    }

    /**
     * Reverse of wireRoutes() — removes exactly the guard block that was
     * appended, leaving the rest of each routes file untouched. The
     * published routes/g{slug}-{portal}.php files themselves are left in
     * place (harmless once nothing requires them).
     */
    private function unwireRoutes(Plugin $plugin): void
    {
        foreach ($plugin->portalsArray() as $portal) {
            $routeFile = $this->routeFileFor($portal);
            $publishedRouteFile = $this->publishedRouteFileFor($plugin, $portal);
            $guard = "if (file_exists(base_path('{$publishedRouteFile}'))) {\n    require base_path('{$publishedRouteFile}');\n}";

            $path = base_path($routeFile);
            if (! file_exists($path)) {
                continue;
            }

            $contents = file_get_contents($path);
            $updated = str_replace("\n".$guard."\n", "\n", $contents);

            if ($updated !== $contents) {
                file_put_contents($path, $updated);
                $plugin->appendLog("Removed {$publishedRouteFile} wiring from {$routeFile}.");
            }
        }
    }

    private function patchCustomAddonJs(Plugin $plugin): void
    {
        $path = resource_path('assets/js/custom_addon.js');
        if (! file_exists($path)) {
            throw new Exception('custom_addon.js not found.');
        }

        $registerFn = 'register'.Str::studly($plugin->slug);
        $importLine = "import { {$registerFn} } from './g{$plugin->slug}'";
        $contents = file_get_contents($path);

        if (! str_contains($contents, $importLine)) {
            $contents = preg_match('/^import .*$/m', $contents)
                ? preg_replace('/^import .*$/m', "$0\n".$importLine, $contents, 1)
                : $importLine."\n\n".$contents;
        }

        if (! str_contains($contents, $registerFn.'(app)')) {
            $contents = preg_replace(
                '/export default function registerCustomAddon\(app\)\s*\{/',
                "export default function registerCustomAddon(app) {\n\n    {$registerFn}(app)",
                $contents
            );
        }

        file_put_contents($path, $contents);
        $plugin->appendLog('Patched custom_addon.js.');
    }

    /**
     * Reverse of patchCustomAddonJs() — strips exactly the import line and
     * register call that were inserted, however they were inserted (first
     * import in the file vs. appended after an existing one).
     */
    private function unpatchCustomAddonJs(Plugin $plugin): void
    {
        $path = resource_path('assets/js/custom_addon.js');
        if (! file_exists($path)) {
            return;
        }

        $registerFn = 'register'.Str::studly($plugin->slug);
        $importLine = "import { {$registerFn} } from './g{$plugin->slug}'";
        $contents = file_get_contents($path);

        $contents = str_replace("\n".$importLine, '', $contents);
        $contents = str_replace($importLine."\n\n", '', $contents);
        $contents = str_replace($importLine, '', $contents);
        $contents = str_replace("\n    {$registerFn}(app)", '', $contents);

        file_put_contents($path, $contents);
        $plugin->appendLog('Un-patched custom_addon.js.');
    }

    private function runNpmBuild(Plugin $plugin): void
    {
        $this->runProcess($plugin, ['npm', 'install'], 300);
        // Laravel Mix, not Vite -- there is no "build" script, "prod" is the
        // production build (package.json:9-10).
        $this->runProcess($plugin, ['npm', 'run', 'prod'], 300);
    }

    private function runMigrations(Plugin $plugin): void
    {
        $this->runProcess($plugin, ['php', 'artisan', 'migrate', '--force']);
    }

    private function runSeeder(Plugin $plugin): void
    {
        if (empty($plugin->seeder_class)) {
            return;
        }

        $this->runProcess($plugin, ['php', 'artisan', 'db:seed', '--class='.$plugin->seeder_class, '--force']);
    }

    private function runProcess(Plugin $plugin, array $command, int $timeout = 120): void
    {
        $process = new Process($command, base_path(), null, null, $timeout);
        $process->run();

        $plugin->appendLog('$ '.implode(' ', $command)."\n".$process->getOutput().$process->getErrorOutput());

        if (! $process->isSuccessful()) {
            throw new Exception('Command failed: '.implode(' ', $command));
        }
    }
}
