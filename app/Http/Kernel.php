<?php

namespace App\Http;

use App\Http\Middleware\AdminAccountant;
use App\Http\Middleware\CheckForMaintenanceMode;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\MustBeAccountant;
use App\Http\Middleware\MustBeAlumni;
use App\Http\Middleware\MustBeLibrarian;
use App\Http\Middleware\MustBeOTP;
use App\Http\Middleware\MustBeParent;
use App\Http\Middleware\MustBePrivilege;
use App\Http\Middleware\MustBeReceptionist;
use App\Http\Middleware\MustBeSchoolAdmin;
use App\Http\Middleware\MustBeSchoolSubAdmin;
use App\Http\Middleware\MustBeSiteAdmin;
use App\Http\Middleware\MustBeSiteSubAdmin;
use App\Http\Middleware\MustBeStockKeeper;
use App\Http\Middleware\MustBeStudent;
use App\Http\Middleware\MustBeTeacher;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Laratrust\Middleware\Ability;
use Laratrust\Middleware\Permission;
use Laratrust\Middleware\Role;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Nckg\Impersonate\Impersonate;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        CheckForMaintenanceMode::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,

        TrustProxies::class,
        StartSession::class,

        Impersonate::class,

    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            // \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
        ],

        'api' => [
            EnsureFrontendRequestsAreStateful::class,
            'throttle:60,1',
            SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => Authenticate::class,
        'auth.basic' => AuthenticateWithBasicAuth::class,
        'bindings' => SubstituteBindings::class,
        'cache.headers' => SetCacheHeaders::class,
        'can' => Authorize::class,
        'guest' => RedirectIfAuthenticated::class,
        'signed' => ValidateSignature::class,
        'throttle' => ThrottleRequests::class,
        'siteadmin' => MustBeSiteAdmin::class,
        'sitesubadmin' => MustBeSiteSubAdmin::class,
        'role' => Role::class,
        'permission' => Permission::class,
        'ability' => Ability::class,

        'schooladmin' => MustBeSchoolAdmin::class,
        'schoolsubadmin' => MustBeSchoolSubAdmin::class,
        'teacher' => MustBeTeacher::class,
        'librarian' => MustBeLibrarian::class,
        'student' => MustBeStudent::class,
        'parent' => MustBeParent::class,
        'receptionist' => MustBeReceptionist::class,
        'accountant' => MustBeAccountant::class,
        'stockkeeper' => MustBeStockKeeper::class,
        'adminaccountant' => AdminAccountant::class,
        'privilegeconditions' => MustBePrivilege::class, // checks academic year and standards
        'verifyotp' => MustBeOTP::class, // verify otp while school registration
        'alumni' => MustBeAlumni::class,

    ];
}
