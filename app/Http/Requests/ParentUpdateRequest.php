<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class ParentUpdateRequest extends FormRequest
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
        Validator::extend('check_occupation', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z_~\-!@#\$%\^&*.,:(\)\s]+$/', request('sub_occupation'));
        });

        Validator::extend('check_designation', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z_~\-!@#\$%\^&*.,:(\)\s]+$/', request('designation'));
        });

        Validator::extend('check_organization_name', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z_~\-!@#\$%\^&*.,:(\)\s]+$/', request('organization_name'));
        });

        Validator::extend('alpha_spaces', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[\pL\s]+$/u', $value);
        });

        Validator::extend('check_annual_income', function ($attribute, $value, $parameters, $validator) {
            if ((strlen(request('annual_income')) > 3) && (strlen(request('annual_income')) < 10)) {
                return true;
            }

            return false;
        });

        Validator::extend('check_annual_income_value', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[0-9]+$/', request('annual_income'));
        });

        $rules =
        [
            //
            'firstname' => 'required|alpha_spaces|max:15',
            'lastname' => 'nullable|alpha_spaces|max:15',
            'alternate_no' => 'nullable|numeric|digits:10',
            'profession' => 'required',
            'relation' => 'required',
        ];

        if ((request('profession') != null) && (request('profession') != 'home_maker')) {
            $rules['sub_occupation'] = 'nullable|check_occupation|max:15';
            $rules['designation'] = 'nullable|check_designation';
            $rules['organization_name'] = 'nullable|check_organization_name';
            $rules['annual_income'] = 'required|numeric|check_annual_income|check_annual_income_value';
        }

        for ($i = 0; $i < Request('count'); $i++) {
            $rules['qualification_id'.$i] = 'nullable';
        }

        return $rules;
    }

    public function messages()
    {
        $messages =
        [
            'firstname.required' => 'First Name Is Required',
            'firstname.alpha_spaces' => 'Enter A Valid First Name',
            'firstname.max:15' => 'First Name Should Be Atmost 15 Characters',

            'lastname.alpha_spaces' => 'Enter A Valid Last Name',
            'lastname.max:15' => 'Last Name Should Be Atmost 15 characters',

            'alternate_no.numeric' => 'Alternate Number Should Be Numeric',
            'alternate_no.digits:10' => 'Alternate Number Should Be 10 Digits',
            'alternate_no.checkunique_mobile' => 'Mobile Number Already In Use. Enter Different Mobile Number',

            'profession.required' => 'Occupation Is Required',

            'relation.required' => 'Choose A Relation',

            'sub_occupation.required' => 'Sub Category Is Required',
            'sub_occupation.check_occupation' => 'Enter Valid Sub Category',
            'sub_occupation.max:15' => 'Sub Category Should Be Atmost 15 Characters',

            'designation.required' => 'Designation Is Required',
            'designation.check_designation' => 'Enter Valid Designation',

            'organization_name.required' => 'Organization Name Is Required',
            'organization_name.check_organization_name' => 'Enter Valid Organization Name',

            'annual_income.required' => 'Annual Income Is Required',
            'annual_income.numeric' => 'Annual Income Should Be Numeric',
            'annual_income.check_annual_income' => 'Annual Income Should Be Greater Than 3 Digits And Lesser Than 9 Digits',
            'annual_income.check_annual_income_value' => 'Enter Valid Annual Income',
        ];

        for ($i = 0; $i < Request('count'); $i++) {
            $messages['qualification_id'.$i.'.required'] = 'Qualification Is Required';
        }

        return $messages;
    }
}
