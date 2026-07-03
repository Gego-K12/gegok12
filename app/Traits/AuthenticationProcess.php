<?php

/**
 * Manages OTP-based authentication creation and validation for users.
 */

namespace App\Traits;

use App\Models\Authentication;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Log;

trait AuthenticationProcess
{
    use MSG91;

    /**
     * Create an OTP authentication record for a user and send the OTP.
     *
     * @param  User  $user  Target user
     * @param  \Illuminate\Http\Request|string  $request  Request object or empty string when unavailable
     * @param  string  $type  Authentication type (e.g., register, login)
     * @return void
     */
    public function createAuthentication($user, $request, $type)
    {
        try {
            if ($user->mobile_no != null) {
                $otp = rand(100000, 999999);
                $expiry = Carbon::now()->addMinutes(5);
                // $expiry = Carbon::now()->addDay(1); //for test

                $authentication = new Authentication;

                $authentication->user_id = $user->id;
                $authentication->type = $type;
                $authentication->token = $otp;
                $authentication->status = 0;
                $authentication->expires_on = $expiry;
                if ($request != '') {
                    $authentication->ip_address = $request->ip();
                } else {
                    $authentication->ip_address = \Request::ip();
                }
                $authentication->save();

                $this->getOTP($otp, $user->mobile_no);

                \Session::put('successmessage', trans('messages.otp_success_msg'));
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Check whether a registration OTP for the user has been verified.
     *
     * @param  int  $user_id  User identifier
     * @return int Status flag from authentication record (0 or 1)
     */
    public function checkAuthentication($user_id)
    {
        $authentication = Authentication::where([['user_id', $user_id], ['type', 'register']])->orderBy('id', 'DESC')->get();
        if (count($authentication) > 0) {
            return $authentication[0]->status;
        } else {
            return 0;
        }
    }
}
