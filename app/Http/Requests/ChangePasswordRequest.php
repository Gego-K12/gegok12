<?php

namespace App\Http\Requests;

use App\Models\User;
use Hash;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChangePasswordRequest extends FormRequest
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
        Validator::extend('checkpassword', function ($attribute, $value, $parameters, $validator) {
            $user = User::find(Auth::id());
            $hashedPassword = $user->password;

            if ($user->password != '') {
                if (Hash::check(request('oldpassword'), $hashedPassword)) {
                    return true;
                }

                return false;
            }
        });

        Validator::extend('check_new_password', function ($attribute, $value, $parameters, $validator) {
            if (request('newpassword') == request('oldpassword')) {
                return false;
            }

            return true;
        });

        return [
            'oldpassword' => 'required|checkpassword',
            'newpassword' => 'required|min:8|check_new_password',
            'confirmpassword' => 'required|min:8|same:newpassword',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'oldpassword.required' => __('userprofile.oldpassword'),
            'oldpassword.checkpassword' => __('userprofile.oldpassword_err'),

            'newpassword.required' => __('userprofile.newpassword'),
            'newpassword.min' => __('userprofile.newpassword_min'),
            'newpassword.check_new_password' => __('userprofile.check_new_password'),

            'confirmpassword.required' => __('userprofile.confirmpassword'),
            'confirmpassword.min' => __('userprofile.newpassword_min'),
            'confirmpassword.same' => __('userprofile.same_confirmpassword'),
        ];
    }
}
