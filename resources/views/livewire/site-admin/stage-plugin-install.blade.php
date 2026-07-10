<div>
    <div class="bg-white shadow px-4 py-3 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="admin-h1">Stage a New Plugin Install</h1>
            <a href="{{ url('/plugins') }}" class="text-blue-700 text-sm font-medium">&larr; Back to Plugins</a>
        </div>

        <form wire:submit.prevent="stageInstall">
            <div class="my-3">
                <label class="tw-form-label">Source</label>
                <div class="flex gap-4 mt-1">
                    <label class="flex items-center"><input type="radio" wire:model="source_type" value="git" class="mr-1"> Git URL</label>
                    <label class="flex items-center"><input type="radio" wire:model="source_type" value="zip" class="mr-1"> Zip upload</label>
                </div>
            </div>

            @if($source_type === 'git')
                <div class="w-full lg:w-2/3 mb-4">
                    <label class="tw-form-label">Git repository URL<span class="text-red-500">*</span></label>
                    <input type="text" wire:model="git_url" class="tw-form-control w-full" placeholder="https://github.com/gego-k12/exam.git">
                    <p class="text-xs text-gray-500 mt-1">The code isn't fetched until it's actually installed, so there's no plugin.json to read yet — fill in the details below manually.</p>
                    @error('git_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            @else
                <div class="w-full lg:w-2/3 mb-4">
                    <label class="tw-form-label">Plugin zip<span class="text-red-500">*</span></label>
                    <input type="file" wire:model="zip" class="tw-form-control w-full" accept=".zip">
                    <p class="text-xs text-gray-500 mt-1">Must contain a plugin.json manifest at its root (or one level deep, e.g. a GitHub "Download ZIP").</p>
                    @error('zip') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                    <div wire:loading wire:target="zip" class="text-xs text-gray-500 mt-2">Reading plugin.json...</div>

                    @if($manifestDetected)
                        <div class="text-xs text-green-700 bg-green-50 rounded px-2 py-1 mt-2">✓ Detected from plugin.json — fields below are locked to what the zip declares. Choose a different zip to change them.</div>
                    @elseif($manifestUnreadable)
                        <div class="text-xs text-yellow-700 bg-yellow-50 rounded px-2 py-1 mt-2">Could not read a plugin.json from this zip — fill in the details below manually.</div>
                    @endif
                </div>
            @endif

            <div class="flex flex-wrap gap-4">
                <div class="w-full lg:w-1/3">
                    <label class="tw-form-label">Slug<span class="text-red-500">*</span></label>
                    <input type="text" wire:model="slug" @readonly($manifestDetected) class="tw-form-control w-full @if($manifestDetected) bg-gray-50 @endif" placeholder="e.g. exam">
                    @error('slug') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="w-full lg:w-1/3">
                    <label class="tw-form-label">Name<span class="text-red-500">*</span></label>
                    <input type="text" wire:model="name" @readonly($manifestDetected) class="tw-form-control w-full @if($manifestDetected) bg-gray-50 @endif" placeholder="e.g. Exam Module">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="w-full lg:w-1/3">
                    <label class="tw-form-label">Version<span class="text-red-500">*</span></label>
                    <input type="text" wire:model="version" @readonly($manifestDetected) class="tw-form-control w-full @if($manifestDetected) bg-gray-50 @endif" placeholder="e.g. 1.0.0 or v1.0.x-dev">
                    @error('version') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="w-full lg:w-1/3">
                    <label class="tw-form-label">Composer package<span class="text-red-500">*</span></label>
                    <input type="text" wire:model="composer_package" @readonly($manifestDetected) class="tw-form-control w-full @if($manifestDetected) bg-gray-50 @endif" placeholder="e.g. gegok12/exam">
                    @error('composer_package') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="w-full lg:w-1/3">
                    <label class="tw-form-label">Provider class<span class="text-red-500">*</span></label>
                    <input type="text" wire:model="provider_class" @readonly($manifestDetected) class="tw-form-control w-full @if($manifestDetected) bg-gray-50 @endif" placeholder="e.g. Gegok12\Exam\ExamServiceProvider">
                    @error('provider_class') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="w-full lg:w-1/3">
                    <label class="tw-form-label">Seeder class (optional)</label>
                    <input type="text" wire:model="seeder_class" @readonly($manifestDetected) class="tw-form-control w-full @if($manifestDetected) bg-gray-50 @endif" placeholder="e.g. Gegok12\Exam\Database\Seeders\ExamTableSeeder">
                    @error('seeder_class') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="w-full lg:w-1/3">
                    <label class="tw-form-label">Portal(s)<span class="text-red-500">*</span></label>
                    <select multiple wire:model="portals" @disabled($manifestDetected) class="tw-form-control w-full @if($manifestDetected) bg-gray-50 @endif">
                        <option value="admin">Admin</option>
                        <option value="teacher">Teacher</option>
                        <option value="student">Student</option>
                        <option value="web">Web (shared)</option>
                        <option value="api">API</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Cmd/Ctrl-click to select more than one — a plugin can hook into several portals at once.</p>
                    @error('portals') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    @error('portals.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="my-4">
                <label class="tw-form-label block mb-1">Hooks</label>
                <p class="text-xs text-gray-500 mb-2">If declared, the plugin must publish resources/views/plugins/{slug}/{portal}/menu.blade.php and/or dashboard-widget.blade.php for each of its portals — they're included automatically into the matching portal's sidebar/dashboard, no manual wiring needed.</p>
                <div class="flex gap-6">
                    <label class="flex items-center text-sm">
                        <input type="checkbox" wire:model="has_menu" @disabled($manifestDetected) class="mr-2"> Has menu entry
                    </label>
                    <label class="flex items-center text-sm">
                        <input type="checkbox" wire:model="has_dashboard_widget" @disabled($manifestDetected) class="mr-2"> Has dashboard widget
                    </label>
                    <label class="flex items-center text-sm">
                        <input type="checkbox" wire:model="has_tools_menu" @disabled($manifestDetected) class="mr-2"> Has Admin Tools menu entry
                    </label>
                    <label class="flex items-center text-sm">
                        <input type="checkbox" wire:model.live="has_profile_tab" @disabled($manifestDetected) class="mr-2"> Has Additional Info row (teacher/staff/student)
                    </label>
                    <label class="flex items-center text-sm">
                        <input type="checkbox" wire:model="has_before_content" @disabled($manifestDetected) class="mr-2"> Has before-content hook
                    </label>
                    <label class="flex items-center text-sm">
                        <input type="checkbox" wire:model="has_after_content" @disabled($manifestDetected) class="mr-2"> Has after-content hook
                    </label>
                </div>
                @if($has_before_content || $has_after_content)
                    <p class="text-xs text-gray-500 mt-2 mb-2">
                        Publishes resources/views/plugins/{slug}/{portal}/before-content.blade.php and/or after-content.blade.php — included on every page of each of this plugin's portals, wrapping the page's own content. There's no separate "which page" setting: scope it to a specific page from inside the view itself, e.g. <code>@@if(request()-&gt;is('admin/teacher/show/*'))</code> — check the condition first, before any real work, since the view runs on every page load in the portal.
                    </p>
                @endif
                @if($has_profile_tab)
                    <p class="text-xs text-gray-500 mt-2 mb-2">Publishes resources/views/plugins/{slug}/profile-tab.blade.php — shown as a row in the "Additional Info" panel on the Admin teacher/staff/student/class/event detail page for whichever record is being viewed.</p>
                    <div class="flex gap-4 items-start">
                        <div class="w-full lg:w-1/3">
                            <label class="tw-form-label">Row label</label>
                            <input type="text" wire:model="profile_tab_label" @disabled($manifestDetected) class="tw-form-control w-full @if($manifestDetected) bg-gray-50 @endif" placeholder="e.g. Work Permissions">
                            @error('profile_tab_label') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="w-full lg:w-1/3">
                            <label class="tw-form-label">Show on</label>
                            <select wire:model="profile_tab_scope" @disabled($manifestDetected) class="tw-form-control w-full @if($manifestDetected) bg-gray-50 @endif">
                                <option value="both">Teacher &amp; Staff</option>
                                <option value="teacher">Teacher only</option>
                                <option value="staff">Staff only</option>
                                <option value="student">Student only</option>
                                <option value="class">Class only</option>
                                <option value="event">Event only</option>
                            </select>
                        </div>
                    </div>
                @endif
            </div>

            <div class="my-6">
                <button type="submit" class="btn btn-submit blue-bg text-white rounded px-3 py-1 text-sm font-medium">Stage Install</button>
            </div>
        </form>
    </div>
</div>
