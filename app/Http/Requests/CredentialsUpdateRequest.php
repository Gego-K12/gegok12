<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CredentialsUpdateRequest extends FormRequest
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
        Validator::extend('check_unique_mob', function ($attribute, $value, $parameters, $validator) {

            $user = User::where([['school_id', Auth::user()->school_id], ['name', '!=', request('user_name')], ['usergroup_id', request('user_group')]])->where('mobile_no', 'LIKE', '%'.$value.'%')->exists();
            if ($user) {
                return false;
            }

            return true;
        });

        Validator::extend('check_unique_email', function ($attribute, $value, $parameters, $validator) {

            $user = User::where([['school_id', Auth::user()->school_id], ['name', '!=', request('user_name')]])->where('email', 'LIKE', '%'.$value.'%')->exists();
            if ($user) {
                return false;
            }

            return true;
        });

        $rules = [];

        if (request('user_group') == 7) {
            $rules['mobile_no'] = 'required|numeric|digits:10|check_unique_mob';
            $rules['email'] = 'nullable|email|check_unique_email';
        } elseif (request('user_group') == 5 || request('user_group') == 8 || request('user_group') == 8 || request('user_group') == 10 || request('user_group') == 11 || request('user_group') == 12) {
            $rules['mobile_no'] = 'required|numeric|digits:10|check_unique_mob';
            $rules['email'] = 'required|email|check_unique_email';
        } else {
            $rules['mobile_no'] = 'nullable|numeric|digits:10|check_unique_mob';
            $rules['email'] = 'nullable|email|check_unique_email';
        }

        return $rules;

    }

    public function messages()
    {
        return [
            //
            'mobile_no.check_unique_mob' => 'Mobile Number Already exists',
            'email.check_unique_email' => 'Email Already exists',
        ];
    }
}
