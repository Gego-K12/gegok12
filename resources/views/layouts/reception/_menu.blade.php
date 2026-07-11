@php
$coreMenu = [
    ['label' => 'Dashboard', 'icon' => 'fa-solid fa-gauge', 'route' => '/receptionist/dashboard', 'match' => ['dashboard'], 'hoverClass' => 'hover:bg-blue-900'],
    ['label' => 'Calendar', 'icon' => 'fa-solid fa-calendar-days', 'route' => 'receptionist/events', 'match' => ['events'], 'hoverClass' => 'hover:bg-blue-900'],
    ['label' => 'Visitor Log', 'icon' => 'fa-solid fa-id-card', 'route' => '/receptionist/visitorlog', 'match' => ['visitorlog'], 'hoverClass' => 'hover:bg-blue-900'],
    ['label' => 'Call Log', 'icon' => 'fa-solid fa-phone', 'route' => 'receptionist/calllog', 'match' => ['calllog'], 'hoverClass' => 'hover:bg-blue-900'],
    ['label' => 'Postal Record', 'icon' => 'fa-solid fa-envelope', 'route' => 'receptionist/postalrecord', 'match' => ['postalrecord'], 'hoverClass' => 'hover:bg-blue-900'],
    ['label' => 'To Do List', 'icon' => 'fa-solid fa-list-check', 'route' => '/receptionist/tasks', 'match' => ['tasks', 'task'], 'hoverClass' => 'hover:bg-blue-900'],
    ['label' => 'Telephone Directory', 'icon' => 'fa-solid fa-address-book', 'route' => '/receptionist/phonenumbers', 'match' => ['phonenumbers'], 'hoverClass' => 'hover:bg-blue-900'],
    ['label' => 'Staff Leave', 'icon' => 'fa-solid fa-calendar-minus', 'route' => '/receptionist/staff/leaves', 'segment' => 3, 'match' => ['leaves'], 'hoverClass' => 'hover:bg-blue-900'],
    ['label' => 'Holiday', 'icon' => 'fa-solid fa-umbrella-beach', 'route' => '/receptionist/holidaylist', 'match' => ['holidaylist'], 'hoverClass' => 'hover:bg-blue-900'],
    ['label' => 'Notice Board', 'icon' => 'fa-solid fa-bullhorn', 'route' => '/receptionist/notices', 'match' => ['notices'], 'hoverClass' => 'hover:bg-blue-900'],
    ['label' => 'Activity Log', 'icon' => 'fa-solid fa-clock-rotate-left', 'route' => '/receptionist/activity', 'match' => ['activity'], 'hoverClass' => 'hover:bg-blue-900'],
];
@endphp
<ul class="list-reset text-sm">
    @foreach($coreMenu as $item)
        <x-menu-item :item="$item" />
    @endforeach
</ul>
