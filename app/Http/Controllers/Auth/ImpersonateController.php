<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\Common;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Log;

class ImpersonateController extends Controller
{
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function impersonate($id)
    {
        try {
            $user = User::find($id);

            $is_admin = $this->is_admin($user->id);
            if ($is_admin == false) {
                Auth::user()->setImpersonating($user->id);
            } else {
                \Session::put('Impersonate disabled for this user.');
            }

            return redirect('/teacher/dashboard');
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function librarianimpersonate($id)
    {
        try {
            $user = User::find($id);

            $is_admin = $this->is_admin($user->id);
            if ($is_admin == false) {
                Auth::user()->setImpersonating($user->id);
            } else {
                \Session::put('Impersonate disabled for this user.');
            }

            return redirect('/library/dashboard');
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function studentimpersonate($id)
    {
        try {
            $user = User::find($id);

            $is_admin = $this->is_admin($user->id);
            if ($is_admin == false) {
                Auth::user()->setImpersonating($user->id);
            } else {
                \Session::put('Impersonate disabled for this user.');
            }

            return redirect('/student/dashboard');
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function schoolAdminimpersonate($id)
    {
        try {
            $user = User::find($id);

            $is_admin = $this->is_admin($user->id);
            if ($is_admin == true) {
                Auth::user()->setImpersonating($user->id);
            } else {
                \Session::put('Impersonate disabled for this user.');
            }

            return redirect('/admin/dashboard');
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function stopImpersonate()
    {
        try {
            $user = User::where('id', Auth::user()->id)->first();
            Auth::user()->stopImpersonating();
            if ($user->usergroup_id == 5) {
                return redirect('/teacher/dashboard');
            } elseif ($user->usergroup_id == 3) {
                return redirect('/superadmin/dashboard');

                // return redirect('/admin/dashboard');
            } elseif ($user->usergroup_id == 6) {
                return redirect('/student/dashboard');
            } elseif ($user->usergroup_id == 8) {
                return redirect('/library/dashboard');
            }
            /*elseif ($user->usergroup_id == 1)
            {
                return redirect('/superadmin/dashboard');
            } */
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
