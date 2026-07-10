<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Services\Process;

use Symfony\Component\Process\Process;

/**
 * Real implementation — the one bound in production. Extracted out of
 * PluginInstaller so it can be swapped for a fake in tests.
 */
class SymfonyProcessRunner implements ProcessRunner
{
    public function run(array $command, string $cwd, int $timeout = 120): ProcessResult
    {
        $process = new Process($command, $cwd, null, null, $timeout);
        $process->run();

        return new ProcessResult($process->isSuccessful(), $process->getOutput(), $process->getErrorOutput());
    }
}
