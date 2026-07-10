<div>
@if($plugins->isNotEmpty())
    <div class="bg-white rounded-lg shadow border border-gray-100 mt-4">
        <ul class="list-reset flex text-xs profile-tab flex-wrap">
            @foreach($plugins as $plugin)
                <li @class(['px-2 mx-3 py-2', 'active' => $activeSlug === $plugin->slug])>
                    <a href="#" class="text-gray-700 font-medium" wire:click.prevent="$set('activeSlug', '{{ $plugin->slug }}')">{{ $plugin->profile_tab_label }}</a>
                </li>
            @endforeach
        </ul>

        <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3">
            @foreach($plugins as $plugin)
                @if($activeSlug === $plugin->slug)
                    @includeIf($plugin->profileTabViewName(), ['entityId' => $entityId])
                @endif
            @endforeach
        </div>
    </div>
@endif
</div>
