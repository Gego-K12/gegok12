<?php

namespace App\Http\Middleware;

use Closure;

class MustBeStockKeeper
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

        if(\Auth::user()->isStockKeeper())
        {
            return $next($request);  
            // return redirect('/stock/dashboard');            
        }
          
        if(\Auth::user()->isAdmin())
        {
            return redirect('/admin/dashboard');
        }

        if(\Auth::user()->isTeacher())
        {
            return redirect('/teacher/dashboard');
        }

        if(\Auth::user()->isStudent())
        {
            return redirect('/student/dashboard');
        }

        if(\Auth::user()->isReceptionist())
        {
            return redirect('/receptionist/dashboard');
        }

        if(\Auth::user()->isAccountant())
        {
            return redirect('/accountant/dashboard');
        }
        
        abort(404);
    }
}
