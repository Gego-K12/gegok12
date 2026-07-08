<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class ParentAddRequest extends FormRequest
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
        Validator::extend('check_firstname', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('firstname'));
        });

        Validator::extend('check_lastname', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('lastname'));
        });

        Validator::extend('checkunique_email', function ($attribute, $value, $parameters, $validator) {
            $user = User::where('email', 'LIKE', '%'.request('email').'%')->exists();
            if ($user) {
                return false;
            }

            return true;
        });

        Validator::extend('checkunique_mobile', function ($attribute, $value, $parameters, $validator) {
            $user = User::where([['mobile_no', '=', request('mobile_no')], ['usergroup_id', 7]])->exists();
            if ($user) {
                return false;
            }

            return true;
        });

        Validator::extend('check_occupation', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z_~\-!@#\$%\^&*.,:(\)\s]+$/', request('sub_occupation'));
        });

        Validator::extend('check_designation', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z_~\-!@#\$%\^&*.,:(\)\s]+$/', request('designation'));
        });

        Validator::extend('check_organization_name', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z_~\-!@#\$%\^&*.,:(\)\s]+$/', request('organization_name'));
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

        if (request('parent') == 'select') {
            $rules['select_id'] = 'required';
        } else {

            $rules =
            [
                //
                'firstname' => 'required|check_firstname|max:35',
                'lastname' => 'nullable|check_lastname|max:15',
                'email' => 'nullable|email|checkunique_email',
                'mobile_no' => 'required|numeric|digits:10|checkunique_mobile',
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

            for ($i = 0; $i < request('count'); $i++) {
                $rules['qualification_id'.$i] = 'nullable';
            }
        }

        return $rules;
    }

    public function messages()
    {
        $messages =
        [
            'select_id.required' => 'Select Parent',

            'firstname.required' => 'First Name Is Required',
            'firstname.check_firstname' => 'Enter A Valid First Name',
            'firstname.max:15' => 'First Name Should Be Atmost 15 Characters',

            'lastname.check_lastname' => 'Enter A Valid Last Name',
            'lastname.max:15' => 'Last Name Should Be Atmost 15 Characters',

            'email.required' => 'Email ID Is Required',
            'email.email' => 'Enter A valid Email ID ',
            'email.checkunique_email' => 'Email ID Already In Use. Enter Different Email ID',

            'mobile_no.required' => 'Mobile Number Is Required',
            'mobile_no.numeric' => 'Mobile Number Should Be Numeric',
            'mobile_no.digits:10' => 'Mobile Number Should Be 10 Digits',
            'mobile_no.checkunique_mobile' => 'Mobile Number Already In Use. Enter Different Mobile Number',

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

        for ($i = 0; $i < request('count'); $i++) {
            $messages['qualification_id'.$i.'.required'] = 'Qualification Is Required';
        }

        return $messages;
    }
}
