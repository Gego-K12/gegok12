@php
$coreMenu = [
    ['label' => 'Dashboard', 'icon' => 'fa-solid fa-gauge', 'route' => '/stock/dashboard', 'match' => ['dashboard'], 'hoverClass' => 'hover:bg-red-900'],
    [
        'label' => 'Stocks', 'icon' => 'fa-solid fa-boxes-stacked', 'route' => '#', 'match' => ['product', 'purchase', 'sales', 'returnorder'], 'hoverClass' => 'hover:bg-red-900',
        'children' => [
            ['label' => 'Products', 'icon' => 'fa-solid fa-box', 'route' => '/stock/products', 'match' => ['product'], 'hoverClass' => 'hover:bg-red-900'],
            ['label' => 'Purchases', 'icon' => 'fa-solid fa-bag-shopping', 'route' => '/stock/purchase/show', 'match' => ['purchase'], 'hoverClass' => 'hover:bg-red-900'],
            ['label' => 'Sales', 'icon' => 'fa-solid fa-money-bill-wave', 'route' => '/stock/sales/show', 'match' => ['sales'], 'hoverClass' => 'hover:bg-red-900'],
            ['label' => 'Returns', 'icon' => 'fa-solid fa-rotate-left', 'route' => '/stock/returnorder/show', 'match' => ['returnorder'], 'hoverClass' => 'hover:bg-red-900'],
        ],
    ],
];
@endphp
<ul class="list-reset text-sm">
    @foreach($coreMenu as $item)
        <x-menu-item :item="$item" />
    @endforeach
</ul>
