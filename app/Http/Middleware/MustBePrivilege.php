<?php

namespace App\Http\Middleware;

use App\Helpers\SiteHelper;
use App\Models\Standard;
use Closure;
use Illuminate\Http\Request;

class MustBePrivilege
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $academic_year = SiteHelper::getAcademicYear(\Auth::user()->school_id);
        if ($academic_year != null) {
            $standard_count = Standard::where('school_id', \Auth::user()->school_id)->count();

            if ($standard_count > 0) {
                return $next($request);
            } else {
                return redirect('/admin/standard/create');
            }
        } else {
            return redirect('/admin/academics');
        }

        abort(404);
    }
}
