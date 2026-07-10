<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Livewire\Admin;

use App\Models\Plugin;
use Livewire\Component;

/**
 * Class ProfileExtraTabs
 *
 * Renders a single "Additional Info" panel on the Admin teacher/staff/
 * student detail pages — the plugin-hook equivalent of the has_menu/
 * has_dashboard_widget/has_tools_menu hooks, but for a per-record detail
 * page rather than a static layout. Every installed plugin declaring
 * has_profile_tab=true (matching this page's scope) gets one row inside
 * this single panel, rather than its own top-level tab — that keeps the
 * page's nav at "native tabs + 1" no matter how many plugins hook in,
 * instead of growing a new tab per plugin. Each plugin supplies its own
 * resources/views/plugins/{slug}/profile-tab.blade.php, included here for
 * whichever row is currently expanded.
 */
class ProfileExtraTabs extends Component
{
    public $entityId;

    public $scope;

    public $expandedSlug = null;

    public function mount($entityId, $scope)
    {
        $this->entityId = $entityId;
        $this->scope = $scope;

        $firstPlugin = Plugin::withProfileTabFor($scope)->orderBy('name')->first();
        $this->expandedSlug = $firstPlugin?->slug;
    }

    public function toggle(string $slug): void
    {
        $this->expandedSlug = $this->expandedSlug === $slug ? null : $slug;
    }

    public function render()
    {
        return view('livewire.admin.profile-extra-tabs', [
            'plugins' => Plugin::withProfileTabFor($this->scope)->orderBy('name')->get(),
        ]);
    }
}
