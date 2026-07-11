@php
$coreMenu = [
    ['label' => 'Dashboard', 'icon' => 'fa-solid fa-gauge', 'route' => '/nonteaching/dashboard', 'match' => ['dashboard'], 'hoverClass' => ''],
];
@endphp
<ul class="list-reset text-sm">
    @foreach($coreMenu as $item)
        <x-menu-item :item="$item" />
    @endforeach
</ul>
