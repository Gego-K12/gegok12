<?php

namespace App\Http\Requests\API;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class ResetPasswordRequest extends FormRequest
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

        Validator::extend('check_mobile_no', function ($attribute, $value, $parameters, $validator) {
            // $user = User::where('mobile_no','=',request('mobile_no'))->exists();
            $user = $this->user;
            if ($user) {
                return true;
            }

            return false;
        });

        Validator::extend('check_active', function ($attribute, $value, $parameters, $validator) {
            // $user = User::where('mobile_no','=',request('mobile_no'))->first();
            $user = $this->user;
            if (($user->userprofile->status == 'active') && ($user->status == 'active')) {
                return true;
            }

            return false;
        });

        return [
            'mobile_no' => 'required|numeric|digits:10|check_mobile_no|check_active',
            'usergroup' => 'required',
        ];
    }

    public function messages()
    {
        // $user = User::where('mobile_no','=',request('mobile_no'))->first();
        $user = $this->user;
        if (($user->userprofile->status == 'inactive') && ($user->status == 'inactive')) {
            $status = 'User Account Suspended. Please Contact School';
        } elseif (($user->userprofile->status == 'exit') && ($user->status == 'exit')) {
            $status = 'User Account Exited. Please Contact School';
        }

        return [
            'mobile_no.required' => 'Mobile Number Is Required',
            'mobile_no.numeric' => 'Mobile Number Should Be Numeric',
            'mobile_no.digits:10' => 'Mobile Number Should Be 10 Digits',
            'mobile_no.check_mobile_no' => 'Mobile Number Is Not Registered',
            'mobile_no.check_active' => $status,
        ];
    }
}
