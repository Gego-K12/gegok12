<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Support;

use RuntimeException;

/**
 * Single source of truth for the per-portal facts that
 * resources/views/layouts/_common/{navigation,sidebar}.blade.php need —
 * notification mode, avatar relation/field/wrapping, which dropdown
 * features exist, and sidebar CSS classes. Replaces what used to be
 * hand-copied into 9 near-identical navigation.blade.php/sidebar.blade.php
 * pairs, one per portal.
 */
class PortalConfig
{
    private static ?array $all = null;

    public static function for(string $portal): array
    {
        if (self::$all === null) {
            self::$all = require resource_path('views/layouts/_common/portal-config.php');
        }

        if (! isset(self::$all[$portal])) {
            throw new RuntimeException("No portal config registered for '{$portal}'.");
        }

        return self::$all[$portal];
    }
}
