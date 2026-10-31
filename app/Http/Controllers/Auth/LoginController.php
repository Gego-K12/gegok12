<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\AuthenticatesUsers;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller implements ShouldQueue
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Determine where to redirect users after login.
     *
     * Routes users based on their role (usergroup_id):
     * - Stock Keeper (ID 12) → /stock/dashboard
     * - All other roles → /admin/dashboard (then middleware handles role-specific routing)
     *
     * @return string The redirect path
     */

    protected $redirectTo = '/admin/dashboard';

    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        if (config('academy_backend.enabled') && in_array((int) $user->usergroup_id, [3, 5, 6, 7], true)) {
            return redirect('/learning/academy');
        }
    }



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Display the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }
}
