@php
$coreMenu = [
    ['label' => 'Plugin Management', 'icon' => 'fa-solid fa-puzzle-piece', 'route' => '/plugins', 'segment' => 1, 'match' => ['plugins'], 'hoverClass' => 'hover:font-semibold bg-gray-800'],
];
@endphp
<ul class="list-reset text-sm">
    @foreach($coreMenu as $item)
        <x-menu-item :item="$item" />
    @endforeach
</ul>
