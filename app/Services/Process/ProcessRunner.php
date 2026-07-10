<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Services\Process;

/**
 * Seam around actually shelling out, so PluginInstaller's composer/npm/
 * artisan steps can be faked in tests instead of running real subprocesses
 * against the project.
 */
interface ProcessRunner
{
    public function run(array $command, string $cwd, int $timeout = 120): ProcessResult;
}
