@php
$coreMenu = [
    ['label' => 'Dashboard', 'icon' => 'fa-solid fa-gauge', 'route' => 'student/dashboard', 'match' => ['dashboard']],
    ['label' => 'Homework', 'icon' => 'fa-solid fa-book', 'route' => 'student/homeworks', 'match' => ['homework', 'homeworks']],
    ['label' => 'Assignment', 'icon' => 'fa-solid fa-file-pen', 'route' => 'student/assignments', 'match' => ['assignments', 'assignment']],
    ['label' => 'To Do List', 'icon' => 'fa-solid fa-list-check', 'route' => '/student/tasks', 'match' => ['tasks', 'task'], 'hoverClass' => 'hover:bg-purple-900'],
    ['label' => 'Calendar', 'icon' => 'fa-solid fa-calendar-days', 'route' => 'student/events', 'match' => ['events']],
    ['label' => 'Notice Board', 'icon' => 'fa-solid fa-bullhorn', 'route' => '/student/notices', 'match' => ['notices', 'notice']],
    ['label' => 'Holiday', 'icon' => 'fa-solid fa-umbrella-beach', 'route' => 'student/holidays', 'match' => ['holidays', 'holiday']],
    ['label' => 'My Library Activity', 'icon' => 'fa-solid fa-book-open', 'route' => '/student/libraryactivity', 'match' => ['libraryactivity']],
    [
        'label' => 'Class Wall', 'icon' => 'fa-solid fa-chalkboard', 'route' => '#', 'segment' => 3, 'match' => ['pages', 'page', 'posts', 'post'],
        'children' => [
            ['label' => 'Pages', 'icon' => 'fa-regular fa-file', 'route' => '/student/classwall/pages', 'segment' => 3, 'match' => ['pages', 'page'], 'hoverClass' => 'hover:font-semibold'],
            ['label' => 'Posts', 'icon' => 'fa-regular fa-note-sticky', 'route' => '/student/classwall/posts', 'segment' => 3, 'match' => ['posts', 'post'], 'hoverClass' => 'hover:font-semibold'],
            ['label' => 'Feeds', 'icon' => 'fa-solid fa-rss', 'route' => '/student/feeds', 'match' => ['feeds', 'feed'], 'hoverClass' => 'hover:font-semibold'],
        ],
    ],
    ['label' => 'Activity Log', 'icon' => 'fa-solid fa-clock-rotate-left', 'route' => '/student/activity', 'match' => ['activity']],
];
@endphp
<ul class="list-reset text-sm">
    @foreach($coreMenu as $item)
        <x-menu-item :item="$item" />
    @endforeach

    {{-- Plugin menu hook: any installed plugin with has_menu=true and portal=student
         gets its resources/views/plugins/{slug}/menu.blade.php included here automatically,
         so new plugins never require editing this file. --}}
    @foreach(\App\Models\Plugin::cachedHook('withMenuFor', 'student') as $installedPlugin)
        @includeIf($installedPlugin->menuViewName('student'))
    @endforeach
</ul>
