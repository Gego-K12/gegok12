<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api;

use App\Events\SinglePushEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\OTPRequest;
use App\Http\Requests\API\ResetChangePasswordRequest;
use App\Http\Requests\API\ResetPasswordRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Mail\ChangePassword;
use App\Models\Authentication;
use App\Models\User;
use App\Traits\AuthenticationProcess;
use App\Traits\ResetPasswordProcess;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Log;

class UserController extends Controller
{
    use AuthenticationProcess;
    use ResetPasswordProcess;

    public function updatetoken(Request $request)
    {
        //
        try {
            $user = User::where([['id', Auth::id()], ['school_id', Auth::user()->school_id]])->first();

            $user->platform_token = $request->device_name;

            $user->save();

            $res['message'] = 'Token Updated Successfully';

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user = User::where('id', Auth::id())->first();
            $hashedPassword = $user->password;
            if (Hash::check($request->oldpassword, $hashedPassword)) {
                $user->password = Hash::make($request->newpassword);
                $user->save();

                $success = true;
                $message = 'Password Updated Successfully';
                $error_code = 200;
            } else {
                $success = false;
                $message = 'Password Update Failed';
                $error_code = 302;
            }

            return response()->json([
                'success' => $success,
                'message' => $message,
            ], $error_code);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $user = User::where([['mobile_no', $request->mobile_no], ['usergroup_id', $request->usergroup]])->first();

            $user->tokens()->delete();

            $user->platform_token = null;

            $user->save();

            $this->createAuthentication($user, $request, 'reset');

            return response()->json([
                'success' => true,
                'message' => 'Check sms to reset the password',
            ], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function storePassword(OTPRequest $request)
    {
        //
        \DB::beginTransaction();
        try {
            // $user = User::where('mobile_no',request('mobile_no'))->first();
            $user = User::where([['mobile_no', $request->mobile_no], ['usergroup_id', $request->usergroup]])->first();
            $authentication = Authentication::where([
                ['user_id', $user->id],
                ['status', 0],
                ['type', 'reset'],
            ])->orderBy('id', 'DESC')->get();

            if ($authentication[0]['token'] == $request->password) {
                $authentication_update = Authentication::where('id', $authentication[0]['id'])->first();

                $authentication_update->status = 1;

                $authentication_update->save();

                $user_update = User::where('mobile_no', $request->mobile_no)->first();

                $user_update->is_reset = 1;
                // $user_update->password  = bcrypt($request->password);
                $user_update->save();

                \DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Password Reset Successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Password Does Not Match',
                ], 302);
            }
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }

    public function checkReset(Request $request)
    {
        try {
            // $user = User::where('mobile_no',$request->mobile_no)->first();
            $user = User::where([['mobile_no', $request->mobile_no], ['usergroup_id', $request->usergroup]])->first();

            return response()->json([
                'success' => true,
                'is_reset' => $user->is_reset,
            ], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function resetChangePassword(ResetChangePasswordRequest $request)
    {
        try {
            $user = User::where([['mobile_no', $request->mobile_no], ['usergroup_id', $request->usergroup]])->first();
            // $user = User::where('mobile_no',$request->mobile_no)->first();

            $authentication = Authentication::where([
                ['user_id', $user->id],
                ['status', 1],
                ['type', 'reset'],
            ])->orderBy('id', 'DESC')->get();

            $admin = User::where([['school_id', $user->school_id], ['usergroup_id', 3]])->first();

            if ($authentication[0]['token'] == $request->oldpassword) {
                $user->tokens()->delete();

                // Change the password
                $user->password = Hash::make($request->newpassword);
                $user->platform_token = null;
                $user->is_reset = 0;

                $user->save();

                if ($user->email != null) {
                    Mail::to($user->email)->queue(new ChangePassword($user));
                }
                $success = true;
                $message = 'Changed Password Successfully';
                $error_code = 200;

                $array = [];

                $array['school_id'] = $user->school_id;
                $array['user_id'] = $user->id;
                $array['message'] = 'Changed Password Successfully';
                $array['type'] = 'private message';

                event(new SinglePushEvent($array));
            } else {
                $success = false;
                $message = 'Change Password Failed';
                $error_code = 302;
            }

            return response()->json([
                'success' => $success,
                'message' => $message,
            ], $error_code);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
