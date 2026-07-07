<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Console\Commands;

use App\Models\Plugin;
use App\Services\PluginInstaller;
use Illuminate\Console\Command;

class ProcessPluginInstalls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:processplugininstalls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute one staged plugin install or uninstall (composer/publish/migrate/build)';

    public function handle(PluginInstaller $installer)
    {
        // Process one at a time: composer isn't safe for concurrent
        // invocations against the same composer.json. Installs take
        // priority; either way only one plugin is touched per run.
        $plugin = Plugin::where('status', 'staged')->orderBy('id')->first();

        if ($plugin) {
            $plugin->status = 'installing';
            $plugin->save();

            $installer->install($plugin);

            $this->info("Plugin '{$plugin->slug}' finished with status: {$plugin->status}");

            return;
        }

        $plugin = Plugin::where('status', 'uninstall_staged')->orderBy('id')->first();

        if ($plugin) {
            $plugin->status = 'uninstalling';
            $plugin->save();

            $installer->uninstall($plugin);

            $this->info("Plugin '{$plugin->slug}' finished with status: {$plugin->status}");
        }
    }
}
