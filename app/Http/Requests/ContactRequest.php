<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class ContactRequest extends FormRequest
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
        Validator::extend('checkname', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^\p{L}[\p{L} A-Za-z0-9_~\-!,@#\$%\^&*.:(\)\s]+$/u', request('fullname'));
        });

        Validator::extend('check_role', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^\p{L}[\p{L} A-Za-z_~\-!,@#\$%\^&*.:(\)\s]+$/u', request('role'));
        });

        return [
            'fullname' => 'required|max:25|checkname',
            'emailid' => 'required|email',
            'serve_at' => 'required',
            'role' => 'required|check_role',
            'contact_no' => 'required|numeric|digits:10',
            // 'message'  => 'required',
            'select' => 'required',
            // 'checkbox'  => 'required',
        ];
    }

    public function messages()
    {
        return [
            'fullname.required' => 'Name is required',
            'fullname.checkname' => 'Name format is invalid',

            'emailid.required' => 'Email ID is required',
            'emailid.email' => 'Invalid Email address',

            'serve_at.required' => 'School / Organization Name is required',

            'role.required' => 'Role is required',
            'role.check_role' => 'Invalid Role',

            'contact_no.required' => 'Contact number is required',
            'contact_no.numeric' => 'Must be a numeric number',
            'contact_no.digits' => 'The contactno must be 10 digits',

            // 'message.required'  =>'Message is required',

            'select.required' => 'Please Select',

            // 'checkbox.required' =>'Checkbox is required',
        ];
    }
}
