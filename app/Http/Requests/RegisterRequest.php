<?php

namespace App\Http\Requests;

use App\Models\Keyword;
use App\Models\School;
use App\Models\User;
use App\Rules\ValidRecaptcha;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class RegisterRequest extends FormRequest
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
        Validator::extend('checkunique_schoolname', function ($attribute, $value, $parameters, $validator) {
            $school = School::where('name', 'LIKE', '%'.request('school_name').'%')->exists();
            if ($school) {
                return false;
            }

            return true;
        });

        Validator::extend('check_keyword', function ($attribute, $value, $parameters, $validator) {
            $keyword = Keyword::where('name', 'LIKE', '%'.request('school_name').'%')->exists();
            if ($keyword) {
                return false;
            }

            return true;
        });

        /*Validator::extend('checkaddress',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('address')) ;
        });*/

        Validator::extend('check_name', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)]+$/', request('name'));
        });

        Validator::extend('checkunique_name', function ($attribute, $value, $parameters, $validator) {
            $user = User::where('name', 'LIKE', '%'.request('name').'%')->exists();
            if ($user) {
                return false;
            }

            return true;
        });

        Validator::extend('checkunique_email', function ($attribute, $value, $parameters, $validator) {
            $user = User::where('email', 'LIKE', '%'.request('email').'%')->exists();
            if ($user) {
                return false;
            }

            return true;
        });

        Validator::extend('checkunique_mobile', function ($attribute, $value, $parameters, $validator) {
            $user = User::where('mobile_no', '=', request('mobile_no'))->exists();
            if ($user) {
                return false;
            }

            return true;
        });

        $rules = [
            'school_name' => 'required|max:30|checkunique_schoolname|check_keyword',
            /*'address'               =>  'required|checkaddress',
            'city'                  =>  'required',
            'state'                 =>  'required',
            'pincode'               =>  'required',*/
            'name' => 'required|max:15|checkunique_name|check_name',
            'mobile_no' => 'required|numeric|digits:10|checkunique_mobile',
            'email' => 'required|email|checkunique_email',
            'password' => 'required|min:8|confirmed',
            'termsandcondn' => 'required',
            //
        ];

        $rules['g-recaptcha-response'] = ['required', new ValidRecaptcha];

        return $rules;
    }

    public function messages()
    {
        return [
            'school_name.required' => 'School Name is required',
            'school_name.max:15' => 'School Name should be atmost 30 characters',
            'school_name.checkunique_schoolname' => 'School Name already exists. Try different Name',
            'school_name.check_keyword' => 'Enter a Valid School Name',

            /*'address.required'                      =>  'Address is required',
            'address.checkaddress'                  =>  'Enter a Valid Address',

            'city.required'                         =>  'City is required',

            'state.required'                        =>  'State is required',

            'pincode.required'                      =>  'Pincode is required',*/

            'name.required' => 'User Name is required',
            'name.checkunique_name' => 'User Name already exists. Try different User Name',
            'name.check_name' => 'Enter a Valid User Name',
            'name.max:15' => 'User Name should be atmost 15 characters',

            'mobile_no.required' => 'Mobile Number is required.OTP will be sent',
            'mobile_no.numeric' => 'Mobile Number should be numeric.OTP will be sent',
            'mobile_no.digits:10' => 'Mobile Number should be 10 digits.OTP will be sent',
            'mobile_no.checkunique_mobile' => 'Mobile Number already exists. Enter different Mobile Number.OTP will be sent',

            'email.required' => 'Email ID is required.Verification Mail will be sent',
            'email.email' => 'Enter a valid Email ID.Verification Mail will be sent',
            'email.checkunique_email' => 'Email ID already exists. Enter different Email ID.Verification Mail will be sent',

            'password.required' => 'Password is required',
            'password.min:8' => 'Password should be atleast 8 digits',

            'termsandcondn.required' => 'Terms and Condition is required',

            'g-recaptcha-response.required' => 'Captcha Required',
        ];
    }
}
