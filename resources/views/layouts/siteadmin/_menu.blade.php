@php
$coreMenu = [
    ['label' => 'Plugin Management', 'icon' => 'fa-solid fa-puzzle-piece', 'route' => '/plugins', 'segment' => 1, 'match' => ['plugins'], 'hoverClass' => 'hover:font-semibold bg-gray-800'],
    // Master data (global, not school-scoped) moved here from admin's
    // Settings section -- see routes/siteadmin.php. segment 3 (not 2,
    // which is always 'setting' for all three) distinguishes which one
    // is active.
    ['label' => 'Countries', 'icon' => 'fa-solid fa-earth-americas', 'route' => '/siteadmin/setting/countries', 'segment' => 3, 'match' => ['countries', 'country'], 'hoverClass' => 'hover:font-semibold bg-gray-800'],
    ['label' => 'States', 'icon' => 'fa-solid fa-map', 'route' => '/siteadmin/setting/states', 'segment' => 3, 'match' => ['states', 'state'], 'hoverClass' => 'hover:font-semibold bg-gray-800'],
    ['label' => 'Cities', 'icon' => 'fa-solid fa-city', 'route' => '/siteadmin/setting/cities', 'segment' => 3, 'match' => ['cities', 'city'], 'hoverClass' => 'hover:font-semibold bg-gray-800'],
    // Upgrades (Purchase Modules/Purchase History) moved here from admin's
    // sidebar -- see routes/siteadmin.php. Default segment(2) works fine
    // here since 'addon' and 'purchase' don't collide with each other or
    // with 'setting' the way Countries/States/Cities did.
    ['label' => 'Purchase Modules', 'icon' => 'fa-solid fa-cart-shopping', 'route' => '/siteadmin/addon', 'match' => ['addon'], 'hoverClass' => 'hover:font-semibold bg-gray-800'],
    ['label' => 'Purchase History', 'icon' => 'fa-solid fa-receipt', 'route' => '/siteadmin/purchase/addon/histories', 'match' => ['purchase'], 'hoverClass' => 'hover:font-semibold bg-gray-800'],
];
@endphp
<ul class="list-reset text-sm">
    @foreach($coreMenu as $item)
        <x-menu-item :item="$item" />
    @endforeach
</ul>
