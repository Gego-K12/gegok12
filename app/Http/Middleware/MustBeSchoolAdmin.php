<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MustBeSchoolAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (\Auth::user()->isAdmin()) {
            return $next($request);
        }

        if (\Auth::user()->isTeacher()) {
            return redirect('/teacher/dashboard');
        }

        if (\Auth::user()->isStudent()) {
            return redirect('/student/dashboard');
        }

        if (\Auth::user()->isLibrarian()) {
            return redirect('/library/dashboard');
        }

        if (\Auth::user()->isAlumni()) {
            return redirect('/alumni/dashboard');
        }

        if (\Auth::user()->isReceptionist()) {
            return redirect('/receptionist/dashboard');
        }

        if (\Auth::user()->isAccountant()) {
            return redirect('/accountant/dashboard');
        }
        if (\Auth::user()->isStockKeeper()) {
            return redirect('/stock/dashboard');
        }

        if (\Auth::user()->isNonTeachingStaff()) {
            return redirect('/nonteaching/dashboard');
        }

        if (\Auth::user()->isSiteAdmin()) {
            return redirect('/plugins');
        }

        if (\Auth::user()->isParent()) {
            return redirect('/parent/dashboard');
        }

        abort(404);
    }
}
