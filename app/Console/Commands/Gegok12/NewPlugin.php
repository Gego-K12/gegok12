<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Console\Commands\Gegok12;

use App\Models\Plugin;
use App\Models\User;
use App\Services\PluginInstaller;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * Class NewPlugin
 *
 * Interactive scaffolder for local plugin development: generates a working
 * plugin skeleton directly into custompackages/gegok12/{slug} and installs
 * it immediately via PluginInstaller (source_type=path), so it's live for
 * testing without a zip-upload round trip through the SiteAdmin console.
 */
class NewPlugin extends Command
{
    protected $signature = 'gegok12:newPlugin';

    protected $description = 'Scaffold a new plugin into custompackages/gegok12/{slug} and install it locally for development';

    private const VALID_PORTALS = ['web', 'admin', 'teacher', 'student', 'api'];

    private const PORTALS_WITH_LAYOUTS = ['admin', 'teacher', 'student'];

    public function handle(PluginInstaller $installer)
    {
        $slug = $this->askSlug();
        $name = $this->ask('Display name', Str::title(str_replace(['-', '_'], ' ', $slug)));
        $vendor = $this->ask('Vendor', 'gegok12');
        $version = $this->ask('Version', '1.0.0');
        $description = (string) $this->ask('Short description (one line, shown if this plugin is ever listed in a catalog)', '');
        $authorName = (string) $this->ask('Author name', 'GegoSoft Technologies');
        $authorEmail = $this->askAuthorEmail();
        $license = (string) $this->ask('License (SPDX identifier)', 'MIT');
        $thumbnail = (string) $this->ask('Thumbnail image path, relative to the plugin (optional, e.g. resources/assets/thumbnail.png)', '');
        $coverImage = (string) $this->ask('Cover image path, relative to the plugin (optional)', '');

        $portals = $this->choice('Which portals should this plugin target? (comma-separate for more than one)', self::VALID_PORTALS, 0, null, true);

        $hasMenu = $this->confirm('Add a sidebar menu entry (has_menu)?', true);
        $hasDashboardWidget = $this->confirm('Add a dashboard widget (has_dashboard_widget)?');
        $hasToolsMenu = in_array('admin', $portals, true)
            ? $this->confirm('Add an entry to the Admin Tools submenu (has_tools_menu)?')
            : false;
        $hasProfileTab = $this->confirm('Add a row to the Admin teacher/staff/student/class/event "Additional Info" panel (has_profile_tab)?');

        $profileTabLabel = null;
        $profileTabScope = null;
        if ($hasProfileTab) {
            $profileTabLabel = $this->ask('Row label', $name);
            $profileTabScope = $this->choice('Show on', ['both', 'teacher', 'staff', 'student', 'class', 'event'], 0);
        }

        $hasBeforeContent = $this->confirm('Add a before-content hook (renders before every page in this plugin\'s portals)?');
        $hasAfterContent = $this->confirm('Add an after-content hook (renders after every page in this plugin\'s portals)?');

        $requestedBy = $this->askRequestedBy();

        $data = [
            'slug' => $slug,
            'studly' => Str::studly($slug),
            'vendor' => $vendor,
            'vendorStudly' => Str::studly($vendor),
            'name' => $name,
            'version' => $version,
            'description' => $description,
            'author_name' => $authorName,
            'author_email' => $authorEmail,
            'license' => $license,
            'thumbnail' => $thumbnail,
            'cover_image' => $coverImage,
            'portals' => $portals,
            'has_menu' => $hasMenu,
            'has_dashboard_widget' => $hasDashboardWidget,
            'has_tools_menu' => $hasToolsMenu,
            'has_profile_tab' => $hasProfileTab,
            'profile_tab_label' => $profileTabLabel,
            'profile_tab_scope' => $profileTabScope,
            'has_before_content' => $hasBeforeContent,
            'has_after_content' => $hasAfterContent,
        ];

        $destination = base_path("custompackages/{$vendor}/{$slug}");

        $this->scaffold($destination, $data);
        $this->info("Scaffolded {$destination}");

        $plugin = Plugin::create([
            'slug' => $slug,
            'name' => $name,
            'version' => $version,
            'source_type' => 'path',
            'source_ref' => "{$vendor}/{$slug}",
            'composer_package' => "{$vendor}/{$slug}",
            'provider_class' => "{$data['vendorStudly']}\\{$data['studly']}\\{$data['studly']}ServiceProvider",
            'portal' => implode(',', $portals),
            'has_menu' => $hasMenu,
            'has_dashboard_widget' => $hasDashboardWidget,
            'has_tools_menu' => $hasToolsMenu,
            'has_profile_tab' => $hasProfileTab,
            'profile_tab_label' => $profileTabLabel,
            'profile_tab_scope' => $profileTabScope,
            'has_before_content' => $hasBeforeContent,
            'has_after_content' => $hasAfterContent,
            'status' => 'staged',
            'requested_by' => $requestedBy,
        ]);

        $this->info('Installing (composer require, publish, route wiring, npm build, migrate)... this can take a minute.');

        $installer->install($plugin);
        $plugin->refresh();

        $this->line($plugin->clean_log);

        if ($plugin->status === 'installed') {
            $this->info("Installed successfully: {$name}");
            foreach ($portals as $portal) {
                if (in_array($portal, self::PORTALS_WITH_LAYOUTS, true)) {
                    $this->line(" - /{$portal}/{$slug}");
                }
            }
            $this->comment("Edit the plugin at: {$destination}");
            $this->comment("Ready to distribute it? Run: php artisan gegok12:extractPlugin {$slug}");
        } else {
            $this->error("Install did not complete (status: {$plugin->status}). See the log above.");
        }
    }

    private function askSlug(): string
    {
        while (true) {
            $slug = $this->ask('Plugin slug (alpha-numeric, dashes/underscores only, e.g. "attendance-export")');

            if (! $slug || ! preg_match('/^[a-zA-Z0-9_-]+$/', $slug)) {
                $this->error('Slug must be alpha-numeric, dashes, or underscores only.');

                continue;
            }

            if (is_dir(base_path('custompackages/gegok12/'.$slug))) {
                $this->error("custompackages/gegok12/{$slug} already exists.");

                continue;
            }

            if (Plugin::where('slug', $slug)->exists()) {
                $this->error("A plugin with slug '{$slug}' is already registered.");

                continue;
            }

            return $slug;
        }
    }

    private function askAuthorEmail(): string
    {
        while (true) {
            $email = (string) $this->ask('Author support email (optional)', '');

            if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $email;
            }

            $this->error('Not a valid email address — leave blank to skip.');
        }
    }

    private function askRequestedBy(): int
    {
        $siteAdmin = User::where('usergroup_id', User::SITEADMIN_USERGROUP_ID)->first();

        return (int) $this->ask('Attribute this install to which user ID?', $siteAdmin->id ?? 1);
    }

    private function scaffold(string $destination, array $d): void
    {
        $this->put("{$destination}/composer.json", $this->composerJson($d));
        $this->put("{$destination}/plugin.json", $this->pluginJson($d));
        $this->put("{$destination}/readme.md", $this->readme($d));
        $this->put("{$destination}/src/{$d['studly']}ServiceProvider.php", $this->serviceProvider($d));
        $this->put("{$destination}/database/migrations/.gitkeep", '');
        $this->put("{$destination}/resources/assets/js/g{$d['slug']}.js", $this->jsFile($d));

        foreach ($d['portals'] as $portal) {
            $this->put("{$destination}/routes/{$portal}.php", $this->routeFile($portal, $d));
            $this->put("{$destination}/src/Http/Controllers/".Str::studly($portal)."/{$d['studly']}Controller.php", $this->controller($portal, $d));
            $this->put("{$destination}/resources/views/{$portal}/{$d['slug']}/index.blade.php", $this->indexView($portal, $d));

            if ($d['has_menu']) {
                $this->put("{$destination}/resources/views/plugins/{$d['slug']}/{$portal}/menu.blade.php", $this->menuHookView($portal, $d));
            }

            if ($d['has_dashboard_widget']) {
                $this->put("{$destination}/resources/views/plugins/{$d['slug']}/{$portal}/dashboard-widget.blade.php", $this->dashboardWidgetHookView($d));
            }

            if ($d['has_before_content']) {
                $this->put("{$destination}/resources/views/plugins/{$d['slug']}/{$portal}/before-content.blade.php", $this->beforeAfterContentHookView($d, $portal, 'before'));
            }

            if ($d['has_after_content']) {
                $this->put("{$destination}/resources/views/plugins/{$d['slug']}/{$portal}/after-content.blade.php", $this->beforeAfterContentHookView($d, $portal, 'after'));
            }
        }

        if ($d['has_tools_menu']) {
            $this->put("{$destination}/resources/views/plugins/{$d['slug']}/tools-menu.blade.php", $this->toolsMenuHookView($d));
        }

        if ($d['has_profile_tab']) {
            $this->put("{$destination}/resources/views/plugins/{$d['slug']}/profile-tab.blade.php", $this->profileTabHookView($d));
        }
    }

    private function put(string $path, string $contents): void
    {
        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, $contents);
    }

    private function composerJson(array $d): string
    {
        $namespace = "{$d['vendorStudly']}\\\\{$d['studly']}\\\\";

        return <<<JSON
        {
            "name": "{$d['vendor']}/{$d['slug']}",
            "type": "library",
            "version": "{$d['version']}",
            "autoload": {
                "psr-4": {
                    "{$namespace}": "src/"
                }
            },
            "require": {
                "php": "^8.4"
            },
            "extra": {
                "laravel": {
                    "providers": [
                        "{$d['vendorStudly']}\\\\{$d['studly']}\\\\{$d['studly']}ServiceProvider"
                    ]
                }
            },
            "minimum-stability": "dev",
            "prefer-stable": true
        }

        JSON;
    }

    private function pluginJson(array $d): string
    {
        $portals = implode(', ', array_map(fn ($p) => "\"{$p}\"", $d['portals']));
        $extra = '';

        if ($d['has_menu']) {
            $extra .= ",\n    \"has_menu\": true";
        }
        if ($d['has_dashboard_widget']) {
            $extra .= ",\n    \"has_dashboard_widget\": true";
        }
        if ($d['has_tools_menu']) {
            $extra .= ",\n    \"has_tools_menu\": true";
        }
        if ($d['has_profile_tab']) {
            $extra .= ",\n    \"has_profile_tab\": true";
            $extra .= ",\n    \"profile_tab_label\": \"{$d['profile_tab_label']}\"";
            $extra .= ",\n    \"profile_tab_scope\": \"{$d['profile_tab_scope']}\"";
        }
        if ($d['has_before_content']) {
            $extra .= ",\n    \"has_before_content\": true";
        }
        if ($d['has_after_content']) {
            $extra .= ",\n    \"has_after_content\": true";
        }

        $description = $this->jsonString($d['description']);
        $authorName = $this->jsonString($d['author_name']);
        $authorEmail = $this->jsonString($d['author_email']);
        $license = $this->jsonString($d['license']);
        $thumbnail = $this->jsonString($d['thumbnail']);
        $coverImage = $this->jsonString($d['cover_image']);

        return <<<JSON
        {
            "slug": "{$d['slug']}",
            "name": "{$d['name']}",
            "version": "{$d['version']}",
            "vendor": "{$d['vendor']}",
            "description": {$description},
            "author_name": {$authorName},
            "author_email": {$authorEmail},
            "license": {$license},
            "thumbnail": {$thumbnail},
            "cover_image": {$coverImage},
            "composer_package": "{$d['vendor']}/{$d['slug']}",
            "provider_class": "{$d['vendorStudly']}\\\\{$d['studly']}\\\\{$d['studly']}ServiceProvider",
            "portal": [{$portals}]{$extra}
        }

        JSON;
    }

    /**
     * These fields are free text (description/author name/etc.) rather than
     * the slug-like identifiers the rest of this template interpolates
     * unescaped — json_encode() so a quote or backslash in one can't break
     * the generated plugin.json.
     */
    private function jsonString(string $value): string
    {
        return json_encode($value);
    }

    private function readme(array $d): string
    {
        return <<<MD
        # {$d['name']}

        {$d['description']}

        Scaffolded by `php artisan gegok12:newPlugin`. Lives at
        `custompackages/{$d['vendor']}/{$d['slug']}` for local development,
        installed via a composer path repository (source_type=path).

        Portals: {$this->list($d['portals'])}.

        Author: {$d['author_name']}{$this->authorEmailSuffix($d['author_email'])} · License: {$d['license']}

        To distribute this plugin for real (git or zip install elsewhere),
        extract it into its own standalone git repository:

            php artisan gegok12:extractPlugin {$d['slug']}

        See `php artisan gegok12:extractPlugin --help` for pushing it
        straight to a remote (e.g. under the Gego-K12 GitHub org).
        MD;
    }

    private function list(array $items): string
    {
        return implode(', ', $items);
    }

    private function authorEmailSuffix(string $email): string
    {
        return $email === '' ? '' : " ({$email})";
    }

    private function serviceProvider(array $d): string
    {
        $routePublishes = [];
        foreach ($d['portals'] as $portal) {
            $routePublishes[] = "            __DIR__.'/../routes/{$portal}.php' => base_path('routes/g{$d['slug']}-{$portal}.php'),";
        }
        $routePublishes = implode("\n", $routePublishes);

        return <<<PHP
        <?php

        namespace {$d['vendorStudly']}\\{$d['studly']};

        use Illuminate\\Support\\ServiceProvider;

        class {$d['studly']}ServiceProvider extends ServiceProvider
        {
            public function boot()
            {
                // Publish Migrations
                \$this->publishes([
                    __DIR__.'/../database/migrations/' => database_path('migrations'),
                ], '{$d['slug']}-migrations');

                // Publish Views
                \$this->publishes([
                    __DIR__.'/../resources/views' => resource_path('views'),
                ], '{$d['slug']}-views');

                // Publish Routes — one file per portal
                \$this->publishes([
        {$routePublishes}
                ], '{$d['slug']}-routes');

                // Publish components
                \$this->publishes([
                    __DIR__.'/../resources/assets/' => resource_path('assets'),
                ], '{$d['slug']}-components');
            }

            public function register()
            {
                // Register package services if needed
            }
        }

        PHP;
    }

    private function routeFile(string $portal, array $d): string
    {
        $namespace = "{$d['vendorStudly']}\\{$d['studly']}\\Http\\Controllers\\".Str::studly($portal);
        $controller = "{$d['studly']}Controller";

        if ($portal === 'web') {
            return <<<PHP
            <?php

            use Illuminate\\Support\\Facades\\Route;
            use {$namespace}\\{$controller};

            // routes/web.php has no ambient prefix/middleware group, so this
            // plugin declares its own (matching how the alumni plugin's
            // self-service portal routes work).
            Route::group(['prefix' => '{$d['slug']}', 'middleware' => ['web', 'auth']], function () {
                Route::get('/', [{$controller}::class, 'index']);
            });

            PHP;
        }

        return <<<PHP
        <?php

        use Illuminate\\Support\\Facades\\Route;
        use {$namespace}\\{$controller};

        // Required from the end of routes/{$portal}.php, so this inherits
        // that portal's prefix/middleware group set up in
        // app/Providers/RouteServiceProvider.php. Using [Controller::class,
        // 'method'] array syntax bypasses that group's own ->namespace()
        // setting, so the controller can stay in the package's own namespace.

        Route::get('/{$d['slug']}', [{$controller}::class, 'index']);

        PHP;
    }

    private function controller(string $portal, array $d): string
    {
        $namespace = "{$d['vendorStudly']}\\{$d['studly']}\\Http\\Controllers\\".Str::studly($portal);

        return <<<PHP
        <?php

        namespace {$namespace};

        use App\\Http\\Controllers\\Controller;

        class {$d['studly']}Controller extends Controller
        {
            public function index()
            {
                return view('{$portal}/{$d['slug']}/index');
            }
        }

        PHP;
    }

    private function indexView(string $portal, array $d): string
    {
        if (in_array($portal, self::PORTALS_WITH_LAYOUTS, true)) {
            return <<<BLADE
            @extends('layouts.{$portal}.layout')

            @section('content')
                <div class="p-4">
                    <h1 class="admin-h1">{$d['name']}</h1>
                    <p>Hello from the {$d['name']} plugin — start building here.</p>
                </div>
            @endsection
            BLADE;
        }

        return <<<BLADE
        <div class="p-4">
            <h1>{$d['name']}</h1>
            <p>Hello from the {$d['name']} plugin — start building here.</p>
        </div>
        BLADE;
    }

    private function menuHookView(string $portal, array $d): string
    {
        return <<<BLADE
        <li class="py-3 px-3 hover:font-semibold">
            <a href="{{ url('/{$portal}/{$d['slug']}') }}" class="flex items-center">
                <span class="mx-3 whitespace-no-wrap">{$d['name']}</span>
            </a>
        </li>
        BLADE;
    }

    private function dashboardWidgetHookView(array $d): string
    {
        return <<<BLADE
        <div class="bg-white shadow px-4 py-3 my-4 max-w-md">
            <h2 class="font-semibold text-base text-gray-700 mb-3">{$d['name']}</h2>
            <p class="text-sm text-gray-600">Hello from the {$d['name']} plugin!</p>
        </div>
        BLADE;
    }

    private function toolsMenuHookView(array $d): string
    {
        return <<<BLADE
        <li class="py-3 px-3 hover:font-semibold">
            <a href="{{ url('/admin/{$d['slug']}') }}" class="flex items-center">
                <span class="mx-3 whitespace-no-wrap">{$d['name']}</span>
            </a>
        </li>
        BLADE;
    }

    private function profileTabHookView(array $d): string
    {
        return <<<BLADE
        <p class="text-sm text-gray-600">Hello from the {$d['name']} plugin! (entityId: {{ \$entityId }})</p>
        BLADE;
    }

    /**
     * This view runs on EVERY page of this plugin's portals, not just one —
     * unlike menu/dashboard-widget/profile-tab, there's no separate "which
     * page" field. Scope it to a specific page from inside the view itself
     * via request()->is(), checked FIRST, before any real work, since this
     * file executes on every page load in the portal regardless of whether
     * the condition matches.
     */
    private function beforeAfterContentHookView(array $d, string $portal, string $position): string
    {
        $examplePath = $portal === 'web' ? "{$d['slug']}*" : "{$portal}/{$d['slug']}*";

        return <<<BLADE
        @if(request()->is('{$examplePath}'))
            {{-- Defaults to this plugin's own page — narrow it to any page you want,
                 e.g. request()->is('admin/teacher/show/*'), or remove the @if entirely
                 to render on every page in this portal. --}}
            <div class="bg-white shadow px-4 py-3 my-4">
                <p class="text-sm text-gray-600">{$d['name']} — {$position}-content hook.</p>
            </div>
        @endif
        BLADE;
    }

    private function jsFile(array $d): string
    {
        $registerFn = 'register'.$d['studly'];

        return <<<JS
        export function {$registerFn}(app) {
            // Register this plugin's Vue components here, e.g.:
            // app.component('{$d['slug']}-example', () => import('./components/Example.vue').then(m => m.default));
        }

        JS;
    }
}
