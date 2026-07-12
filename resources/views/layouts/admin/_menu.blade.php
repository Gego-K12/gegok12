@php
$coreMenu = [
    ['label' => 'Dashboard', 'icon' => 'fa-solid fa-gauge', 'route' => 'admin/dashboard', 'match' => ['dashboard'], 'hoverClass' => ''],

    [
        'label' => 'Users', 'icon' => 'fa-solid fa-users', 'route' => '#',
        'match' => ['students', 'student', 'parents', 'parent', 'teachers', 'teacher', 'staff', 'staffs', 'alumni', 'blocked_students'],
        'hoverClass' => '',
        'children' => [
            [
                'label' => 'Staffs', 'icon' => 'fa-solid fa-user-tie', 'route' => '#',
                'match' => ['teachers', 'teacher', 'staff', 'staffs'], 'hoverClass' => '',
                'children' => [
                    ['label' => 'Teaching', 'icon' => 'fa-solid fa-chalkboard-user', 'route' => '/admin/teachers', 'match' => ['teachers', 'teacher'], 'hoverClass' => 'hover:font-semibold'],
                    ['label' => 'Non-Teaching', 'icon' => 'fa-solid fa-user-gear', 'route' => '/admin/staffs', 'match' => ['staffs', 'staff'], 'hoverClass' => 'hover:font-semibold'],
                ],
            ],
            ['label' => 'Students', 'icon' => 'fa-solid fa-user-graduate', 'route' => '/admin/students', 'match' => ['students', 'student'], 'hoverClass' => 'hover:font-semibold'],
            ['label' => 'Parents', 'icon' => 'fa-solid fa-people-roof', 'route' => '/admin/parents', 'match' => ['parents', 'parent'], 'hoverClass' => 'hover:font-semibold'],
        ],
    ],

    ['label' => 'Classes', 'icon' => 'fa-solid fa-chalkboard', 'route' => '/admin/standardlinks', 'match' => ['standardlinks', 'standardlink', 'standardLink'], 'hoverClass' => ''],

    ['label' => 'Groups', 'icon' => 'fa-solid fa-layer-group', 'route' => '/admin/groups', 'match' => ['groups'], 'hoverClass' => ''],

    ['label' => 'Home Works', 'icon' => 'fa-solid fa-book', 'route' => '/admin/homeworks', 'match' => ['homeworks', 'homework'], 'hoverClass' => ''],

    ['label' => 'Calendar', 'icon' => 'fa-solid fa-calendar-days', 'route' => 'admin/events', 'match' => ['events', 'event'], 'hoverClass' => ''],

    ['label' => 'Notice Board', 'icon' => 'fa-solid fa-bullhorn', 'route' => '/admin/notices', 'match' => ['notices', 'notice'], 'hoverClass' => ''],

    [
        'label' => 'Messages', 'icon' => 'fa-solid fa-envelope', 'route' => '#',
        'match' => ['sentmessages', 'feedbacks', 'feedback', 'emergency'], 'hoverClass' => '',
        'children' => [
            ['label' => 'Sent Messages', 'icon' => 'fa-solid fa-paper-plane', 'route' => '/admin/sentmessages', 'match' => ['sentmessages'], 'hoverClass' => 'hover:font-semibold'],
            ['label' => 'Emergency Messages', 'icon' => 'fa-solid fa-triangle-exclamation', 'route' => '/admin/emergency', 'match' => ['emergency'], 'hoverClass' => 'hover:font-semibold'],
            ['label' => 'Feedbacks', 'icon' => 'fa-solid fa-comment-dots', 'route' => '/admin/feedbacks', 'match' => ['feedbacks', 'feedback'], 'hoverClass' => 'hover:font-semibold'],
        ],
    ],

    [
        'label' => 'Media Files', 'icon' => 'fa-solid fa-folder-open', 'route' => '#',
        'match' => ['files', 'file', 'magazines', 'magazine', 'videos'], 'hoverClass' => '',
        'children' => [
            ['label' => 'Files', 'icon' => 'fa-regular fa-file', 'route' => 'admin/files', 'match' => ['files', 'file'], 'hoverClass' => 'hover:font-semibold'],
            ['label' => 'Magazine', 'icon' => 'fa-solid fa-book-open', 'route' => '/admin/magazines', 'match' => ['magazines', 'magazine'], 'hoverClass' => 'hover:font-semibold'],
        ],
    ],

    [
        'label' => 'Records', 'icon' => 'fa-solid fa-folder', 'route' => '#',
        'match' => ['discipline', 'disciplines'], 'hoverClass' => '',
        'children' => [
            ['label' => 'Disciplinary Records', 'icon' => 'fa-solid fa-gavel', 'route' => '/admin/disciplines', 'match' => ['disciplines', 'discipline'], 'hoverClass' => 'hover:font-semibold'],
        ],
    ],

    ['label' => 'Telephone Directory', 'icon' => 'fa-solid fa-address-book', 'route' => '/admin/phonenumbers', 'match' => ['phonenumbers'], 'hoverClass' => ''],

    [
        'label' => 'Payroll', 'icon' => 'fa-solid fa-money-check', 'route' => '#',
        'segment' => 3, 'match' => ['template', 'templates', 'payslip', 'payslips', 'salary', 'transaction'], 'hoverClass' => 'hover:bg-light-green-900',
        'children' => [
            ['label' => 'Template', 'icon' => 'fa-regular fa-file-lines', 'route' => '/accountant/payroll/template', 'segment' => 3, 'match' => ['template', 'templates'], 'hoverClass' => 'hover:font-semibold'],
            ['label' => 'Salary', 'icon' => 'fa-solid fa-money-bill-wave', 'route' => '/accountant/payroll/salary', 'segment' => 3, 'match' => ['salary'], 'hoverClass' => 'hover:font-semibold'],
            ['label' => 'Payroll', 'icon' => 'fa-solid fa-file-invoice-dollar', 'route' => '/accountant/payroll/payslip', 'segment' => 3, 'match' => ['payslip'], 'hoverClass' => 'hover:font-semibold'],
            ['label' => 'Transaction', 'icon' => 'fa-solid fa-money-bill-transfer', 'route' => '/accountant/payroll/transaction', 'segment' => 3, 'match' => ['transaction'], 'hoverClass' => 'hover:font-semibold'],
        ],
    ],

    // NOTE: "Tools" (with its nested withToolsMenuFor plugin hook) is hand-authored
    // as raw Blade further below, after this array is rendered - it cannot be expressed
    // in this plain-array schema because it needs to run a foreach hook inside its own <ul>.

    ['label' => 'Reports', 'icon' => 'fa-solid fa-chart-column', 'route' => '/admin/reports', 'match' => ['reports', 'report'], 'hoverClass' => ''],

    ['label' => 'Activity Log', 'icon' => 'fa-solid fa-clock-rotate-left', 'route' => '/admin/activity', 'match' => ['activity'], 'hoverClass' => ''],

    [
        'label' => 'Upgrades', 'icon' => 'fa-solid fa-cloud-arrow-up', 'route' => '#',
        'match' => ['addon'], 'hoverClass' => '',
        'children' => [
            ['label' => 'Purchase Modules', 'icon' => 'fa-solid fa-cart-shopping', 'route' => 'admin/addon', 'match' => ['addon'], 'hoverClass' => 'hover:font-semibold'],
            ['label' => 'Purchase History', 'icon' => 'fa-solid fa-receipt', 'route' => 'admin/purchase/addon/histories', 'match' => ['purchase'], 'hoverClass' => 'hover:font-semibold'],
        ],
    ],

    [
        'label' => 'Settings', 'icon' => 'fa-solid fa-gear', 'route' => '#',
        'match' => ['schooldetails', 'academics', 'academic', 'promotion', 'leavetypes', 'leavetype', 'examrules', 'holidays', 'holiday', 'admissions', 'admission', 'setting'],
        'hoverClass' => '',
        'children' => [
            ['label' => 'School Details', 'icon' => 'fa-solid fa-school', 'route' => '/admin/schooldetails', 'match' => ['schooldetails'], 'hoverClass' => 'hover:font-semibold'],
            ['label' => 'Academic Years', 'icon' => 'fa-solid fa-graduation-cap', 'route' => 'admin/academics', 'match' => ['academics', 'academic'], 'hoverClass' => 'hover:font-semibold'],
            ['label' => 'Admissions', 'icon' => 'fa-solid fa-file-signature', 'route' => 'admin/admissions', 'match' => ['admissions', 'admission'], 'hoverClass' => 'hover:font-semibold'],
            ['label' => 'Holidays List', 'icon' => 'fa-solid fa-umbrella-beach', 'route' => 'admin/holidays', 'match' => ['holidays', 'holiday'], 'hoverClass' => 'hover:font-semibold'],
            ['label' => 'Exam Rules', 'icon' => 'fa-solid fa-scale-balanced', 'route' => '/admin/examrules', 'match' => ['examrules'], 'hoverClass' => 'hover:font-semibold', 'visible' => ['config' => 'gexam.enabled']],
            ['label' => 'Exam Grade', 'icon' => 'fa-solid fa-ranking-star', 'route' => '/admin/exam/grade', 'segment' => 3, 'match' => ['grade'], 'hoverClass' => 'hover:font-semibold', 'visible' => ['config' => 'gexam.enabled']],
            ['label' => 'Promotions', 'icon' => 'fa-solid fa-arrow-up-right-dots', 'route' => '/admin/promotion/create', 'match' => ['promotion'], 'hoverClass' => 'hover:font-semibold'],
            ['label' => 'Leave Master', 'icon' => 'fa-solid fa-calendar-minus', 'route' => '/admin/leavetypes', 'match' => ['leavetypes', 'leavetype'], 'hoverClass' => 'hover:font-semibold'],
            ['label' => 'SMS Templates', 'icon' => 'fa-solid fa-message', 'route' => '/admin/setting/smstemplates', 'segment' => 3, 'match' => ['smstemplates'], 'hoverClass' => 'hover:font-semibold'],
            ['label' => 'Countries', 'icon' => 'fa-solid fa-earth-americas', 'route' => '/admin/setting/countries', 'segment' => 3, 'match' => ['countries', 'country'], 'hoverClass' => 'hover:font-semibold'],
            ['label' => 'States', 'icon' => 'fa-solid fa-map', 'route' => '/admin/setting/states', 'segment' => 3, 'match' => ['states', 'state'], 'hoverClass' => 'hover:font-semibold'],
            ['label' => 'Cities', 'icon' => 'fa-solid fa-city', 'route' => '/admin/setting/cities', 'segment' => 3, 'match' => ['cities', 'city'], 'hoverClass' => 'hover:font-semibold'],
            // Bug fix: original checked segment(3) in ['cities','city'] here (copy-pasted from the
            // Cities item above), so this item could never actually show as active. Corrected to
            // match its own route segment.
            ['label' => 'Settings', 'icon' => 'fa-solid fa-sliders', 'route' => '/admin/settings/generalsettings', 'segment' => 3, 'match' => ['generalsettings'], 'hoverClass' => 'hover:font-semibold'],
            ['label' => 'Standards', 'icon' => 'fa-solid fa-list-ol', 'route' => '/admin/settings/standards', 'segment' => 3, 'match' => ['standards'], 'hoverClass' => 'hover:font-semibold'],
        ],
    ],

];
@endphp
<ul class="list-reset text-sm">
    @foreach($coreMenu as $item)
        <x-menu-item :item="$item" />
    @endforeach

    {{-- Hand-authored exception: "Tools" cannot be expressed in the plain-array schema above
         because it needs to run the withToolsMenuFor plugin hook inside its own <ul>, nested
         one level in. Kept verbatim from the original layouts/admin/menu.blade.php. --}}
    @php
      $toolsClass='';
      $toolsArray=array('add','register');
      if(\Request()->segment('2') == 'attendance' && in_array(\Request()->segment('4'), $toolsArray))
      {
        $toolsClass='active';
      }
    @endphp
     <li class="relative py-3 px-3 hover:bg-light-green-900 {{$toolsClass}}">
     <a href="#" class="flex items-center">
            <svg class="w-5 h-5 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/></svg>
            <span class="mx-3 whitespace-no-wrap flex items-center justify-between w-10/12">Tools <img src="{{url('images/right-arrow.svg')}}" class="w-2 h-2"> </span>
        </a>
      <ul class="list-reset sites-sidebar bottom-0" style="top: auto;">
        <li class="py-3 px-3 hover:font-semibold {{Request::segment ('4') == 'register' ? 'active':''}}">
          <a href="{{url('/admin/attendance/staff/register')}}" class="flex items-center">
            <svg class="w-5 h-5 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/></svg>
            <span class="mx-3 whitespace-no-wrap">Staff Attendance Register</span>
          </a>
        </li>

        {{-- Plugin Tools-menu hook: any installed plugin with has_tools_menu=true
             and portal including admin gets its resources/views/plugins/{slug}/tools-menu.blade.php
             included here automatically, so new plugins never require editing this file. --}}
        @foreach(\App\Models\Plugin::withToolsMenuFor('admin')->get() as $installedPlugin)
            @includeIf($installedPlugin->toolsMenuViewName())
        @endforeach
      </ul>
    </li>

    {{-- Plugin menu hook: any installed plugin with has_menu=true and portal=admin
         gets its resources/views/plugins/{slug}/menu.blade.php included here automatically,
         so new plugins never require editing this file. --}}
    @foreach(\App\Models\Plugin::withMenuFor('admin')->get() as $installedPlugin)
        @includeIf($installedPlugin->menuViewName('admin'))
    @endforeach
</ul>
