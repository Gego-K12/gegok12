<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Services\Process;

final class ProcessResult
{
    public function __construct(
        public readonly bool $successful,
        public readonly string $output,
        public readonly string $errorOutput,
    ) {}
}
