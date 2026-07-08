<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankDetailRequest extends FormRequest
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
            'bank_name' => 'required',
            'key' => 'required',
            'account_number' => 'required|numeric',
            'ifsc_code' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'bank_name.required' => 'Enter Bank Name',
            'key.required' => 'Enter key',
            'account_number.required' => 'Enter Account number',
            'ifsc_code.required' => 'Enter valid IFSC code',
        ];
    }
}
