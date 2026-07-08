<?php

namespace App\Http\Requests\Admission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class AdmissionPersonalRequest extends FormRequest
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
        Validator::extend('check_driver_name', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('driver_name'));
        });

        $rules = [
            //
            'medical_history' => 'required',
            'extra_curricular_activities' => 'required',
            'mode_of_transport' => 'required',
        ];

        if ((request('mode_of_transport') == 'auto') || (request('mode_of_transport') == 'rickshaw') || (request('mode_of_transport') == 'taxi')) {
            $rules['driver_name'] = 'required|check_driver_name';
            $rules['driver_mobile_number'] = 'required|numeric|digits:10';
        }

        return $rules;
    }

    public function messages()
    {
        return
        [
            'medical_history.required' => 'Medical History Is Required',

            'extra_curricular_activities.required' => 'Extra Curricular Activities Is Required',

            'mode_of_transport.required' => 'Mode Of Trasnport Is Required',

            'driver_name.required' => 'Driver Name Required',
            'driver_name.check_driver_name' => 'Enter Valid Driver Name',

            'driver_mobile_number.required' => 'Driver Mobile No. Required',
            'driver_mobile_number.numeric' => 'Enter Valid Mobile Number',
            'driver_mobile_number.digits:10' => 'Mobile Number Should Be 10 Digits',
        ];
    }
}
