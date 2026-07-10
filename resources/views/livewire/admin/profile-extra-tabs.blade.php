<div>
    @if($plugins->isNotEmpty())
        <div class="bg-white rounded-lg shadow border border-gray-100 mt-4">
            <h3 class="text-xs font-semibold text-gray-500 uppercase px-3 py-2 border-b border-gray-100">Additional Info</h3>

            @foreach($plugins as $plugin)
                <div class="border-b border-gray-100 last:border-b-0">
                    <button type="button" wire:click="toggle('{{ $plugin->slug }}')" class="w-full text-left px-3 py-2 flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">{{ $plugin->profile_tab_label }}</span>
                        <span class="text-gray-400 text-xs">{{ $expandedSlug === $plugin->slug ? '−' : '+' }}</span>
                    </button>

                    @if($expandedSlug === $plugin->slug)
                        <div class="px-3 pb-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto">
                            @includeIf($plugin->profileTabViewName(), ['entityId' => $entityId])
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
