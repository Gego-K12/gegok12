# GegoK12 Plugin System — Architecture & Developer Guide

This is the reference doc for building a plugin against this app's custom
plugin/install system. It's written to be handed to a developer *or* pasted
into an AI coding session as context for scaffolding a new plugin — it
covers the full architecture, every hook type, the install/uninstall
lifecycle, and the gotchas that have actually bitten this system in
practice.

Core files:

- `app/Models/Plugin.php` — one DB row per plugin; every hook's scoping
  query and view-name convention lives here.
- `app/Services/PluginInstaller.php` — the non-interactive install/uninstall
  executor (composer/npm/artisan, route wiring, custom_addon.js patching).
- `app/Livewire/SiteAdmin/PluginConsole.php` (+ its Blade view) — the
  operator-facing "stage an install" screen.
- `app/Console/Commands/Gegok12/NewPlugin.php` — interactive scaffolder for
  local plugin development.
- `app/Console/Commands/Gegok12/ExtractPlugin.php` — turns a local plugin
  into a standalone git repo for distribution.

---

## 1. The big picture

A plugin is a small Composer package that lives at
`custompackages/{vendor}/{slug}` during local development, and can later be
installed into any GegoK12 install via **git URL**, **uploaded zip**, or (for
local dev only) a **composer path repository**. Once installed, it can hook
into specific, pre-defined extension points in the host app — sidebar menus,
dashboard widgets, an admin "Tools" flyout, entity detail pages, and
every-page content wrapping — without ever touching the host app's own
source files.

The `plugins` DB table is the single source of truth for "what's installed
and what does it hook into." Every hook query filters on `status =
'installed'` first, so a `staged`/`failed`/`uninstalled` plugin never
renders anything.

### Install lifecycle (status flow)

```
staged → installing → installed → (uninstall_staged → uninstalling → uninstalled)
                    ↘ failed                                        ↘ failed
```

Nothing runs synchronously in a web request. `PluginConsole::stageInstall()`
only ever writes a `status = 'staged'` row; the actual composer/npm/migrate
work happens later, off-request, in the scheduled command
`gego:processplugininstalls` (`app/Console/Commands/ProcessPluginInstalls.php`),
which picks up exactly one staged (or uninstall_staged) plugin per run and
hands it to `PluginInstaller`.

**No automatic rollback.** If step 5 of `install()` fails, steps 1–4's
effects (composer.json changes, extracted files, etc.) are left in place —
retrying is expected to resume from where it left off, not start clean.
`prepareZipSource()` explicitly detects and resumes from a half-finished
extraction for exactly this reason.

---

## 2. Plugin package anatomy

```
custompackages/{vendor}/{slug}/
├── composer.json                          # standard Composer package manifest
├── plugin.json                            # this system's own manifest (see §3)
├── readme.md
├── src/
│   ├── {Slug}ServiceProvider.php          # publishes migrations/views/routes/assets
│   └── Http/Controllers/{Portal}/{Slug}Controller.php   # one per targeted portal
├── database/migrations/
├── routes/{portal}.php                    # one per targeted portal
└── resources/
    ├── views/
    │   ├── {portal}/{slug}/index.blade.php     # the plugin's own page(s)
    │   └── plugins/{slug}/                     # hook partials — see §5
    └── assets/js/g{slug}.js                    # Vue component registration entry point
```

### composer.json

Standard Composer package fields. Notably `"type": "library"`,
`"minimum-stability": "dev"`, and Laravel auto-discovery under
`extra.laravel.providers`. The host app registers this package via a
`{"type": "path", "url": "custompackages/{vendor}/{slug}"}` (or `"vcs"` for
git) entry appended to the **host app's own** `composer.json` — not the
plugin's.

### plugin.json — the manifest of record

This is the file everything else reads: `PluginInstaller::peekManifest()`
reads it (without extracting) to auto-fill the PluginConsole zip-upload
form, `validateManifest()` enforces its required keys at real install time,
and `Plugin::create()` mirrors its fields into the DB row.

| Key | Required? | Notes |
|---|---|---|
| `slug` | **required** | alpha-numeric/dash/underscore, must be globally unique |
| `name` | **required** | display name |
| `version` | **required** | e.g. `1.0.0` |
| `vendor` | **required** | e.g. `gegok12` — forms `custompackages/{vendor}/{slug}` |
| `composer_package` | **required** | e.g. `gegok12/work-permission` |
| `provider_class` | **required** | fully-qualified service provider class |
| `portal` | **required** | array or comma-separated string: `web`, `admin`, `teacher`, `student`, `api` — can target several at once |
| `seeder_class` | optional | run once via `db:seed --class=` at the end of install |
| `description` | optional | free text, informational only — no consumer reads it yet (see §9) |
| `author_name`, `author_email`, `license`, `thumbnail`, `cover_image` | optional | same — informational, future-proofing for a catalog that doesn't exist yet |
| `has_menu` | optional | see §5.1 |
| `has_dashboard_widget` | optional | see §5.2 |
| `has_tools_menu` | optional | see §5.3 (Admin portal only) |
| `has_profile_tab` + `profile_tab_label` + `profile_tab_scope` | optional | see §5.4 |
| `has_before_content` / `has_after_content` | optional | see §5.5 |

**Important asymmetry**: for a **zip** install, `plugin.json` is peeked at
upload time (auto-fills the form) and *actually validated* at install time.
For a **git** install, the code isn't fetched until install time, so there's
no manifest to read yet — the operator types every field manually into
`PluginConsole`'s form, and whatever they typed becomes the DB row (no
cross-check against the real `plugin.json` the repo eventually contains).
For a **path** install (`gegok12:newPlugin`), the scaffolder writes both the
DB row and `plugin.json` itself in the same command, so they can't drift.

### The service provider

Every scaffolded provider's `boot()` publishes four groups via
`$this->publishes([...], '{slug}-{group}')`:

```php
$this->publishes([__DIR__.'/../database/migrations/' => database_path('migrations')], '{slug}-migrations');
$this->publishes([__DIR__.'/../resources/views' => resource_path('views')], '{slug}-views');
$this->publishes([__DIR__.'/../routes/{portal}.php' => base_path('routes/g{slug}-{portal}.php')], '{slug}-routes'); // one line per portal
$this->publishes([__DIR__.'/../resources/assets/' => resource_path('assets')], '{slug}-components');
```

`PluginInstaller::publishAssets()` runs `php artisan vendor:publish
--provider={provider_class} --force` — every group publishes every time,
there's no per-group selection.

---

## 3. The install() pipeline, in order

```php
prepareZipSource() / preparePathSource() / prepareGitSource()   // register the composer repository
runComposerRequire()          // composer require {package}:{version}
publishAssets()                // vendor:publish --force
checkHookPartials()             // WARN (non-fatal) if a declared hook's view wasn't published
wireRoutes()                    // append a require guard into routes/{portal}.php per portal
patchCustomAddonJs()            // inject an import + register call into resources/assets/js/custom_addon.js
runNpmBuild()                   // npm install && npm run prod  (Mix, not Vite — no "build" script)
runMigrations()                 // php artisan migrate --force
runSeeder()                     // php artisan db:seed --class={seeder_class}, only if declared
```

`uninstall()` is the mirror image — `runComposerRemove()` (idempotent: skips
if the package isn't in `composer.json`'s `require` anymore),
`removeRepositoryEntry()`, `unwireRoutes()`, `unpatchCustomAddonJs()`,
`removeExtractedSource()` (zip installs only — deletes the
`custompackages/{vendor}/{slug}` copy), `runNpmBuild()`. **It deliberately
never touches the plugin's own database tables, data, or published
views/config** — re-installing later finds everything still there.

### wireRoutes() mechanics

For each portal the plugin targets, it appends this guard to the *host
app's* `routes/{portal}.php`:

```php
if (file_exists(base_path('routes/g{slug}-{portal}.php'))) {
    require base_path('routes/g{slug}-{portal}.php');
}
```

`unwireRoutes()` strips exactly that block back out. (Cosmetic quirk: it
leaves the blank line the block used to occupy — harmless, not worth
"fixing".)

### patchCustomAddonJs() mechanics

Inserts into `resources/assets/js/custom_addon.js`:
```js
import { register{Slug} } from './g{slug}'
```
at the top (or after the first existing import), and inserts
`{registerFn}(app)` as the first line inside
`registerCustomAddon(app) { ... }`. `unpatchCustomAddonJs()` reverses both
(same cosmetic blank-line quirk as routes).

### Safety limits

- Zip uploads: 50 MB max uncompressed, rejected on any zip-slip path entry
  (`..`, absolute paths, Windows drive prefixes).
- `VALID_PORTALS = ['web', 'admin', 'teacher', 'student', 'api']`.
- Every shell step logs its full stdout/stderr to `plugins.log` (via
  `Plugin::appendLog()`), viewable at `/plugins/{id}/log`.

---

## 4. Testability seam (for anyone extending PluginInstaller itself)

`PluginInstaller`'s constructor takes an injected `ProcessRunner` (interface
at `App\Services\Process\ProcessRunner`, real implementation
`SymfonyProcessRunner`, bound in `AppServiceProvider::register()`) instead
of constructing `Symfony\Process` directly, plus optional
`basePathOverride`/`storagePathOverride`/`resourcePathOverride` constructor
args (all `null` in production — every real call site is container-resolved
via `handle(PluginInstaller $installer)`). This lets
`tests/Feature/PluginInstallerTest.php` fake every shell command
(`Tests\Support\FakeProcessRunner`) and redirect every filesystem write into
a throwaway temp tree, instead of mutating the real project.

You don't need any of this to build a plugin — it's only relevant if you're
modifying `PluginInstaller` itself.

---

## 5. Hook reference

All hook queries live as `Plugin::scopeWith*For()` methods and are only
ever true for `status = 'installed'` rows. Every hook's own Blade partial
lives at `resources/views/plugins/{slug}/...` inside the plugin package
(published there by `vendor:publish`).

### 5.1 `has_menu` — sidebar entry

- View: `plugins.{slug}.{portal}.menu` → `resources/views/plugins/{slug}/{portal}/menu.blade.php`
- Rendered once per portal, inside that portal's own sidebar partial
  (`layouts/{admin,teacher,student}/menu.blade.php`), via:
  ```blade
  @foreach(\App\Models\Plugin::withMenuFor('{portal}')->get() as $p)
      @includeIf($p->menuViewName('{portal}'))
  @endforeach
  ```
- Portal-namespaced — a plugin targeting several portals can render
  different links/labels in each.

### 5.2 `has_dashboard_widget` — dashboard card

- View: `plugins.{slug}.{portal}.dashboard-widget`
- Rendered at the end of `{admin,teacher,student}/dashboard/dashboard.blade.php`,
  same `@foreach`/`@includeIf` shape as menu.

### 5.3 `has_tools_menu` — Admin "Tools" flyout entry

- View: `plugins.{slug}.tools-menu` (**not** portal-namespaced — Tools only
  exists in Admin)
- Rendered inside the Tools flyout `<ul>` in `layouts/admin/menu.blade.php`.

### 5.4 `has_profile_tab` — the "Additional Info" panel

This is the one entity-detail-page hook, and the only one that's *not*
portal-scoped — it's keyed by `profile_tab_scope` instead, and receives an
`$entityId`.

- View: `plugins.{slug}.profile-tab` → `resources/views/plugins/{slug}/profile-tab.blade.php`,
  included with `['entityId' => $entityId]`.
- `profile_tab_scope` allowed values: `teacher`, `staff`, `student`,
  `class`, `event`, or `both` (**`both` means teacher + staff only** — it
  predates the other scopes and was never redefined to mean "all of
  them"; there's no single value meaning "every scope").
- Rendering component: `App\Livewire\Admin\ProfileExtraTabs` (+ its view
  `resources/views/livewire/admin/profile-extra-tabs.blade.php`). It does
  **not** give each matching plugin its own tab — every installed plugin
  matching the requested scope gets one row inside a single "Additional
  Info" panel (accordion-style, first row expanded by default, sorted
  alphabetically by plugin name). This keeps a detail page's nav at
  "native tabs + 1" no matter how many plugins hook into it, instead of
  growing a new tab per plugin.
- Embedded on 5 pages today, alongside each page's own *native* tab
  component (Vue or otherwise) — never replacing it:

  | Page | File | `scope` |
  |---|---|---|
  | Teacher detail | `admin/teacher/show.blade.php` | `teacher` |
  | Staff detail | `admin/staff/show.blade.php` | `staff` |
  | Student detail | `admin/member/show.blade.php` | `student` |
  | Class detail | `admin/school/standardlinks/show_form.blade.php` | `class` |
  | Event detail | `admin/events/show.blade.php` | `event` |

  Each is a single line:
  ```blade
  @livewire('admin.profile-extra-tabs', ['entityId' => $record->id, 'scope' => '{scope}'])
  ```
  placed directly after that page's native tab component.

- To add this hook to a **new** detail page, you only need the entity's
  `id` and a new scope string — no DB migration required (`profile_tab_scope`
  is a plain `string` column, not an `enum`; see §9 for why that matters).
  Widen the `in:` validation list in `PluginConsole::rules()`
  and the `<select>` in its Blade view, plus the `choice()` list in
  `NewPlugin.php`, and drop the `@livewire(...)` line onto the page.

### 5.5 `has_before_content` / `has_after_content` — every-page wrapping

- Views: `plugins.{slug}.{portal}.before-content` / `...after-content`
- Wired into `layouts/{admin,teacher,student}/layout.blade.php`, wrapping
  `@yield('content')`:
  ```blade
  @section('base-content')
    @foreach(\App\Models\Plugin::withBeforeContentFor('{portal}')->get() as $p)
      @includeIf($p->beforeContentViewName('{portal}'))
    @endforeach

    @yield('content')

    @foreach(\App\Models\Plugin::withAfterContentFor('{portal}')->get() as $p)
      @includeIf($p->afterContentViewName('{portal}'))
    @endforeach
  @endsection
  ```
- **This runs on every single page of that portal.** There is deliberately
  no manifest field for "which page(s)" — a plugin scopes itself from
  *inside its own view*:
  ```blade
  @if(request()->is('admin/teacher/show/*'))
      {{-- your content --}}
  @endif
  ```
  **Put the condition first**, before any real work (queries, computation)
  — the view executes on every page load in the portal regardless of
  whether the condition ends up matching. This mirrors WordPress's
  `before_content`/`after_content` action-hook pattern, keyed on URL path
  instead of a named route (this app names only ~4% of its routes, so
  `Route::currentRouteName()` matching wasn't viable — `request()->is()`
  works regardless).

---

## 6. Building a new plugin, step by step

1. **Scaffold it**: `php artisan gegok12:newPlugin`. Interactive prompts,
   in order: slug (validated, uniqueness-checked), display name, vendor
   (default `gegok12`), version, description, author name/email, license
   (default `MIT`), thumbnail/cover image paths, target portal(s), then one
   confirm per hook (`has_menu`, `has_dashboard_widget`, `has_tools_menu`
   if Admin is targeted, `has_profile_tab` → label + scope,
   `has_before_content`, `has_after_content`), then who to attribute the
   install to.
2. The command writes the full package to
   `custompackages/{vendor}/{slug}/` (see §2 for the layout — including a
   working stub view for every hook you said yes to) **and** immediately
   installs it (`source_type = 'path'`) — composer require, publish,
   route-wiring, npm build, migrate, all run synchronously in your
   terminal so you see the log right away.
3. **Edit the plugin** at `custompackages/{vendor}/{slug}` — it's a normal
   Composer path-repository package; changes to PHP/Blade are picked up
   immediately, changes to `resources/assets/js/g{slug}.js` need `npm run
   dev` (or `prod`) rebuilt.
4. Re-running install isn't a thing you do here — for iterating on hooks
   or routes after the initial scaffold, edit the plugin's files directly
   and re-run whichever step changed (`php artisan vendor:publish
   --provider={provider_class} --force`, `php artisan route:cache`-adjacent
   concerns, etc.) rather than going through `PluginConsole` again.

---

## 7. Distributing a plugin

`php artisan gegok12:extractPlugin {slug}` copies
`custompackages/{vendor}/{slug}` into a standalone directory (default
`storage/app/extracted-plugins/{slug}`, override with `--to`), `git init`s
it, commits, and tags `v{version}` from the manifest. **It never pushes
anywhere on its own** — pass both `--remote` and `--push` explicitly if you
want it to. The local `custompackages/` copy (and the host app's composer
path-repository wiring) is left completely untouched, so local dev keeps
working after extraction.

Once extracted and pushed (convention: under the `Gego-K12` GitHub org,
matching where this app itself lives), it's installed elsewhere the normal
way — paste the git URL into `PluginConsole`'s "Git URL" source option.
There is no plugin registry/catalog/marketplace (deliberately not built —
see §9); discovery is manual.

---

## 8. Quick reference — every file involved

| Concern | File |
|---|---|
| DB model, all hook scopes/view-name helpers | `app/Models/Plugin.php` |
| Install/uninstall executor | `app/Services/PluginInstaller.php` |
| Shell-out seam (interface / real / fake) | `app/Services/Process/{ProcessRunner,SymfonyProcessRunner}.php`, `tests/Support/FakeProcessRunner.php` |
| Operator "stage an install" screen | `app/Livewire/SiteAdmin/PluginConsole.php` + `resources/views/livewire/site-admin/plugin-console.blade.php` |
| Scheduled install/uninstall executor | `app/Console/Commands/ProcessPluginInstalls.php` (`gego:processplugininstalls`) |
| Local dev scaffolder | `app/Console/Commands/Gegok12/NewPlugin.php` (`gegok12:newPlugin`) |
| Graduate-to-standalone-repo command | `app/Console/Commands/Gegok12/ExtractPlugin.php` (`gegok12:extractPlugin`) |
| "Additional Info" panel component | `app/Livewire/Admin/ProfileExtraTabs.php` + `resources/views/livewire/admin/profile-extra-tabs.blade.php` |
| Sidebar/dashboard/tools-menu hook includes | `resources/views/layouts/{admin,teacher,student}/menu.blade.php`, `{admin,teacher,student}/dashboard/dashboard.blade.php` |
| Before/after-content hook includes | `resources/views/layouts/{admin,teacher,student}/layout.blade.php` |
| Detail pages embedding the Additional Info panel | `admin/teacher/show.blade.php`, `admin/staff/show.blade.php`, `admin/member/show.blade.php`, `admin/school/standardlinks/show_form.blade.php`, `admin/events/show.blade.php` |
| Test coverage | `tests/Feature/PluginInstallerTest.php`, `PluginMultiPortalTest.php`, `PluginContentHooksTest.php`, `PluginContentHooksRenderingTest.php`, `ProfileExtraTabsTest.php`, `ClassDetailPageHookTest.php`, `EventDetailPageHookTest.php`, `ExtractPluginTest.php` |

---

## 9. Gotchas actually hit while building this system

- **Don't use `enum()` on the `plugins` table again.** `status`,
  `source_type`, and `profile_tab_scope` all started as MySQL `enum`
  columns, and every attempt to widen one needed a MySQL-only `ALTER TABLE
  ... MODIFY` that silently no-ops on sqlite (sqlite's enum is a `CHECK`
  constraint baked in at `CREATE TABLE` time, not a widenable column type)
  — meaning new values were completely untestable until each column was
  converted to a plain `string`. They're all `string` now; adding a new
  `profile_tab_scope` value (like `class`/`event` were) needs **zero**
  migration, just an app-layer validation-list update.
- **`profile_tab_scope = 'both'` means teacher + staff only.** A real bug
  shipped briefly during development: the "match `$scope` OR `both`"
  fallback wasn't guarded, so widening the scope list to add `student`
  meant `both`-scoped plugins silently started appearing on the student
  page too. `scopeWithProfileTabFor()` now only applies the `both`
  fallback when the query scope is `teacher` or `staff` — keep that guard
  if you add more scopes.
- **Livewire components must always render exactly one root HTML tag.**
  `ProfileExtraTabs`'s view used to be a bare `@if(...) <div>...</div>
  @endif` with nothing outside it — with zero matching plugins, that
  produces no root tag at all, which crashes
  (`RootTagMissingFromViewException`) rather than rendering nothing. Any
  new Livewire hook component needs a permanent outer wrapper element.
- **`unwireRoutes()`/`unpatchCustomAddonJs()` leave a blank line behind**
  where the removed guard/import used to be. Cosmetic only, deliberately
  left as-is.
- **Only ~18/483 routes in `routes/admin.php` are named** (similarly sparse
  in `teacher.php`/`student.php`) — never design a hook around
  `Route::currentRouteName()`; use `request()->is('path/pattern/*')`
  instead, which works regardless of naming.
- **No plugin registry/marketplace exists, on purpose.** Considered and
  deliberately scoped down to "extract to a standalone repo" only — no
  catalog UI, no hosted service. `description`/`thumbnail`/`cover_image`/
  `author_*`/`license` in `plugin.json` are pure future-proofing for if
  that ever gets built; nothing reads them today.
