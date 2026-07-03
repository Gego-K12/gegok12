<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class TelephoneDirectoryRequest extends FormRequest
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
        Validator::extend('check_name', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('name'));
        });

        Validator::extend('check_designation', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('designation'));
        });

        return [
            //
            'name' => 'required|check_name',
            'designation' => 'required|check_designation',
            'phone_number' => 'required|numeric|digits:10',
        ];
    }

    public function messages()
    {
        return [
            //
            'name.required' => 'Name Is Required',
            'name.check_firstname' => 'Enter Valid Name',

            'designation.required' => 'Designation Is Required',
            'designation.check_designation' => 'Enter Valid Designation',

            'phone_number.required' => 'Phone Number Is Required',
            'phone_number.numeric' => 'Phone Number Should Be Numeric',
            'phone_number.digits:10' => 'Phone Number Should Be 10 Digits',
        ];
    }
}
