<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Console\Commands\Gegok12;

use App\Services\Process\ProcessRunner;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Extracts a locally-scaffolded plugin (custompackages/{vendor}/{slug}, built
 * via gegok12:newPlugin) into a standalone, tagged git repository, ready to
 * push under the Gego-K12 GitHub org for real distribution.
 *
 * Deliberately does NOT push anywhere on its own unless both --remote and
 * --push are given explicitly — pushing to a shared, public org repo is a
 * decision for whoever runs this command, not something to happen as a side
 * effect of extraction. The plugin's custompackages/ copy (and its composer
 * path-repository wiring in the host app) is left untouched, so local
 * dev/testing keeps working exactly as before after extraction.
 */
class ExtractPlugin extends Command
{
    protected $signature = 'gegok12:extractPlugin
        {slug : Plugin slug, as scaffolded via gegok12:newPlugin}
        {--from= : Override the source directory (default: auto-detected under custompackages/*/{slug})}
        {--to= : Destination directory for the extracted repo (default: storage/app/extracted-plugins/{slug})}
        {--remote= : Git remote URL to add as origin, e.g. git@github.com:Gego-K12/{slug}.git}
        {--push : Push to --remote after committing (requires --remote)}
        {--force : Overwrite an existing destination directory}';

    protected $description = 'Extract a locally-scaffolded plugin into a standalone, tagged git repo for distribution';

    public function handle(ProcessRunner $processRunner): int
    {
        $slug = $this->argument('slug');

        if ($this->option('push') && ! $this->option('remote')) {
            $this->error('--push requires --remote.');

            return self::FAILURE;
        }

        $source = $this->option('from') ?: $this->locateSourceDir($slug);
        if ($source === null) {
            $this->error("Could not find a scaffolded plugin '{$slug}' under custompackages/*/{$slug} with a matching plugin.json. Pass --from to point at it explicitly.");

            return self::FAILURE;
        }

        $manifest = json_decode(file_get_contents($source.'/plugin.json'), true);
        if (! is_array($manifest) || empty($manifest['version'])) {
            $this->error("{$source}/plugin.json is missing or doesn't declare a version.");

            return self::FAILURE;
        }

        $destination = $this->option('to') ?: storage_path('app/extracted-plugins/'.$slug);

        if (File::isDirectory($destination)) {
            if (! $this->option('force')) {
                $this->error("Destination {$destination} already exists. Pass --force to overwrite it.");

                return self::FAILURE;
            }
            File::deleteDirectory($destination);
        }

        File::ensureDirectoryExists(dirname($destination));
        File::copyDirectory($source, $destination);
        $this->info("Copied {$source} -> {$destination}");

        $version = $manifest['version'];
        $steps = [
            ['git', 'init', '-q', '-b', 'main'],
            ['git', 'add', '.'],
            ['git', 'commit', '-q', '-m', "Initial extraction of {$slug} v{$version}"],
            ['git', 'tag', 'v'.$version],
        ];

        foreach ($steps as $command) {
            $result = $processRunner->run($command, $destination);
            if (! $result->successful) {
                $this->error('Command failed: '.implode(' ', $command)."\n".$result->errorOutput.$result->output);

                return self::FAILURE;
            }
        }
        $this->info("Initialized a git repo at {$destination}, committed, tagged v{$version}.");

        $remote = $this->option('remote');
        if ($remote) {
            $result = $processRunner->run(['git', 'remote', 'add', 'origin', $remote], $destination);
            if (! $result->successful) {
                $this->error('Failed to add remote: '.$result->errorOutput);

                return self::FAILURE;
            }
            $this->info("Added remote origin -> {$remote}");

            if ($this->option('push')) {
                $result = $processRunner->run(['git', 'push', '-u', 'origin', 'main', '--tags'], $destination);
                if (! $result->successful) {
                    $this->error('Push failed: '.$result->errorOutput);

                    return self::FAILURE;
                }
                $this->info("Pushed to {$remote}.");

                return self::SUCCESS;
            }
        }

        $this->newLine();
        $this->line('Next step to publish:');
        $this->line("  cd {$destination}");
        if (! $remote) {
            $this->line("  git remote add origin git@github.com:Gego-K12/{$slug}.git");
        }
        $this->line('  git push -u origin main --tags');

        return self::SUCCESS;
    }

    /**
     * Mirrors PluginInstaller::findExistingExtraction()'s glob-scan
     * convention for locating a plugin by slug under custompackages/*.
     */
    private function locateSourceDir(string $slug): ?string
    {
        foreach (glob(base_path('custompackages/*/'.$slug), GLOB_ONLYDIR) ?: [] as $dir) {
            if (! file_exists($dir.'/plugin.json')) {
                continue;
            }

            $manifest = json_decode(file_get_contents($dir.'/plugin.json'), true);
            if (is_array($manifest) && ($manifest['slug'] ?? null) === $slug) {
                return $dir;
            }
        }

        return null;
    }
}
