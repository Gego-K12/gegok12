<?php

namespace App\Http\Requests\Payroll;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class TransactionRequest extends FormRequest
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
        Validator::extend('checkpayroll', function ($attribute, $value, $parameters, $validator) {
            $user = User::find($value);
            $payrolls = $user->payrolls()->where('status', 'unpaid')->get();

            if ($payrolls->isEmpty()) {
                return false;
            } else {
                return true;
            }
        });

        $rules['staff_id'] = 'required';
        $rules['paytype'] = 'required';
        $rules['account_id'] = 'required';
        if (request('paytype') == 1) {
            $rules['staff_id'] = 'required|checkpayroll';
            $rules['payroll_id'] = 'required';
            $rules['account_id'] = 'required';
        }
        $rules['transaction_date'] = 'required|date';
        $rules['payment_method'] = 'required';
        $rules['amount'] = 'required|numeric';
        $rules['attachment'] = 'nullable|mimes:jpeg,png,jpg';
        if (request('payment_method') == 'Cheque') {
            $rules['cheque_number'] = 'required|numeric';
            $rules['cheque_date'] = 'required|date';
            $rules['clearnig_date'] = 'required|date';
            $rules['cheque_bank'] = 'required';
        }
        if (request('payment_method') == 'Bank') {
            $rules['reference_number'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'staff_id.checkpayroll' => 'No upaid payroll found.',

        ];
    }
}
