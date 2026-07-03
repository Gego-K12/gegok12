<?php

namespace App\Http\Requests\Admission;

use Illuminate\Foundation\Http\FormRequest;

class AdmissionFormRequest extends FormRequest
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

        // return [
        //     //
        //     'section_id'       => 'required',
        //     'payment_status'   => 'required',
        //     'fee_group_id'     => 'required',
        // ];
        $rules = [
            'section_id' => 'required',
        ];

        if (config('gfee.enabled', false)) {
            $rules['payment_status'] = 'required';
            $rules['fee_group_id'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return
        [
            'section_id.required' => 'Section Is Required',

            'fee_group_id.required' => 'Fee Type Is Required',

            'payment_status.required' => 'Payment Status Is Required',
        ];
    }
}
