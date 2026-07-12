<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 *
 * Single source of truth for everything layouts/_common/{navigation,sidebar}.blade.php
 * need to render each portal's chrome. Loaded via App\Support\PortalConfig::for().
 *
 * Every value below was read directly out of the pre-consolidation
 * layouts/{portal}/navigation.blade.php / sidebar.blade.php files -- this is a
 * transcription, not a redesign. Known-fixed bugs (library's dashboard link,
 * reception's and accountant's impersonate-stop links) already reflect the
 * fixed values, since those were fixed on the old files first.
 */

return [

    'teacher' => [
        'urlPrefix' => 'teacher',
        'dashboardUrl' => '/teacher/dashboard',
        'toggleSidebarId' => 'res_sidebar',
        'notificationMode' => 'teacher',
        'avatarRelation' => 'userprofile',
        'avatarNullField' => 'avatar',
        'avatarPathField' => 'AvatarPath',
        'avatarWrap' => 'bare',
        'minimal' => false,
        'features' => [
            'registerLink' => true,
            'changePassword' => '/teacher/changepassword/',
            'changeAvatar' => '/teacher/changeavatar',
            'editProfile' => false,
            'impersonateStop' => true,
            'nameFallback' => false,
        ],
        'sidebar' => [
            'desktopClass' => 'bg-purple-800 text-white h-full teacher-sidebar',
            'mobileOuterClass' => 'teacher-sidebar',
            'mobileInnerClass' => 'bg-purple-700 text-white',
            'mobileId' => 'res_sidebar',
        ],
    ],

    'student' => [
        'urlPrefix' => 'student',
        'dashboardUrl' => '/student/dashboard',
        'toggleSidebarId' => 'res_sidebar',
        'notificationMode' => 'student',
        'avatarRelation' => 'userprofile',
        'avatarNullField' => 'avatar',
        'avatarPathField' => 'AvatarPath',
        'avatarWrap' => 'asset',
        'minimal' => false,
        'features' => [
            'registerLink' => true,
            'changePassword' => '/student/changepassword/',
            'changeAvatar' => false,
            'editProfile' => false,
            'impersonateStop' => true,
            'nameFallback' => false,
        ],
        'sidebar' => [
            'desktopClass' => 'text-white h-full student-sidebar',
            'mobileOuterClass' => '',
            'mobileInnerClass' => 'student-sidebar text-white',
            'mobileId' => 'res_sidebar',
        ],
    ],

    'library' => [
        'urlPrefix' => 'library',
        'dashboardUrl' => '/library/dashboard',
        'toggleSidebarId' => 'res_sidebar',
        'notificationMode' => 'library',
        'avatarRelation' => 'userprofile',
        'avatarNullField' => 'avatar',
        'avatarPathField' => 'AvatarPath',
        'avatarWrap' => 'bare',
        'minimal' => false,
        'features' => [
            'registerLink' => true,
            'changePassword' => false,
            'changeAvatar' => false,
            'editProfile' => false,
            'impersonateStop' => true,
            'nameFallback' => false,
        ],
        'sidebar' => [
            'desktopClass' => 'bg-blue-700 text-white h-full librarian-sidebar',
            'mobileOuterClass' => '',
            'mobileInnerClass' => 'bg-red-800 text-white',
            'mobileId' => 'res_sidebar',
        ],
    ],

    'reception' => [
        'urlPrefix' => 'receptionist',
        'dashboardUrl' => '/receptionist/dashboard',
        'toggleSidebarId' => 'res_sidebar',
        'notificationMode' => 'receptionist',
        'avatarRelation' => 'userprofile',
        'avatarNullField' => 'avatar',
        'avatarPathField' => 'AvatarPath',
        'avatarWrap' => 'bare',
        'minimal' => false,
        'features' => [
            'registerLink' => true,
            'changePassword' => '/receptionist/changepassword/',
            'changeAvatar' => '/receptionist/changeavatar',
            'editProfile' => false,
            'impersonateStop' => true,
            'nameFallback' => false,
        ],
        'sidebar' => [
            'desktopClass' => 'bg-blue-700 text-white h-full librarian-sidebar',
            'mobileOuterClass' => '',
            'mobileInnerClass' => 'bg-red-800 text-white',
            'mobileId' => 'res_sidebar',
        ],
    ],

    'accountant' => [
        'urlPrefix' => 'accountant',
        'dashboardUrl' => '/accountant/dashboard',
        'toggleSidebarId' => 'res_sidebar',
        'notificationMode' => 'accountant',
        'avatarRelation' => 'userprofile',
        'avatarNullField' => 'avatar',
        'avatarPathField' => 'AvatarPath',
        'avatarWrap' => 'bare',
        'minimal' => false,
        'features' => [
            'registerLink' => true,
            'changePassword' => '/accountant/changepassword/',
            'changeAvatar' => '/accountant/changeavatar',
            'editProfile' => false,
            'impersonateStop' => true,
            'nameFallback' => false,
        ],
        'sidebar' => [
            'desktopClass' => 'bg-light-green-700 text-white h-full accountant-sidebar',
            'mobileOuterClass' => '',
            'mobileInnerClass' => 'accountant-sidebar text-white',
            'mobileId' => 'res_sidebar',
        ],
    ],

    'alumni' => [
        'urlPrefix' => 'alumni',
        'dashboardUrl' => '/alumni/dashboard',
        'toggleSidebarId' => 'res_sidebar',
        'notificationMode' => 'alumni',
        'avatarRelation' => 'alumniprofile',
        'avatarNullField' => 'photo',
        'avatarPathField' => 'PhotoPath',
        'avatarWrap' => 'bare',
        'minimal' => false,
        'features' => [
            'registerLink' => true,
            'changePassword' => '/alumni/changepassword/',
            'changeAvatar' => false,
            'editProfile' => '/alumni/edit',
            'impersonateStop' => false,
            'nameFallback' => false,
        ],
        'sidebar' => [
            'desktopClass' => 'bg-blue-700 text-white h-full librarian-sidebar',
            'mobileOuterClass' => '',
            'mobileInnerClass' => 'bg-red-800 text-white',
            'mobileId' => 'res_sidebar',
        ],
    ],

    'nonteaching' => [
        'urlPrefix' => 'nonteaching',
        'dashboardUrl' => '/nonteaching/dashboard',
        'toggleSidebarId' => 'nt_sidebar',
        'notificationMode' => null,
        'avatarRelation' => 'userprofile',
        'avatarNullField' => 'avatar',
        'avatarPathField' => 'AvatarPath',
        'avatarWrap' => 'bare',
        'minimal' => false,
        'features' => [
            'registerLink' => false,
            'changePassword' => false,
            'changeAvatar' => false,
            'editProfile' => false,
            'impersonateStop' => false,
            'nameFallback' => false,
        ],
        'sidebar' => [
            'desktopClass' => 'bg-blue-700 text-white h-full librarian-sidebar',
            'mobileOuterClass' => '',
            'mobileInnerClass' => 'bg-red-800 text-white',
            'mobileId' => 'nt_sidebar',
        ],
    ],

    'siteadmin' => [
        'urlPrefix' => null,
        'dashboardUrl' => '/plugins',
        'toggleSidebarId' => 'res_sidebar',
        'notificationMode' => null,
        'avatarRelation' => null,
        'avatarNullField' => null,
        'avatarPathField' => null,
        'avatarWrap' => 'bare',
        'minimal' => true,
        'flashMessagePartial' => 'partials.message',
        'features' => [
            'registerLink' => false,
            'changePassword' => false,
            'changeAvatar' => false,
            'editProfile' => false,
            'impersonateStop' => false,
            'nameFallback' => false,
        ],
        'sidebar' => [
            'desktopClass' => 'bg-gray-900 text-white h-full',
            'mobileOuterClass' => '',
            'mobileInnerClass' => 'bg-gray-800 text-white',
            'mobileId' => 'res_sidebar',
        ],
    ],

    'admin' => [
        'urlPrefix' => 'admin',
        'dashboardUrl' => '/admin/dashboard',
        'toggleSidebarId' => 'res_sidebar',
        'notificationMode' => 'admin',
        'avatarRelation' => 'userprofile',
        'avatarNullField' => 'avatar',
        'avatarPathField' => 'AvatarPath',
        'avatarWrap' => 'url',
        'minimal' => false,
        'features' => [
            'registerLink' => true,
            'changePassword' => '/admin/changepassword',
            'changeAvatar' => '/admin/changeavatar',
            'editProfile' => '/admin/editprofile',
            'impersonateStop' => true,
            'nameFallback' => true,
            'navBarAcademicYear' => true,
            'schoolLogoFallbackImage' => '/uploads/demologo.png',
        ],
        'sidebar' => [
            'desktopClass' => 'bg-red-800 text-white h-full',
            'mobileOuterClass' => 'admin-sidebar',
            'mobileInnerClass' => 'bg-red-800',
            'mobileId' => 'res_sidebar',
        ],
    ],

    'parent' => [
        'urlPrefix' => 'parent',
        'dashboardUrl' => '/parent/dashboard',
        'toggleSidebarId' => 'res_sidebar',
        'notificationMode' => null,
        'avatarRelation' => null,
        'avatarNullField' => null,
        'avatarPathField' => null,
        'avatarWrap' => 'bare',
        // Parents have no real web portal -- /parent/dashboard only exists so
        // logging in with a parent account doesn't 404; it just tells them to
        // use the mobile app instead. Reuses siteadmin's minimal navbar shape
        // (no notification/dropdown-links, static avatar, just Logout).
        'minimal' => true,
        'features' => [
            'registerLink' => false,
            'changePassword' => false,
            'changeAvatar' => false,
            'editProfile' => false,
            'impersonateStop' => false,
            'nameFallback' => false,
        ],
        'sidebar' => [
            'desktopClass' => 'bg-gray-900 text-white h-full',
            'mobileOuterClass' => '',
            'mobileInnerClass' => 'bg-gray-800 text-white',
            'mobileId' => 'res_sidebar',
        ],
    ],

    'stock' => [
        'urlPrefix' => 'stock',
        'dashboardUrl' => '/stock/stockproduct/show',
        'toggleSidebarId' => 'res_sidebar',
        'notificationMode' => null,
        'avatarRelation' => 'userprofile',
        'avatarNullField' => 'avatar',
        'avatarPathField' => 'AvatarPath',
        'avatarWrap' => 'bare',
        'minimal' => false,
        'features' => [
            'registerLink' => false,
            'changePassword' => false,
            'changeAvatar' => false,
            'editProfile' => false,
            'impersonateStop' => false,
            'nameFallback' => false,
        ],
        'sidebar' => [
            'desktopClass' => 'bg-blue-700 text-white h-full librarian-sidebar',
            'mobileOuterClass' => '',
            'mobileInnerClass' => 'bg-red-800 text-white',
            'mobileId' => 'res_sidebar',
        ],
    ],

];
