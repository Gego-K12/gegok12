<?php

namespace App\Http;

use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

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
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,

        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Session\Middleware\StartSession::class,

        \Nckg\Impersonate\Impersonate::class,

    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            //\Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\AcademyBackend::class,
        ],

        'api' => [
            EnsureFrontendRequestsAreStateful::class,
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
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
        'auth'                  =>  \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic'            =>  \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings'              =>  \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers'         =>  \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can'                   =>  \Illuminate\Auth\Middleware\Authorize::class,
        'guest'                 =>  \App\Http\Middleware\RedirectIfAuthenticated::class,
        'signed'                =>  \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle'              =>  \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'siteadmin'             =>  \App\Http\Middleware\MustBeSiteAdmin::class,
        'sitesubadmin'          =>  \App\Http\Middleware\MustBeSiteSubAdmin::class,
        'role'                  =>  \Laratrust\Middleware\Role::class,
        'permission'            =>  \Laratrust\Middleware\Permission::class,
        'ability'               =>  \Laratrust\Middleware\Ability::class,

        'schooladmin'           =>  \App\Http\Middleware\MustBeSchoolAdmin::class,
        'schoolsubadmin'        =>  \App\Http\Middleware\MustBeSchoolSubAdmin::class,
        'teacher'               =>  \App\Http\Middleware\MustBeTeacher::class,  
        'librarian'             =>  \App\Http\Middleware\MustBeLibrarian::class,  
        'student'               =>  \App\Http\Middleware\MustBeStudent::class,  
        'parent'                =>  \App\Http\Middleware\MustBeParent::class,  
        'receptionist'          =>  \App\Http\Middleware\MustBeReceptionist::class,  
        'accountant'            =>  \App\Http\Middleware\MustBeAccountant::class,
        'stockkeeper'           =>  \App\Http\Middleware\MustBeStockKeeper::class,
        'adminaccountant'       =>  \App\Http\Middleware\AdminAccountant::class,   
        'privilegeconditions'   =>  \App\Http\Middleware\MustBePrivilege::class, //checks academic year and standards  
        'verifyotp'             =>  \App\Http\Middleware\MustBeOTP::class, //verify otp while school registration   
        'alumni'                =>  \App\Http\Middleware\MustBeAlumni::class,
        
    ];
}
