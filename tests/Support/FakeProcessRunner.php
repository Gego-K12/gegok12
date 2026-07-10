<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace Tests\Support;

use App\Services\Process\ProcessResult;
use App\Services\Process\ProcessRunner;

/**
 * Test double for PluginInstaller's shell-out seam. Records every command it
 * was asked to run (so tests can assert composer/npm/artisan were invoked
 * correctly) and succeeds by default; configure failWhenCommandStartsWith()
 * to simulate a specific step blowing up.
 */
class FakeProcessRunner implements ProcessRunner
{
    /** @var array<int, array{command: array, cwd: string, timeout: int}> */
    public array $ranCommands = [];

    private array $failingPrefixes = [];

    public function failWhenCommandStartsWith(array $prefix, string $errorOutput = 'simulated failure'): static
    {
        $this->failingPrefixes[] = ['prefix' => $prefix, 'error' => $errorOutput];

        return $this;
    }

    public function run(array $command, string $cwd, int $timeout = 120): ProcessResult
    {
        $this->ranCommands[] = ['command' => $command, 'cwd' => $cwd, 'timeout' => $timeout];

        foreach ($this->failingPrefixes as $failing) {
            if (array_slice($command, 0, count($failing['prefix'])) === $failing['prefix']) {
                return new ProcessResult(false, '', $failing['error']);
            }
        }

        return new ProcessResult(true, 'ok', '');
    }

    public function ranCommandStartingWith(array $prefix): bool
    {
        foreach ($this->ranCommands as $ran) {
            if (array_slice($ran['command'], 0, count($prefix)) === $prefix) {
                return true;
            }
        }

        return false;
    }
}
