<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MustBeAccountant
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (\Auth::user()->isAccountant()) {
            return $next($request);
        }

        if (\Auth::user()->isAdmin()) {
            return redirect('/admin/dashboard');
        }

        if (\Auth::user()->isTeacher()) {
            return redirect('/teacher/dashboard');
        }

        if (\Auth::user()->isStudent()) {
            return redirect('/student/dashboard');
        }

        if (\Auth::user()->isReceptionist()) {
            return redirect('/receptionist/dashboard');
        }

        abort(404);
    }
}
