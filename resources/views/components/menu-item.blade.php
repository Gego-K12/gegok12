@php
    $visible = true;
    if (isset($item['visible']['config'])) {
        $visible = config($item['visible']['config'], false);
    } elseif (isset($item['visible']['role'])) {
        $visible = (bool) optional(auth()->user())->hasRole($item['visible']['role']);
    }

    $segment = $item['segment'] ?? 2;
    $active = in_array(request()->segment($segment), $item['match'] ?? []) ? 'active' : '';
    $hoverClass = $item['hoverClass'] ?? 'hover:bg-teal-900';
@endphp
@if($visible)
@if(!empty($item['children']))
    <li class="relative py-3 px-3 {{ $hoverClass }} {{ $active }}">
        <a href="#" class="flex items-center">
            <i class="{{ $item['icon'] }} text-lg w-5 text-center"></i>
            <span class="mx-3 whitespace-no-wrap flex items-center justify-between w-10/12">
                {{ $item['label'] }}
                <img src="{{ url('images/right-arrow.svg') }}" class="w-2 h-2">
            </span>
        </a>
        <ul class="list-reset sites-sidebar">
            @foreach($item['children'] as $child)
                <x-menu-item :item="$child" />
            @endforeach
        </ul>
    </li>
@else
    <li class="py-3 px-3 {{ $hoverClass }} {{ $active }}">
        <a href="{{ url($item['route']) }}" class="flex items-center">
            <i class="{{ $item['icon'] }} text-lg w-5 text-center"></i>
            <span class="mx-3 whitespace-no-wrap">{{ $item['label'] }}</span>
        </a>
    </li>
@endif
@endif
