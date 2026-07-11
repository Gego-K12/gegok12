@php
$coreMenu = [
    ['label' => 'Dashboard', 'icon' => 'fa-solid fa-gauge', 'route' => '/alumni/dashboard', 'match' => ['dashboard', 'edit', 'add'], 'hoverClass' => 'hover:font-semibold'],
    ['label' => 'Browse Profiles', 'icon' => 'fa-solid fa-users', 'route' => '/alumni/index', 'match' => ['index', 'show'], 'hoverClass' => 'hover:font-semibold'],
    ['label' => 'Calendar', 'icon' => 'fa-solid fa-calendar-days', 'route' => 'alumni/events', 'match' => ['events'], 'hoverClass' => 'hover:font-semibold'],
];
@endphp
<ul class="list-reset text-sm">
    @foreach($coreMenu as $item)
        <x-menu-item :item="$item" />
    @endforeach
</ul>
