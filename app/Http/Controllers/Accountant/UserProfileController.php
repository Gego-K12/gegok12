<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Accountant;

use App\Http\Requests\TeacherAvatarAddRequest;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Userprofile;
use App\Traits\LogActivity;
use App\Traits\Common;
use App\Models\User;
use Exception;
use Hash;
use Log;

/**
 * Class UserProfileController
 *
 * Manages accountant user profile operations.
 *
 * Responsibilities:
 * - Change user password
 * - Update profile avatar
 * - Fetch avatar details
 * - Log profile-related activities
 *
 * @package App\Http\Controllers\Accountant
 */
class UserProfileController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Display the change password view.
     *
     * @return \Illuminate\View\View
     */
    public function ChangePassword()
    {
        return view('/accountant/changepassword');
    }
 
    /**
     * Update the password of the authenticated user.
     *
     * @param  \App\Http\Requests\ChangePasswordRequest  $request
     * @return array<string, string>|null
     */
    public function updateChangePassword(ChangePasswordRequest $request)
    {
        try
        {
            $user = User::find(Auth::id());
            $hashedPassword = $user->password;

            if ($hashedPassword != '')
            { 
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
        catch (Exception $e)
        {
            Log::info($e->getMessage());
        }
    }  

    /**
     * Get the authenticated user's avatar details.
     *
     * @return array<string, mixed>
     */
    public function getavatar()
    {
        $userprofile = Userprofile::where('user_id', Auth::id())->first();
        $array = [];

        if (Auth::user())
        {
            $array['avatar'] = $this->getFilePath($userprofile->avatar);
            $array['id'] = $userprofile->id;
        }

        return $array;
    }

    /**
     * Display the change avatar view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function changeavatar(Request $request)
    {   
        return view('/accountant/changeavatar');
    }
 
    /**
     * Update the avatar image of the authenticated user.
     *
     * Handles base64 image decoding and storage.
     *
     * @param  \App\Http\Requests\TeacherAvatarAddRequest  $request
     * @return array<string, string>|null
     */
    public function updatechangeavatar(TeacherAvatarAddRequest $request)
    {
        try
        {
            $userprofile = Userprofile::where('user_id', Auth::id())->first();

            if ($request->avatar != '')
            {
                $image_parts    = explode(";base64,", $request->avatar);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type     = $image_type_aux[1];
                $image_base64   = base64_decode($image_parts[1]);
                $location       = Auth::user()->school->slug . '/uploads/admin/teacher/avatar/';
                $file           = uniqid() . '.jpg';
                $upload_path    = $location . $file;

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
        }
        catch (Exception $e)
        {
            Log::info($e->getMessage());
        }
    }
}
