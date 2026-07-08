<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class LogRequest extends FormRequest
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

        Validator::extend('check_call_date', function ($attribute, $value, $parameters, $validator) {
            if (request('call_date') <= date('Y-m-d')) {
                return true;
            }

            return false;
        });

        $rules = [
            //
            // 'name'          => 'required|max:20',
            'name' => 'required|max:20',

            'call_type' => 'required',

            // 'incoming_number'=>'nullable|numeric|digits:10',

            // 'outgoing_number'=>'nullable|numeric|digits:10',

            'call_date' => 'required|date|check_call_date',

        ];

        if (request('call_type') == 'outgoing') {
            $rules['calling_purpose'] = 'required';
            $rules['outgoing_number'] = 'required|numeric|digits:10';

        } else {
            $rules['incoming_number'] = 'required|numeric|digits:10';
        }

        return $rules;

    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'name.max:20' => 'Name should not be greater than 20 characters',
            'calling_purpose.required' => 'Select Calling Purpose',
            'call_type.required' => 'Select call type',
            'incoming_number.required' => 'Incoming Number is required',
            'incoming_number.numeric' => 'Enter Valid Number',
            'incoming_number.digits:10' => 'The number should not be greater than 10 digits',
            'outgoing_number.required' => 'Outgoing Number is required',
            'outgoing_number.numeric' => 'Enter Valid Number',
            'outgoing_number.digits:10' => 'The number should not be greater than 10 digits',
            'call_date.required' => 'Select Date',
            'call_date.check_task_date' => 'Enter valid Call Date',

        ];
    }
}
