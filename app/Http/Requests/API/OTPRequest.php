<?php

namespace App\Http\Requests\API;

use App\Models\Authentication;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class OTPRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->user = User::where('mobile_no', request('mobile_no'))->where('usergroup_id', request('usergroup'))->first();
        Validator::extend('checkotp', function ($attribute, $value, $parameters, $validator) {
            // $user = User::where('mobile_no',request('mobile_no'))->first();
            $user = $this->user;
            $otp = Authentication::where([
                ['user_id', $user->id],
                ['status', 0],
                ['type', 'reset'],
            ])->orderBy('id', 'DESC')->get();

            if ($otp[0]['token'] != request('password')) {
                return false;
            }

            return true;
        });

        Validator::extend('checkotp_ip', function ($attribute, $value, $parameters, $validator) {
            // $user = User::where('mobile_no',request('mobile_no'))->first();
            $user = $this->user;
            $otp = Authentication::where([
                ['user_id', $user->id],
                ['status', 0],
                ['type', 'reset'],
            ])->orderBy('id', 'DESC')->get();

            if ($otp[0]['ip_address'] != $_SERVER['REMOTE_ADDR']) {
                return false;
            }

            return true;
        });

        Validator::extend('checkotp_expiry', function ($attribute, $value, $parameters, $validator) {
            // $user = User::where('mobile_no',request('mobile_no'))->first();
            $user = $this->user;
            $otp = Authentication::where([
                ['user_id', $user->id],
                ['status', 0],
                ['type', 'reset'],
            ])->orderBy('id', 'DESC')->get();

            if ($otp[0]['expires_on'] < Carbon::now()) {
                return false;
            }

            return true;
        });

        return [
            'password' => 'required|checkotp|checkotp_ip|checkotp_expiry',
            'usergroup' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'Password Is Required',
            'password.checkotp' => 'Invalid Password',
            'password.checkotp_ip' => 'Invalid IP Address',
            'password.checkotp_expiry' => 'Password Expired',
        ];
    }
}
