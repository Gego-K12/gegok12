<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\TeacherAvatarAddRequest;
use App\Models\User;
use App\Models\Userprofile;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
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
        return view('/teacher/changepassword');
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

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getavatar()
    {
        $userprofile = Userprofile::where('user_id', Auth::id())->first();
        $array = [];

        if (Auth::user()) {
            $array['avatar'] = $this->getFilePath($userprofile->avatar);
            $array['id'] = $userprofile->id;
        }

        return $array;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function changeavatar(Request $request)
    {
        return view('/teacher/changeavatar');
    }

    /**
     * Updates the avatar image for specified user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function updatechangeavatar(TeacherAvatarAddRequest $request)
    {
        try {
            $userprofile = Userprofile::where('user_id', Auth::id())->first();
            if ($request->avatar != '') {
                $image_parts = explode(';base64,', $request->avatar);
                $image_type_aux = explode('image/', $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $location = Auth::user()->school->slug.'/uploads/admin/teacher/avatar/';
                $file = uniqid().'.jpg';
                $upload_path = $location.$file;
                $this->putContents($upload_path, $image_base64);

                $userprofile->avatar = $upload_path;
                $userprofile->save();

                $res['message'] = __('admin_userprofile.update_avatar');
            }

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $userprofile,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_CHANGE_AVATAR,
                $res['message']
            );

            return $res;
        } catch (Exception $e) {
        }
    }
}
