<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MustBeTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\Auth::user()->isTeacher()) {
            return $next($request);
        }

        if (\Auth::user()->isAdmin()) {
            return redirect('/admin/dashboard');
        }

        if (\Auth::user()->isStudent()) {
            return redirect('/student/dashboard');
        }

        if (\Auth::user()->isParent()) {
            return redirect('/parent/dashboard');
        }

        if (\Auth::user()->isLibrarian()) {
            return redirect('/library/dashboard');
        }

        abort(404);
    }
}
