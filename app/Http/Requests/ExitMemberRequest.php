<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class ExitMemberRequest extends FormRequest
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
        Validator::extend('checkcomments', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('comments'));
        });

        return [
            //
            'address' => 'required',
            'city_id' => 'required',
            'state_id' => 'required',
            'country_id' => 'required',
            'pincode' => 'required|numeric|digits:6',
            'comments' => 'required|checkcomments|max:100',
        ];
    }

    public function messages()
    {
        return [
            'address.required' => 'Address is required',
            'city_id.required' => 'City is required',
            'state_id.required' => 'State is required',
            'country_id.required' => 'Country is required',
            'pincode.required' => 'Pincode is required',
            'pincode.numeric' => 'Pincode should be numeric',
            'pincode.digits:6' => 'Pincode should be 6 digits',
            'comments.required' => 'Comments is required',
            'comments.checkcomments' => 'Enter Valid Comments',
            'comments.max:100' => 'The Comments may not be greater than 100 characters.',
        ];
    }
}
