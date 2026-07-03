<?php

namespace App\Http\Requests\Payroll;

use App\Models\Salary;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Throwable;

class PayrollSalaryRequest extends FormRequest
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
        Validator::extend('checkformula', function ($attribute, $value, $parameters, $validator) {
            $newArray = [];
            foreach (json_decode(request('payrollskey'), true) as $k => $v) {
                foreach ($v as $key => $value) {
                    $newArray[0][$key] = $value;
                }
            }

            $total_amount = str_replace(array_keys($newArray[0]), $newArray[0], request($attribute));

            if (! preg_match("/^[0-9\s\+\-\*\/\(\)]+$/", $total_amount)) {
                return false;
            } else {

                try {
                    $el = new ExpressionLanguage;

                    $el->evaluate($total_amount);

                    return true;
                } catch (Throwable $t) {
                    return false;
                }

            }

        });
        Validator::extend('checksalary', function ($attribute, $value, $parameters, $validator) {
            $salary = Salary::where([['school_id', Auth::user()->school_id], ['staff_id', $value]])->first();

            if ($salary) {
                if ($salary->effective_date >= date('Y-m-d')) {
                    return false;
                }

                return true;
            } else {
                return true;
            }
        });

        for ($i = 0; $i < request('payrollscount'); $i++) {

            $rules['template_item'.$i] = 'required';
            if (request('category_id'.$i) != 4) {
                $rules['amount'.$i] = 'required|numeric';
            }
            if (request('category_id'.$i) == 4) {
                $rules['category_value'.$i] = 'required|checkformula';
            }
        }

        $rules['staff_id'] = 'required|checksalary';
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

        for ($i = 0; $i < Request('payrollscount'); $i++) {
            $messages['amount'.$i.'.required'] = 'Please Enter the amount';
            $messages['amount'.$i.'.numeric'] = 'Please Enter the valid amount';
        }

        return $messages;
    }
}
