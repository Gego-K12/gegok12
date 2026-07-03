<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherAddressAddRequest extends FormRequest
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
        return [
            //
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'pincode' => 'required|numeric|digits:6',
        ];
    }

    public function messages()
    {
        return
        [
            'country_id.required' => 'Country is required',

            'state_id.required' => 'State is required',

            'city_id.required' => 'City is required',

            'pincode.required' => 'Pincode is required',
            'pincode.numeric' => 'Pincode should be numeric',
            'pincode.digits:6' => 'Pincode should be 6 digits',
        ];
    }
}
