<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Livewire\SiteAdmin;

use App\Models\Plugin;
use App\Services\PluginInstaller;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Class PluginConsole
 *
 * Platform-operator screen (siteadmin only) for staging a new plugin
 * install from a git URL or an uploaded zip. Submitting only writes a
 * `plugins` row with status=staged — the actual composer/npm/migrate work
 * happens later, out of the web request, in the gego:processplugininstalls
 * scheduled command (via App\Services\PluginInstaller).
 */
class PluginConsole extends Component
{
    use WithFileUploads;

    public $source_type = 'git';

    public $slug = '';

    public $name = '';

    public $version = '';

    public $composer_package = '';

    public $provider_class = '';

    public $seeder_class = '';

    public $portals = ['admin'];

    public $has_menu = false;

    public $has_dashboard_widget = false;

    public $has_tools_menu = false;

    public $has_profile_tab = false;

    public $profile_tab_label = '';

    public $profile_tab_scope = 'both';

    public $has_before_content = false;

    public $has_after_content = false;

    public $git_url = '';

    public $zip;

    public $manifestDetected = false;

    public $manifestUnreadable = false;

    /**
     * Fires when a zip finishes uploading. Peeks its plugin.json (no
     * extraction) and auto-fills the form, so the operator isn't retyping
     * what's already in the manifest.
     */
    public function updatedZip()
    {
        $this->manifestDetected = false;
        $this->manifestUnreadable = false;

        $manifest = PluginInstaller::peekManifest($this->zip->getRealPath());

        if (! $manifest) {
            $this->manifestUnreadable = true;

            return;
        }

        $this->slug = $manifest['slug'] ?? $this->slug;
        $this->name = $manifest['name'] ?? $this->name;
        $this->version = $manifest['version'] ?? $this->version;
        $this->composer_package = $manifest['composer_package'] ?? $this->composer_package;
        $this->provider_class = $manifest['provider_class'] ?? $this->provider_class;
        $this->seeder_class = $manifest['seeder_class'] ?? $this->seeder_class;

        if (isset($manifest['portal'])) {
            $this->portals = is_array($manifest['portal'])
                ? $manifest['portal']
                : array_map('trim', explode(',', $manifest['portal']));
        }

        $this->has_menu = (bool) ($manifest['has_menu'] ?? false);
        $this->has_dashboard_widget = (bool) ($manifest['has_dashboard_widget'] ?? false);
        $this->has_tools_menu = (bool) ($manifest['has_tools_menu'] ?? false);
        $this->has_profile_tab = (bool) ($manifest['has_profile_tab'] ?? false);
        $this->profile_tab_label = $manifest['profile_tab_label'] ?? $this->profile_tab_label;
        $this->profile_tab_scope = $manifest['profile_tab_scope'] ?? $this->profile_tab_scope;
        $this->has_before_content = (bool) ($manifest['has_before_content'] ?? false);
        $this->has_after_content = (bool) ($manifest['has_after_content'] ?? false);
        $this->manifestDetected = true;
    }

    public function render()
    {
        return view('livewire.site-admin.plugin-console', [
            'plugins' => Plugin::orderBy('id', 'desc')->get(),
        ]);
    }

    public function rules()
    {
        $rules = [
            'slug' => 'required|alpha_dash|max:50',
            'name' => 'required|string|max:100',
            'version' => 'required|string|max:50',
            'composer_package' => 'required|string|max:150',
            'provider_class' => 'required|string|max:200',
            'seeder_class' => 'nullable|string|max:200',
            'portals' => 'required|array|min:1',
            'portals.*' => 'in:web,admin,teacher,student,api',
            'has_menu' => 'boolean',
            'has_dashboard_widget' => 'boolean',
            'has_tools_menu' => 'boolean',
            'has_profile_tab' => 'boolean',
            'profile_tab_label' => 'nullable|string|max:100|required_if:has_profile_tab,true',
            'profile_tab_scope' => 'nullable|in:teacher,staff,student,class,event,both',
            'has_before_content' => 'boolean',
            'has_after_content' => 'boolean',
        ];

        if ($this->source_type === 'git') {
            $rules['git_url'] = 'required|url';
        } else {
            $rules['zip'] = 'required|file|mimes:zip|max:51200';
        }

        return $rules;
    }

    public function stageInstall()
    {
        $this->validate();

        $sourceRef = $this->source_type === 'git'
            ? $this->git_url
            : $this->zip->store('plugin-uploads');

        Plugin::create([
            'slug' => $this->slug,
            'name' => $this->name,
            'version' => $this->version,
            'source_type' => $this->source_type,
            'source_ref' => $sourceRef,
            'composer_package' => $this->composer_package,
            'provider_class' => $this->provider_class,
            'seeder_class' => $this->seeder_class ?: null,
            'portal' => implode(',', $this->portals),
            'has_menu' => $this->has_menu,
            'has_dashboard_widget' => $this->has_dashboard_widget,
            'has_tools_menu' => $this->has_tools_menu,
            'has_profile_tab' => $this->has_profile_tab,
            'profile_tab_label' => $this->profile_tab_label ?: null,
            'profile_tab_scope' => $this->profile_tab_scope,
            'has_before_content' => $this->has_before_content,
            'has_after_content' => $this->has_after_content,
            'status' => 'staged',
            'requested_by' => Auth::id(),
        ]);

        $this->reset(['slug', 'name', 'version', 'composer_package', 'provider_class', 'seeder_class', 'git_url', 'zip', 'manifestDetected', 'manifestUnreadable', 'has_menu', 'has_dashboard_widget', 'has_tools_menu', 'has_profile_tab', 'profile_tab_label', 'has_before_content', 'has_after_content']);
        $this->portals = ['admin'];
        $this->profile_tab_scope = 'both';
        $this->source_type = 'git';

        session()->flash('successmessage', 'Plugin staged for install — it will be processed within a minute.');
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
