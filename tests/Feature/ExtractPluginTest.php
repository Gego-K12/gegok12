<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Feature;

use App\Services\Process\ProcessRunner;
use Tests\Support\FakeProcessRunner;
use Tests\TestCase;

/**
 * Covers gegok12:extractPlugin — the "graduate a local plugin out of
 * custompackages/ into a standalone git repo" command. Git itself is faked
 * (via ProcessRunner, same seam PluginInstallerTest uses) so these tests
 * don't depend on a real git binary or touch this project's own git state.
 */
class ExtractPluginTest extends TestCase
{
    private string $sourceDir;

    private string $destinationDir;

    private FakeProcessRunner $runner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sourceDir = $this->makeTempDir('extract-plugin-source');
        $this->destinationDir = sys_get_temp_dir().'/extract-plugin-dest-'.uniqid();

        file_put_contents($this->sourceDir.'/plugin.json', json_encode([
            'slug' => 'demoplugin',
            'name' => 'Demo Plugin',
            'version' => '1.2.0',
            'vendor' => 'acme',
            'composer_package' => 'acme/demoplugin',
            'provider_class' => 'Acme\\DemoPlugin\\DemoPluginServiceProvider',
            'portal' => 'teacher',
        ]));
        file_put_contents($this->sourceDir.'/composer.json', json_encode(['name' => 'acme/demoplugin']));

        $this->runner = new FakeProcessRunner;
        $this->app->instance(ProcessRunner::class, $this->runner);
    }

    protected function tearDown(): void
    {
        $this->deleteTempDir($this->sourceDir);
        $this->deleteTempDir($this->destinationDir);

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

    public function test_it_extracts_a_scaffolded_plugin_into_a_standalone_tagged_git_repo()
    {
        $this->artisan('gegok12:extractPlugin', [
            'slug' => 'demoplugin',
            '--from' => $this->sourceDir,
            '--to' => $this->destinationDir,
        ])->assertExitCode(0);

        $this->assertFileExists($this->destinationDir.'/plugin.json');
        $this->assertFileExists($this->destinationDir.'/composer.json');

        // Source left untouched — local dev keeps working.
        $this->assertFileExists($this->sourceDir.'/plugin.json');

        $this->assertTrue($this->runner->ranCommandStartingWith(['git', 'init']));
        $this->assertTrue($this->runner->ranCommandStartingWith(['git', 'add', '.']));
        $this->assertTrue($this->runner->ranCommandStartingWith(['git', 'commit']));
        $this->assertTrue($this->runner->ranCommandStartingWith(['git', 'tag', 'v1.2.0']));
        $this->assertFalse($this->runner->ranCommandStartingWith(['git', 'push']));

        $this->assertSame($this->destinationDir, $this->runner->ranCommands[0]['cwd']);
    }

    public function test_it_auto_detects_the_source_directory_under_custompackages_when_from_is_omitted()
    {
        $slug = 'autodetectdemo-'.uniqid();
        $autoSourceDir = base_path("custompackages/acme/{$slug}");
        mkdir($autoSourceDir, 0755, true);
        file_put_contents($autoSourceDir.'/plugin.json', json_encode([
            'slug' => $slug,
            'version' => '1.0.0',
            'vendor' => 'acme',
        ]));

        try {
            $this->artisan('gegok12:extractPlugin', [
                'slug' => $slug,
                '--to' => $this->destinationDir,
            ])->assertExitCode(0);

            $this->assertFileExists($this->destinationDir.'/plugin.json');
        } finally {
            $this->deleteTempDir($autoSourceDir);
            $this->deleteTempDir(dirname($autoSourceDir));
        }
    }

    public function test_it_fails_when_the_plugin_cannot_be_found()
    {
        $this->artisan('gegok12:extractPlugin', [
            'slug' => 'does-not-exist-anywhere',
        ])
            ->expectsOutputToContain("Could not find a scaffolded plugin 'does-not-exist-anywhere'")
            ->assertExitCode(1);

        $this->assertEmpty($this->runner->ranCommands);
    }

    public function test_push_without_remote_is_rejected_before_touching_anything()
    {
        $this->artisan('gegok12:extractPlugin', [
            'slug' => 'demoplugin',
            '--from' => $this->sourceDir,
            '--to' => $this->destinationDir,
            '--push' => true,
        ])
            ->expectsOutputToContain('--push requires --remote.')
            ->assertExitCode(1);

        $this->assertFileDoesNotExist($this->destinationDir);
        $this->assertEmpty($this->runner->ranCommands);
    }

    public function test_remote_is_wired_up_but_not_pushed_unless_push_is_also_given()
    {
        $this->artisan('gegok12:extractPlugin', [
            'slug' => 'demoplugin',
            '--from' => $this->sourceDir,
            '--to' => $this->destinationDir,
            '--remote' => 'git@github.com:Gego-K12/demoplugin.git',
        ])->assertExitCode(0);

        $this->assertTrue($this->runner->ranCommandStartingWith(['git', 'remote', 'add', 'origin', 'git@github.com:Gego-K12/demoplugin.git']));
        $this->assertFalse($this->runner->ranCommandStartingWith(['git', 'push']));
    }

    public function test_remote_and_push_together_actually_push()
    {
        $this->artisan('gegok12:extractPlugin', [
            'slug' => 'demoplugin',
            '--from' => $this->sourceDir,
            '--to' => $this->destinationDir,
            '--remote' => 'git@github.com:Gego-K12/demoplugin.git',
            '--push' => true,
        ])->assertExitCode(0);

        $this->assertTrue($this->runner->ranCommandStartingWith(['git', 'push', '-u', 'origin', 'main', '--tags']));
    }

    public function test_an_existing_destination_requires_force()
    {
        mkdir($this->destinationDir, 0755, true);
        file_put_contents($this->destinationDir.'/pre-existing.txt', 'do not clobber me');

        $this->artisan('gegok12:extractPlugin', [
            'slug' => 'demoplugin',
            '--from' => $this->sourceDir,
            '--to' => $this->destinationDir,
        ])
            ->expectsOutputToContain('already exists. Pass --force to overwrite it.')
            ->assertExitCode(1);

        $this->assertFileExists($this->destinationDir.'/pre-existing.txt');
        $this->assertEmpty($this->runner->ranCommands);

        $this->artisan('gegok12:extractPlugin', [
            'slug' => 'demoplugin',
            '--from' => $this->sourceDir,
            '--to' => $this->destinationDir,
            '--force' => true,
        ])->assertExitCode(0);

        $this->assertFileDoesNotExist($this->destinationDir.'/pre-existing.txt');
        $this->assertFileExists($this->destinationDir.'/plugin.json');
    }

    public function test_a_failing_git_step_stops_the_command_and_reports_it()
    {
        $this->runner->failWhenCommandStartsWith(['git', 'commit'], 'nothing to commit');

        $this->artisan('gegok12:extractPlugin', [
            'slug' => 'demoplugin',
            '--from' => $this->sourceDir,
            '--to' => $this->destinationDir,
        ])
            ->expectsOutputToContain('Command failed: git commit')
            ->assertExitCode(1);
    }
}
