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
 * Renders a small tab strip on the Admin teacher/staff profile pages for any
 * installed plugin declaring has_profile_tab=true — the plugin-hook
 * equivalent of the has_menu/has_dashboard_widget/has_tools_menu hooks, but
 * for a per-record detail page rather than a static layout. Each plugin
 * supplies its own resources/views/plugins/{slug}/profile-tab.blade.php,
 * included here for whichever plugin's tab is currently active.
 */
class ProfileExtraTabs extends Component
{
    public $entityId;

    public $scope;

    public $activeSlug = null;

    public function mount($entityId, $scope)
    {
        $this->entityId = $entityId;
        $this->scope = $scope;

        $firstPlugin = Plugin::withProfileTabFor($scope)->first();
        $this->activeSlug = $firstPlugin?->slug;
    }

    public function render()
    {
        return view('livewire.admin.profile-extra-tabs', [
            'plugins' => Plugin::withProfileTabFor($this->scope)->get(),
        ]);
    }
}
