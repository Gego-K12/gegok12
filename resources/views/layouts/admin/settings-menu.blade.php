@php
$coreMenu = [
    ['label' => 'School Details', 'icon' => 'fa-solid fa-school', 'route' => '/admin/schooldetails', 'match' => ['schooldetails'], 'hoverClass' => ''],
    ['label' => 'Academic Years', 'icon' => 'fa-solid fa-graduation-cap', 'route' => '/admin/academics', 'match' => ['academics', 'academic'], 'hoverClass' => ''],
    ['label' => 'Admissions', 'icon' => 'fa-solid fa-file-signature', 'route' => '/admin/admissions', 'match' => ['admissions', 'admission'], 'hoverClass' => ''],
    ['label' => 'Holidays List', 'icon' => 'fa-solid fa-umbrella-beach', 'route' => '/admin/holidays', 'match' => ['holidays', 'holiday'], 'hoverClass' => ''],
    ['label' => 'Exam Rules', 'icon' => 'fa-solid fa-scale-balanced', 'route' => '/admin/examrules', 'match' => ['examrules'], 'hoverClass' => '', 'visible' => ['config' => 'gexam.enabled']],
    ['label' => 'Exam Grade', 'icon' => 'fa-solid fa-ranking-star', 'route' => '/admin/exam/grade', 'segment' => 3, 'match' => ['grade'], 'hoverClass' => '', 'visible' => ['config' => 'gexam.enabled']],
    ['label' => 'Promotions', 'icon' => 'fa-solid fa-arrow-up-right-dots', 'route' => '/admin/promotion/create', 'match' => ['promotion'], 'hoverClass' => ''],
    ['label' => 'Leave Master', 'icon' => 'fa-solid fa-calendar-minus', 'route' => '/admin/leavetypes', 'match' => ['leavetypes', 'leavetype'], 'hoverClass' => ''],
    ['label' => 'SMS Templates', 'icon' => 'fa-solid fa-message', 'route' => '/admin/setting/smstemplates', 'segment' => 3, 'match' => ['smstemplates'], 'hoverClass' => ''],
    ['label' => 'Countries', 'icon' => 'fa-solid fa-earth-americas', 'route' => '/admin/setting/countries', 'segment' => 3, 'match' => ['countries', 'country'], 'hoverClass' => ''],
    ['label' => 'States', 'icon' => 'fa-solid fa-map', 'route' => '/admin/setting/states', 'segment' => 3, 'match' => ['states', 'state'], 'hoverClass' => ''],
    ['label' => 'Cities', 'icon' => 'fa-solid fa-city', 'route' => '/admin/setting/cities', 'segment' => 3, 'match' => ['cities', 'city'], 'hoverClass' => ''],
    ['label' => 'General Settings', 'icon' => 'fa-solid fa-sliders', 'route' => '/admin/settings/generalsettings', 'segment' => 3, 'match' => ['generalsettings'], 'hoverClass' => ''],
    ['label' => 'SEO Details', 'icon' => 'fa-solid fa-magnifying-glass-chart', 'route' => '/admin/settings/seodetailsettings', 'segment' => 3, 'match' => ['seodetailsettings'], 'hoverClass' => ''],
    ['label' => 'Maintenance Mode', 'icon' => 'fa-solid fa-screwdriver-wrench', 'route' => '/admin/settings/maintenancesettings', 'segment' => 3, 'match' => ['maintenancesettings'], 'hoverClass' => ''],
    ['label' => 'Standards', 'icon' => 'fa-solid fa-list-ol', 'route' => '/admin/settings/standards', 'segment' => 3, 'match' => ['standards'], 'hoverClass' => ''],
];
@endphp
<div class="w-full h-full lg:w-48 md:w-48 bg-red-800 text-white">
    <div class="min-h-full header-wrapper-b hidden lg:block md:block">
        <a href="{{ url('/admin/dashboard') }}" class="flex items-center py-3 px-3 hover:font-semibold border-b border-red-700">
            <i class="fa-solid fa-arrow-left text-lg w-5 text-center"></i>
            <span class="mx-3">Back to Admin</span>
        </a>
        <div class="px-3 py-2 text-xs uppercase tracking-wide text-red-200">Settings</div>
        <ul class="list-reset text-sm">
            @foreach($coreMenu as $item)
                <x-menu-item :item="$item" />
            @endforeach
        </ul>
    </div>
</div>
<div id="res_sidebar" class="w-full lg:w-48 md:w-48 admin-sidebar hidden lg:hidden md:hidden res_sidebar">
    <div class="min-h-full header-wrapper-b lg:hidden md:hidden bg-red-800">
        <a href="{{ url('/admin/dashboard') }}" class="flex items-center py-3 px-3 hover:font-semibold border-b border-red-700 text-white">
            <i class="fa-solid fa-arrow-left text-lg w-5 text-center"></i>
            <span class="mx-3">Back to Admin</span>
        </a>
        <div class="px-3 py-2 text-xs uppercase tracking-wide text-red-200">Settings</div>
        <ul class="list-reset text-sm">
            @foreach($coreMenu as $item)
                <x-menu-item :item="$item" />
            @endforeach
        </ul>
    </div>
</div>
