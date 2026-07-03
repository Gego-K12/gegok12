<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    use Common;
    use LogActivity;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function ChangePassword()
    {
        return view('/student/changepassword');
    }

    /**
     * Updates the password of the specified user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function updateChangePassword(ChangePasswordRequest $request)
    {
        $user = User::find(Auth::id());
        $hashedPassword = $user->password;

        if ($hashedPassword != '') {
            $user->password = Hash::make($request->newpassword);
            $user->save();

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $user,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_CHANGE_PASSWORD,
                'Changed Profile Password.'
            );
        }

        $res['message'] = __('admin_userprofile.password_update');

        return $res;
    }
}
