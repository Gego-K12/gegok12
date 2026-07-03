<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherLoginRequest;
use App\Models\User;
use App\Models\Users\TeacherUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Log;

class LoginController extends Controller
{
    //

    public $successStatus = 200;

    public $failStatus = 302;

    /**
     * login api
     *
     * @return Response
     */
    public function login(TeacherLoginRequest $request)
    {

        $validator = Validator::make($request->all(), [
            'device_id' => 'required|teacher_logoutdevice_id',
        ], [
            'teacher_logoutdevice_id' => 'Your account is currently logged onto another device. Please log out of the other device or contact your administrator',
        ])->validate();

        try {
            if (Auth::attempt(['mobile_no' => request('email'), 'password' => request('password'), 'usergroup_id' => 5])) {
                $auth_user = Auth::user();

                $token = $auth_user->createToken('gego')->plainTextToken;

                // $token  = $auth_user->createToken($request->device_name)->plainTextToken;

                $user = TeacherUser::where([['id', $auth_user->id], ['school_id', $auth_user->school_id]])->first();

                $user->platform_token = $request->device_name;

                $user->device_id = $request->device_id;

                $user->save();

                return response()->json([
                    'status' => 'success',
                    'token' => $token,
                    'id' => $user->id,
                    'user_email' => $user->email == null ? '' : $user->email,
                    'user_name' => $user->name,
                    'permissions' => Auth::user()->getRoles(),
                    'designation' => $user->getTeacherDetails()['designation'] == 'others' ? $user->getTeacherDetails()['sub_designation'] : $user->getTeacherDetails()['designation'],
                ], $this->successStatus);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            if (Auth::check()) {
                Auth()->user()->tokens()->delete();
            }

            $user = User::where([['id', Auth::id()], ['school_id', Auth::user()->school_id]])->first();

            $user->platform_token = null;

            $user->device_id = null;

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully',
            ], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function logoutDevices(Request $request)
    {

        try {
            $user = User::where([['mobile_no', $request->email], ['device_id', '!=', null], ['usergroup_id', 5]])->first();

            if ($user != null) {

                $user->tokens()->delete();

                $user->platform_token = null;

                $user->device_id = null;

                $user->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Logged out from all devices',
                ], 200);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

    }
}
