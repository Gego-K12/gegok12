<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MustBeTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\Auth::user()->isTeacher())
        {
            app(\App\Services\TeacherLearningGuard::class)->check($request, \Auth::user());
            return $next($request);
        }

        if (\Auth::user()->isAdmin())
        {
            return redirect('/admin/dashboard');
        }

        if (\Auth::user()->isStudent())
        {
            return redirect('/student/dashboard');
        }

        if (\Auth::user()->isParent())
        {
            return redirect('/parent/dashboard');
        }

        if (\Auth::user()->isLibrarian())
        {
            return redirect('/library/dashboard');
        }

        abort(404);
    }
}

