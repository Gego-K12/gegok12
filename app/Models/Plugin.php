<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Plugin
 *
 * Registry of plugins known to this platform install — one row per plugin,
 * tracking its source (git/zip), install status, and last run's log.
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string $version
 * @property string $source_type
 * @property string $source_ref
 * @property string $composer_package
 * @property string $provider_class
 * @property string $seeder_class
 * @property string $portal
 * @property bool $has_menu
 * @property bool $has_dashboard_widget
 * @property bool $has_tools_menu
 * @property bool $has_profile_tab
 * @property string $profile_tab_label
 * @property string $profile_tab_scope
 * @property string $status
 * @property string $log
 * @property int $requested_by
 * @property \DateTime $installed_at
 *
 * @mixin \Eloquent
 */
class Plugin extends Model
{
    protected $table = 'plugins';

    protected $fillable = [
        'slug', 'name', 'version', 'source_type', 'source_ref', 'composer_package',
        'provider_class', 'seeder_class', 'portal', 'has_menu', 'has_dashboard_widget',
        'has_tools_menu', 'has_profile_tab', 'profile_tab_label', 'profile_tab_scope',
        'status', 'log', 'requested_by', 'installed_at',
    ];

    protected $casts = [
        'installed_at' => 'datetime',
        'has_menu' => 'boolean',
        'has_dashboard_widget' => 'boolean',
        'has_tools_menu' => 'boolean',
        'has_profile_tab' => 'boolean',
    ];

    /**
     * A plugin can target more than one portal at once — `portal` is stored
     * as a comma-separated list (e.g. "teacher,admin") rather than a single
     * value, so it can wire routes/menus into each portal independently.
     */
    public function portalsArray(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $this->portal))));
    }

    public function hasPortal(string $portal): bool
    {
        return in_array($portal, $this->portalsArray(), true);
    }

    /**
     * Matches a plugin whose comma-separated `portal` list contains the
     * given portal — not a plain equality check, since a plugin may target
     * several portals at once.
     */
    private function filterByPortal($query, string $portal)
    {
        return $query->where(function ($q) use ($portal) {
            $q->where('portal', $portal)
                ->orWhere('portal', 'like', "{$portal},%")
                ->orWhere('portal', 'like', "%,{$portal}")
                ->orWhere('portal', 'like', "%,{$portal},%");
        });
    }

    /**
     * Plugins that should render into the given portal's sidebar menu, in
     * the WordPress-style "menu.blade.php hook" convention: published to
     * resources/views/plugins/{slug}/{portal}/menu.blade.php.
     */
    public function scopeWithMenuFor($query, string $portal)
    {
        return $this->filterByPortal($query, $portal)->where('status', 'installed')->where('has_menu', true);
    }

    /**
     * Same as scopeWithMenuFor, for the {portal}/dashboard-widget.blade.php hook.
     */
    public function scopeWithDashboardWidgetFor($query, string $portal)
    {
        return $this->filterByPortal($query, $portal)->where('status', 'installed')->where('has_dashboard_widget', true);
    }

    /**
     * Plugins that should render a link inside the Admin portal's "Tools"
     * flyout submenu (as opposed to a top-level sidebar item via
     * scopeWithMenuFor) — published to resources/views/plugins/{slug}/tools-menu.blade.php.
     */
    public function scopeWithToolsMenuFor($query, string $portal)
    {
        return $this->filterByPortal($query, $portal)->where('status', 'installed')->where('has_tools_menu', true);
    }

    /**
     * Plugins that should render a tab on the Admin teacher/staff profile
     * page for the given scope — published to
     * resources/views/plugins/{slug}/profile-tab.blade.php. A plugin
     * declares profile_tab_scope as 'teacher', 'staff', or 'both'.
     */
    public function scopeWithProfileTabFor($query, string $scope)
    {
        return $query->where('status', 'installed')
            ->where('has_profile_tab', true)
            ->where(function ($q) use ($scope) {
                $q->where('profile_tab_scope', $scope)->orWhere('profile_tab_scope', 'both');
            });
    }

    /**
     * View name for this plugin's menu hook in the given portal — always
     * namespaced by portal so a plugin targeting several portals can render
     * different content (e.g. different links/labels) in each one.
     */
    public function menuViewName(string $portal): string
    {
        return "plugins.{$this->slug}.{$portal}.menu";
    }

    /**
     * Same as menuViewName, for the dashboard-widget hook.
     */
    public function dashboardWidgetViewName(string $portal): string
    {
        return "plugins.{$this->slug}.{$portal}.dashboard-widget";
    }

    /**
     * The Tools submenu only exists in the Admin portal, so unlike the menu/
     * dashboard-widget hooks this one is never portal-namespaced.
     */
    public function toolsMenuViewName(): string
    {
        return "plugins.{$this->slug}.tools-menu";
    }

    /**
     * The profile-tab hook isn't portal-namespaced (it's not tied to the
     * plugin's own `portal` list at all — it's keyed by profile_tab_scope
     * instead), so like toolsMenuViewName this is a single, fixed name.
     */
    public function profileTabViewName(): string
    {
        return "plugins.{$this->slug}.profile-tab";
    }

    public function requestedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function appendLog(string $line)
    {
        $this->log = trim(($this->log ?? '')."\n".'['.now()->toDateTimeString().'] '.$line);
        $this->save();
    }

    /**
     * The stored log keeps raw process output, ANSI escape codes included
     * (useful if ever inspected directly via tinker/DB) — not just color
     * (SGR, ending in 'm') but cursor-position/erase codes too (e.g.
     * Laravel Mix's progress bar emits "\x1B[1;1H\x1B[0J"). This strips
     * every CSI sequence (ESC [ ... final-byte) for HTML display, where
     * they'd otherwise show up as garbled "[37;44m" style noise.
     */
    public function getCleanLogAttribute(): string
    {
        return preg_replace('/\x1B\[[0-9;?]*[A-Za-z]/', '', (string) $this->log);
    }
}
