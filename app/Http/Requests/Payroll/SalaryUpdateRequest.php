<?php

namespace App\Http\Requests\Payroll;

use App\Models\Salary;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class SalaryUpdateRequest extends FormRequest
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
        Validator::extend('check_use', function ($attribute, $value, $parameters, $validator) {
            $salary = Salary::find($value);

            if (count($salary->payrolls) > 0) {
                return false;
            } else {
                return true;
            }
        });

        for ($i = 0; $i < request('payrollscount'); $i++) {

            $rules['template_item'.$i] = 'required';
            if (request('category_id'.$i) != 4) {
                $rules['amount'.$i] = 'required|numeric';
            }
        }
        $rules['salary_id'] = 'required|check_use';
        $rules['staff_id'] = 'required';
        $rules['template_id'] = 'required';
        $rules['effective_date'] = 'required|after_or_equal:'.date('Y-m-d');
        $rules['gross_salary'] = 'required|numeric';

        return $rules;
    }

    public function messages()
    {
        $messages['staff_id.required'] = 'Please select the staff';
        $messages['staff_id.checksalary'] = 'Salary structure already exists';
        $messages['template_id.required'] = 'Please select the template';
        $messages['effective_date.required'] = 'Please Enter the date';
        $messages['effective_date.after_or_equal'] = 'Please Enter the valid date';
        $messages['salary_id.check_use'] = 'Salary structure already used';

        for ($i = 0; $i < Request('payrollscount'); $i++) {
            $messages['amount'.$i.'.required'] = 'Please Enter the amount';
            $messages['amount'.$i.'.numeric'] = 'Please Enter the valid amount';
        }

        return $messages;
    }
}
