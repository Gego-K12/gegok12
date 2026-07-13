@php
$coreMenuTop = [
    ['label' => 'Dashboard', 'icon' => 'fa-solid fa-gauge', 'route' => '/library/dashboard', 'match' => ['dashboard'], 'hoverClass' => 'hover:font-semibold'],
    [
        'label' => 'Users', 'icon' => 'fa-solid fa-users', 'route' => '#', 'match' => ['students', 'student', 'teachers', 'teacher', 'staff', 'staffs'], 'hoverClass' => 'hover:font-semibold',
        'children' => [
            [
                'label' => 'Staffs', 'icon' => 'fa-solid fa-user-tie', 'route' => '#', 'match' => ['teachers', 'teacher', 'staff', 'staffs'], 'hoverClass' => 'hover:font-semibold',
                'children' => [
                    ['label' => 'Teaching', 'icon' => 'fa-solid fa-chalkboard-user', 'route' => '/library/teachers', 'match' => ['teachers', 'teacher'], 'hoverClass' => 'hover:font-semibold'],
                    ['label' => 'Non-Teaching', 'icon' => 'fa-solid fa-user-gear', 'route' => '/library/staffs', 'match' => ['staffs', 'staff'], 'hoverClass' => 'hover:font-semibold'],
                ],
            ],
            ['label' => 'Students', 'icon' => 'fa-solid fa-user-graduate', 'route' => '/library/students', 'match' => ['students', 'student'], 'hoverClass' => 'hover:font-semibold'],
        ],
    ],
    ['label' => 'Book Categories', 'icon' => 'fa-solid fa-tags', 'route' => '/library/bookscategory/index', 'match' => ['bookscategory'], 'hoverClass' => 'hover:font-semibold'],
    ['label' => 'Books', 'icon' => 'fa-solid fa-book', 'route' => '/library/books/index', 'match' => ['books'], 'hoverClass' => 'hover:font-semibold'],
];
$coreMenuBottom = [
    ['label' => 'To Do List', 'icon' => 'fa-solid fa-list-check', 'route' => '/library/tasks', 'match' => ['tasks', 'task'], 'hoverClass' => 'hover:font-semibold'],
    ['label' => 'Holiday', 'icon' => 'fa-solid fa-umbrella-beach', 'route' => 'library/holidays', 'match' => ['holidays', 'holiday'], 'hoverClass' => 'hover:font-semibold'],
    ['label' => 'Activity Log', 'icon' => 'fa-solid fa-clock-rotate-left', 'route' => '/library/activity', 'match' => ['activity'], 'hoverClass' => 'hover:font-semibold'],
];
@endphp
<ul class="list-reset text-sm">
    @foreach($coreMenuTop as $item)
        <x-menu-item :item="$item" />
    @endforeach

    {{-- Hand-authored exception: "Books Lending" / "Return Books" both live under the same
         /library/booklending/{action} segment-2 ("booklending"), and are only distinguished by
         segment(3) ("index" vs "return"). The shared menu-item component only supports checking
         a single Request::segment() position via 'segment' + 'match', so it cannot express the
         original's genuine two-segment AND condition (segment(2) == 'booklending' && segment(3) == 'index'/'return').
         Using segment(3) alone would also be a false match against unrelated routes like
         /library/bookscategory/index and /library/books/index, which likewise end in "index".
         So these two items are kept as raw Blade rather than forced into the array shape, positioned
         here to match their original place between "Books" and "To Do List". --}}
    <li class="py-3 px-3 hover:font-semibold {{ (Request::segment('2') == 'booklending' && Request::segment(3) == 'index') ? 'active' : '' }}">
        <a href="{{ url('/library/booklending/index') }}" class="flex items-center">
            <i class="fa-solid fa-atlas text-lg w-5 text-center"></i>
            <span class="mx-3 whitespace-no-wrap">Books Lending</span>
        </a>
    </li>
    <li class="py-3 px-3 hover:font-semibold {{ (Request::segment('2') == 'booklending' && Request::segment(3) == 'return') ? 'active' : '' }}">
        <a href="{{ url('/library/booklending/return') }}" class="flex items-center">
            <i class="fa-solid fa-rotate-left text-lg w-5 text-center"></i>
            <span class="mx-3 whitespace-no-wrap">Return Books</span>
        </a>
    </li>

    @foreach($coreMenuBottom as $item)
        <x-menu-item :item="$item" />
    @endforeach
</ul>
