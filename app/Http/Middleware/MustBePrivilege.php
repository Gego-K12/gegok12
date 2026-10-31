<?php

namespace App\Http\Middleware;

use App\Helpers\SiteHelper;
use App\Models\Standard;
use Closure;

class MustBePrivilege
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
        if (config('academy_backend.enabled')) {
            app(\App\Services\AcademyContext::class)->prepare((int) $request->user()->school_id);
            return $next($request);
        }
        $academic_year = SiteHelper::getAcademicYear(\Auth::user()->school_id);
        if($academic_year != null)
        {
            $standard_count = Standard::where('school_id',\Auth::user()->school_id)->count();

            if($standard_count > 0)
            {
                return $next($request);
            }
            else
            {
                return redirect('/admin/standard/create');
            }
        }
        else
        {
            return redirect('/admin/academics');
        }

        abort(404);
    }
}
