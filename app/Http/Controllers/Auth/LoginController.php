<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/admin/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle post-authentication redirect based on user role
     */
    protected function authenticated($request, $user)
    {
        // Parents (7) and Students (6) - no web portal, show message
        if (in_array($user->usergroup_id, [6, 7])) {
            Auth::logout();
            $request->session()->invalidate();
            return redirect('/login')->withErrors([
                'email' => 'Please use the mobile app to access your account.'
            ]);
        }

        // Teachers
        if ($user->usergroup_id == 5) {
            return redirect('/teacher/dashboard');
        }

        // Librarians
        if ($user->usergroup_id == 8) {
            return redirect('/library/dashboard');
        }

        // Stock Keeper
        if ($user->usergroup_id == 12) {
            return redirect('/stock/dashboard');
        }

        // Admins and others -> admin dashboard
        return redirect('/admin/dashboard');
    }
}
