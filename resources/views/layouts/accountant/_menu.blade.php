@php
$coreMenu = [
    ['label' => 'Dashboard', 'icon' => 'fa-solid fa-gauge', 'route' => '/accountant/dashboard', 'match' => ['dashboard'], 'hoverClass' => 'hover:font-semibold'],

    ['label' => 'Calendar', 'icon' => 'fa-solid fa-calendar-days', 'route' => 'accountant/events', 'match' => ['events'], 'hoverClass' => 'hover:font-semibold'],

    [
        'label' => 'Fee Details', 'icon' => 'fa-solid fa-money-check-dollar', 'route' => '/accountant/fees',
        'match' => ['fees', 'fee'], 'hoverClass' => 'hover:font-semibold',
        'visible' => ['config' => 'gfee.enabled'],
    ],

    ['label' => 'Holiday', 'icon' => 'fa-solid fa-umbrella-beach', 'route' => 'accountant/holidays', 'match' => ['holidays', 'holiday'], 'hoverClass' => 'hover:font-semibold'],

    ['label' => 'Notice Board', 'icon' => 'fa-solid fa-bullhorn', 'route' => '/accountant/notices', 'match' => ['notices'], 'hoverClass' => 'hover:font-semibold'],

    ['label' => 'To Do List', 'icon' => 'fa-solid fa-list-check', 'route' => '/accountant/tasks', 'match' => ['task'], 'hoverClass' => 'hover:font-semibold'],

    [
        // Original's parent active-check only covered ['template','templates','payslip','payslips'],
        // omitting 'salary'/'transaction' — a pre-existing bug (the parent wouldn't highlight
        // when a child Salary/Transaction page was active). Fixed to include all four children's
        // match values, consistent with every other dropdown parent in this codebase.
        'label' => 'Payroll', 'icon' => 'fa-solid fa-sack-dollar', 'route' => '#', 'segment' => 3,
        'match' => ['template', 'templates', 'payslip', 'payslips', 'salary', 'transaction'], 'hoverClass' => 'hover:font-semibold',
        'children' => [
            // Original also OR'd in Request::segment(4) == 'templates', but every
            // /accountant/payroll/template/* route has segment(4) as an id or action
            // (list, create, {id}, ...), never the literal word — that check never
            // fired. Folded the plural into this segment(3) match array instead.
            ['label' => 'Template', 'icon' => 'fa-regular fa-file-lines', 'route' => '/accountant/payroll/template', 'segment' => 3, 'match' => ['template', 'templates'], 'hoverClass' => 'hover:font-semibold'],

            // Original also OR'd Request::segment(4) == 'salary' — same dead-check
            // situation as Template above (segment 4 is always an id/action here).
            ['label' => 'Salary', 'icon' => 'fa-solid fa-money-bill-wave', 'route' => '/accountant/payroll/salary', 'segment' => 3, 'match' => ['salary'], 'hoverClass' => 'hover:font-semibold'],

            // Original also OR'd Request::segment(4) == 'payslip' — same dead-check
            // situation. Label is "Payroll" in the source despite routing to /payslip.
            ['label' => 'Payroll', 'icon' => 'fa-solid fa-file-invoice-dollar', 'route' => '/accountant/payroll/payslip', 'segment' => 3, 'match' => ['payslip'], 'hoverClass' => 'hover:font-semibold'],

            // Original also OR'd Request::segment(4) == 'transaction' — same dead-check
            // situation.
            ['label' => 'Transaction', 'icon' => 'fa-solid fa-money-bill-transfer', 'route' => '/accountant/payroll/transaction', 'segment' => 3, 'match' => ['transaction'], 'hoverClass' => 'hover:font-semibold'],
        ],
    ],

    ['label' => 'Activity Log', 'icon' => 'fa-solid fa-clock-rotate-left', 'route' => '/accountant/activity', 'match' => ['activity'], 'hoverClass' => 'hover:font-semibold'],
];
@endphp
<ul class="list-reset text-sm">
    @foreach($coreMenu as $item)
        <x-menu-item :item="$item" />
    @endforeach

    {{-- Note: the old menu.blade.php also had a "Feed" item, but it was already
         entirely wrapped in a Blade/HTML comment ("have to enable when feed added
         in all login") and never rendered. Left out here since it wasn't part of
         the live menu; see resources/views/layouts/accountant/menu.blade.php
         (lines 9-14) if it needs to be revived later. --}}

    {{-- This portal has no plugin-menu hook (no Plugin::withMenuFor call in the
         original menu.blade.php), so none is added here. --}}
</ul>
