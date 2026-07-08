<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * AdminAccountant Middleware
 *
 * Enforces access control for the admin/accountant dashboard.
 * Only Admin (ID 3) and Accountant (ID 11) users can proceed.
 * Other users are redirected to their respective dashboards:
 * - SuperAdmin (ID 1) → /superadmin/dashboard
 * - Teacher (ID 5) → /teacher/dashboard
 * - Student (ID 6) → /student/dashboard
 * - Receptionist (ID 10) → /receptionist/dashboard
 * - All others → 404 Unauthorized
 */
class AdminAccountant
{
    /**
     * Handle an incoming request.
     *
     * Routes non-admin/accountant users to appropriate dashboards
     * or returns 404 if user has no authorized route.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $userGroupId = Auth::user()->usergroup_id;

        // Allow Admin (3) and Accountant (11) to proceed
        if ($userGroupId == 11 || $userGroupId == 3) {
            return $next($request);
        }

        // Redirect other roles to their dashboards
        if ($userGroupId == 1) {
            return redirect('/superadmin/dashboard');
        }

        if ($userGroupId == 5) {
            return redirect('/teacher/dashboard');
        }

        if ($userGroupId == 6) {
            return redirect('/student/dashboard');
        }

        if ($userGroupId == 10) {
            return redirect('/receptionist/dashboard');
        }

        // Unauthorized access - no valid role
        abort(404);
    }
}
