@php
$coreMenu = [
    ['label' => 'Products', 'icon' => 'fa-solid fa-box-open', 'route' => '/stock/stockproduct/show', 'match' => ['stockproduct'], 'hoverClass' => ''],
    ['label' => 'Purchases', 'icon' => 'fa-solid fa-cart-shopping', 'route' => '/stock/purchase/show', 'match' => ['purchase'], 'hoverClass' => ''],
    ['label' => 'Sales', 'icon' => 'fa-solid fa-receipt', 'route' => '/stock/sales/show', 'match' => ['sales'], 'hoverClass' => ''],
    ['label' => 'Returns', 'icon' => 'fa-solid fa-rotate-left', 'route' => '/stock/returnorder/show', 'match' => ['returnorder'], 'hoverClass' => ''],
];
@endphp
<ul class="list-reset text-sm">
    @foreach($coreMenu as $item)
        <x-menu-item :item="$item" />
    @endforeach
</ul>
