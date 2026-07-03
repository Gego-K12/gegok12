<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class PayrollUpdateRequest extends FormRequest
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
        $rules = [];

        Validator::extend('checkstatus', function ($attribute, $value, $parameters, $validator) {
            if ($value == 'paid') {
                return false;
            } else {
                return true;
            }
        });

        for ($i = 0; $i < request('payrollscount'); $i++) {

            $rules['salary_item'.$i] = 'required';
            $rules['amount'.$i] = 'required|numeric';

        }
        $rules['status'] = 'required|checkstatus';
        $rules['leave'] = 'required|numeric';
        $rules['loss_of_pay'] = 'required|numeric';
        $rules['percentage'] = 'required|numeric';

        return $rules;
    }

    public function messages()
    {

        for ($i = 0; $i < Request('payrollscount'); $i++) {
            $messages['amount'.$i.'.required'] = 'Please Enter the amount';
            $messages['amount'.$i.'.numeric'] = 'Please Enter the valid amount';
        }

        return $messages;
    }
}
