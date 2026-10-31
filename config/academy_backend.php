<?php

return [
    'test_only' => env('ACADEMY_TEST_ONLY', false),
    'demo_emails' => [3 => 'admin.demo@accelhub.test', 5 => 'teacher.demo@accelhub.test', 6 => 'student.demo@accelhub.test', 7 => 'parent.demo@accelhub.test'],
    'enabled' => env('ACADEMY_MODE', true),
    'levels' => 6,
    'units_per_level' => 2,
    'cache_seconds' => 300,
    'login_email_notifications' => env('ACADEMY_LOGIN_EMAILS', false),
    // These are administrative modules, not learning activities.
    'disabled_paths' => [
        'admin/academics', 'admin/academic/*',
        'admin/admissions', 'admin/admission/*',
        'admin/alumni*', 'admin/staffs', 'admin/staff/*',
        'admin/visitorlog*', 'admin/calllog*', 'admin/postalrecord*',
        'admin/category/*', 'admin/vendor/*', 'admin/location/*',
        'admin/product/*', 'admin/stockproduct/*', 'admin/purchase/*',
        'admin/sales/*', 'admin/returnorder/*',
        'admin/promotion/*', 'admin/examrules*', 'admin/exam/grade*',
        'admin/student/buspass*', 'admin/student/showbuspass/*',
        'admin/student/buspass/*', 'accountant/payroll/*',
        'library/*', 'stock/*',
    ],
];
