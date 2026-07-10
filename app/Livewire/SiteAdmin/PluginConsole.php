<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Livewire\SiteAdmin;

use App\Models\Plugin;
use Livewire\Component;

/**
 * Class PluginConsole
 *
 * Platform-operator screen (siteadmin only) listing every staged/installed
 * plugin and its status. Staging a new install lives on its own page/
 * component (StagePluginInstall, at /plugins/stage) — this one is
 * list-only.
 */
class PluginConsole extends Component
{
    public function render()
    {
        return view('livewire.site-admin.plugin-console', [
            'plugins' => Plugin::orderBy('id', 'desc')->get(),
        ]);
    }

    /**
     * Stage an uninstall: composer remove, un-wire routes, un-patch
     * custom_addon.js, remove the extracted custompackages/ folder (zip
     * only). Does NOT touch the plugin's own database tables/data. Actual
     * execution happens later in gego:processplugininstalls, same as
     * install — never runs synchronously in this web request.
     */
    public function uninstall($pluginId)
    {
        $plugin = Plugin::find($pluginId);

        if (! $plugin || $plugin->status !== 'installed') {
            return;
        }

        $plugin->status = 'uninstall_staged';
        $plugin->save();

        session()->flash('successmessage', "Uninstall staged for '{$plugin->slug}' — it will be processed within a minute.");
    }
}
