@php
$coreMenu = [
    ['label' => 'Dashboard', 'icon' => 'fa-solid fa-gauge', 'route' => 'teacher/dashboard', 'match' => ['dashboard'], 'hoverClass' => 'hover:bg-purple-900'],
    ['label' => 'Classes', 'icon' => 'fa-solid fa-chalkboard-user', 'route' => '/teacher/standardLinks', 'match' => ['standardLinks', 'standardLink'], 'hoverClass' => 'hover:bg-purple-900'],
    ['label' => 'Home Works', 'icon' => 'fa-solid fa-book', 'route' => '/teacher/homeworks', 'match' => ['homeworks', 'homework'], 'hoverClass' => 'hover:bg-purple-900'],
    ['label' => 'Assignment', 'icon' => 'fa-solid fa-file-pen', 'route' => 'teacher/assignments', 'match' => ['assignments', 'assignment'], 'hoverClass' => 'hover:bg-purple-900'],
    ['label' => 'My Library Activity', 'icon' => 'fa-solid fa-book-open', 'route' => 'teacher/libraryactivity', 'match' => ['libraryactivity'], 'hoverClass' => 'hover:bg-purple-900'],
    ['label' => 'Calendar', 'icon' => 'fa-solid fa-calendar-days', 'route' => 'teacher/events', 'match' => ['events'], 'hoverClass' => 'hover:bg-purple-900'],
    ['label' => 'Notice Board', 'icon' => 'fa-solid fa-bullhorn', 'route' => '/teacher/notices', 'match' => ['notices', 'notice'], 'hoverClass' => 'hover:bg-purple-900'],

    ['label' => 'My Leaves', 'icon' => 'fa-solid fa-calendar-check', 'route' => 'teacher/myleaves', 'match' => ['myleaves'], 'hoverClass' => 'hover:bg-purple-900', 'visible' => ['role' => 'leave_checker']],
    ['label' => 'Student Leaves', 'icon' => 'fa-solid fa-user-clock', 'route' => 'teacher/studentLeaves', 'match' => ['studentLeaves', 'studentLeave'], 'hoverClass' => 'hover:bg-purple-900', 'visible' => ['role' => 'student_leave_checker']],

    ['label' => 'Holiday', 'icon' => 'fa-solid fa-umbrella-beach', 'route' => '/teacher/holidays', 'match' => ['holidays', 'holiday'], 'hoverClass' => 'hover:bg-purple-900'],
    ['label' => 'Lesson Plans', 'icon' => 'fa-solid fa-person-chalkboard', 'route' => 'teacher/lessonplans', 'match' => ['lessonplan', 'lessonplans'], 'hoverClass' => 'hover:bg-purple-900'],
    ['label' => 'To Do List', 'icon' => 'fa-solid fa-list-check', 'route' => '/teacher/tasks', 'match' => ['tasks', 'task'], 'hoverClass' => 'hover:bg-purple-900'],
    [
        'label' => 'Class Wall', 'icon' => 'fa-solid fa-chalkboard', 'route' => '#', 'segment' => 3, 'match' => ['pages', 'page', 'posts', 'post'], 'hoverClass' => 'hover:bg-purple-900',
        'children' => [
            ['label' => 'Pages', 'icon' => 'fa-regular fa-file', 'route' => '/teacher/classwall/pages', 'segment' => 3, 'match' => ['pages', 'page'], 'hoverClass' => 'hover:bg-purple-900'],
            ['label' => 'Posts', 'icon' => 'fa-regular fa-note-sticky', 'route' => '/teacher/classwall/posts', 'segment' => 3, 'match' => ['posts', 'post'], 'hoverClass' => 'hover:bg-purple-900'],
            ['label' => 'Feeds', 'icon' => 'fa-solid fa-rss', 'route' => '/teacher/feeds', 'match' => ['feeds', 'feed'], 'hoverClass' => 'hover:font-semibold'],
        ],
    ],
    ['label' => 'Activity Log', 'icon' => 'fa-solid fa-clock-rotate-left', 'route' => '/teacher/activity', 'match' => ['activity'], 'hoverClass' => 'hover:bg-purple-900'],
];
@endphp
<ul class="list-reset text-sm">
    @foreach($coreMenu as $item)
        <x-menu-item :item="$item" />
    @endforeach

    {{-- Leaves / Leave Approvals: label text and pending-count badge are data-driven off
         hasRole('leave_checker'), not just visibility, so this doesn't fit the plain
         array+component schema. Kept hand-authored, copied verbatim from the original
         menu.blade.php. --}}
    <li class="py-3 px-3 hover:bg-purple-900 {{Request::segment ('2') == 'leaves' ? 'active':''}} && {{Request::segment ('2') == 'leave' ? 'active':''}}">
        <a href="{{ url('teacher/leaves') }}" class="flex items-center">
            <svg class="w-5 h-5 fill-current text-white" id="Layer_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg"><g><path d="m205.182 143.679h95.259c8.284 0 15-6.716 15-15s-6.716-15-15-15h-95.259c-8.284 0-15 6.716-15 15s6.715 15 15 15z"/><path d="m366.03 0h-351.03c-8.284 0-15 6.716-15 15v482c0 8.284 6.716 15 15 15h351.03c8.284 0 15-6.716 15-15v-482c0-8.284-6.716-15-15-15zm-15 482h-321.03v-452h321.03z"/><path d="m512 363.502v-312.591c0-28.072-22.839-50.911-50.911-50.911-28.073 0-50.912 22.839-50.912 50.911v312.59c0 7.826 5.996 14.245 13.643 14.931l-12.748 35.22c-1.34 3.703-1.171 7.784.472 11.362l35.912 78.242c2.445 5.328 7.771 8.743 13.633 8.743s11.188-3.415 13.633-8.743l35.911-78.242c1.643-3.579 1.812-7.66.472-11.362l-12.748-35.22c7.647-.686 13.643-7.104 13.643-14.93zm-71.823-15v-223.596h41.823v223.595h-41.823zm20.912-318.502c11.53 0 20.911 9.381 20.911 20.911v43.995h-41.823v-43.995c0-11.53 9.381-20.911 20.912-20.911zm0 431.041-19.716-42.956 14.327-39.584h10.777l14.328 39.584z"/><path d="m90.701 157.197c2.813 2.813 6.628 4.394 10.606 4.394s7.794-1.581 10.606-4.393l45.563-45.563c5.858-5.858 5.858-15.355 0-21.213-5.857-5.858-15.355-5.858-21.213 0l-34.956 34.956-10.866-10.868c-5.856-5.858-15.354-5.858-21.213 0-5.858 5.858-5.858 15.355 0 21.213z"/><path d="m205.182 234.684h95.259c8.284 0 15-6.716 15-15s-6.716-15-15-15h-95.259c-8.284 0-15 6.716-15 15s6.715 15 15 15z"/><path d="m90.701 248.201c2.813 2.813 6.628 4.394 10.606 4.394s7.794-1.581 10.606-4.393l45.563-45.563c5.858-5.858 5.858-15.355 0-21.213-5.857-5.858-15.355-5.858-21.213 0l-34.956 34.956-10.866-10.866c-5.856-5.858-15.354-5.858-21.213 0-5.858 5.858-5.858 15.355 0 21.213z"/><path d="m205.182 325.688h95.259c8.284 0 15-6.716 15-15s-6.716-15-15-15h-95.259c-8.284 0-15 6.716-15 15s6.715 15 15 15z"/><path d="m205.182 420.178h95.259c8.284 0 15-6.716 15-15s-6.716-15-15-15h-95.259c-8.284 0-15 6.716-15 15s6.715 15 15 15z"/><path d="m90.701 339.206c2.813 2.813 6.628 4.394 10.606 4.394s7.794-1.581 10.606-4.393l45.563-45.562c5.858-5.858 5.858-15.355 0-21.213-5.857-5.858-15.355-5.857-21.213 0l-34.956 34.956-10.866-10.868c-5.856-5.858-15.354-5.858-21.213 0s-5.858 15.355 0 21.213z"/></g></svg>
            @if(Auth::user()->hasRole('leave_checker'))
                <span class="mx-2 whitespace-no-wrap">Leave Approvals <pending-count></pending-count></span>
            @else
                <span class="mx-3 whitespace-no-wrap">Leaves</span>
            @endif
        </a>
    </li>

    {{-- Plugin menu hook: any installed plugin with has_menu=true and portal=teacher
         gets its resources/views/plugins/{slug}/menu.blade.php included here automatically,
         so new plugins never require editing this file. --}}
    @foreach(\App\Models\Plugin::cachedHook('withMenuFor', 'teacher') as $installedPlugin)
        @includeIf($installedPlugin->menuViewName('teacher'))
    @endforeach
</ul>
