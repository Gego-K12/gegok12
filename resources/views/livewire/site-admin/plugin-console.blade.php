<div>
    <div class="bg-white shadow px-4 py-3" wire:poll.5s>
        <div class="flex items-center justify-between mb-4">
            <h1 class="admin-h1">Plugins</h1>
            <a href="{{ url('/plugins/stage') }}" class="btn btn-submit blue-bg text-white rounded px-3 py-1 text-sm font-medium">Stage a Plugin</a>
        </div>

        <table class="w-full text-sm">
            <thead>
                <tr class="border-b text-left text-gray-600">
                    <th class="py-2 pr-2">Slug</th>
                    <th class="py-2 px-2">Name</th>
                    <th class="py-2 px-2">Source</th>
                    <th class="py-2 px-2">Hooks</th>
                    <th class="py-2 px-2">Status</th>
                    <th class="py-2 px-2">Installed At</th>
                    <th class="py-2 px-2">Log</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plugins as $plugin)
                    <tr class="border-b align-top">
                        <td class="py-2 pr-2">{{ $plugin->slug }}</td>
                        <td class="py-2 px-2">{{ $plugin->name }}</td>
                        <td class="py-2 px-2">{{ ucfirst($plugin->source_type) }}</td>
                        <td class="py-2 px-2 text-xs text-gray-600">
                            {{ implode(' · ', array_filter([
                                $plugin->has_menu ? 'Menu' : null,
                                $plugin->has_dashboard_widget ? 'Dashboard' : null,
                                $plugin->has_tools_menu ? 'Tools' : null,
                                $plugin->has_profile_tab ? 'Additional Info' : null,
                                $plugin->has_before_content ? 'Before Content' : null,
                                $plugin->has_after_content ? 'After Content' : null,
                            ])) ?: '--' }}
                        </td>
                        <td class="py-2 px-2">
                            <span class="text-xs font-semibold rounded px-2 py-1
                                @if($plugin->status === 'installed') bg-green-100 text-green-700
                                @elseif($plugin->status === 'failed') bg-red-100 text-red-700
                                @elseif(in_array($plugin->status, ['installing', 'uninstalling'])) bg-blue-100 text-blue-700
                                @elseif($plugin->status === 'uninstalled') bg-gray-100 text-gray-600
                                @else bg-yellow-100 text-yellow-700 @endif">
                                {{ ucfirst(str_replace('_', ' ', $plugin->status)) }}
                            </span>
                        </td>
                        <td class="py-2 px-2">{{ $plugin->installed_at?->format('d M Y H:i') ?? '--' }}</td>
                        <td class="py-2 px-2">
                            @if($plugin->status === 'installed')
                                <a href="#" wire:click.prevent="uninstall({{ $plugin->id }})"
                                   onclick="return confirm('Uninstall {{ $plugin->name }}? Its code will be removed (composer remove, routes/menu un-wired). Its own database tables and data will NOT be touched.')"
                                   class="text-red-700 text-xs font-semibold">Uninstall</a>
                            @endif
                        </td>
                        <td class="py-2 px-2">
                            <a href="{{ url('/plugins/'.$plugin->id.'/log') }}" target="_blank" class="text-blue-700 text-xs font-semibold">View Log</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-4 text-gray-500 text-sm">No plugins staged yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
