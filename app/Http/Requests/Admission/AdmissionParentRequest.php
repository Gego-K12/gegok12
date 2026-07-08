<?php

namespace App\Http\Requests\Admission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class AdmissionParentRequest extends FormRequest
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
        Validator::extend('check_father_name', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('father_name'));
        });

        /*  Validator::extend('check_father_qualification',function($attribute,$value,$parameters,$validator)
          {
              return preg_match('/^[A-Za-z\s]+$/', request('father_qualification_id')) ;
          });*/

        Validator::extend('check_father_designation', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('father_designation'));
        });

        Validator::extend('check_father_occupation', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('father_occupation'));
        });

        Validator::extend('check_father_organisation', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('father_organisation'));
        });

        Validator::extend('check_mother_name', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('mother_name'));
        });

        /* Validator::extend('check_mother_qualification',function($attribute,$value,$parameters,$validator)
         {
             return preg_match('/^[A-Za-z\s]+$/', request('mother_qualification_id')) ;
         });*/

        Validator::extend('check_mother_designation', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('mother_designation'));
        });

        Validator::extend('check_mother_occupation', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('mother_occupation'));
        });

        Validator::extend('check_mother_organisation', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('mother_organisation'));
        });

        Validator::extend('check_relation', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('relation_with_student_1'));
        });

        Validator::extend('check_relation_two', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('relation_with_student_2'));
        });

        Validator::extend('check_mother_annual_income', function ($attribute, $value, $parameters, $validator) {
            if (strlen(request('mother_income')) < 10) {
                return true;
            }

            return false;
        });

        Validator::extend('check_mother_annual_income_value', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[0-9]+$/', request('mother_income'));
        });

        Validator::extend('check_father_annual_income', function ($attribute, $value, $parameters, $validator) {
            if (strlen(request('father_income')) < 10) {
                return true;
            }

            return false;
        });

        Validator::extend('check_father_annual_income_value', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[0-9]+$/', request('father_income'));
        });

        return [
            //
            'father_name' => 'required|check_father_name',
            'father_qualification_id' => 'required',
            'father_designation' => 'required|check_father_designation',
            'father_occupation' => 'required|check_father_occupation',
            'father_organisation' => 'required|check_father_organisation',
            'father_income' => 'required|numeric|check_father_annual_income|check_father_annual_income_value',
            'father_mobile_no' => 'required|numeric|digits:10',
            'father_email' => 'required|email',
            'father_aadhar_number' => 'required|numeric|digits:12',

            'mother_name' => 'required|check_mother_name',
            'mother_qualification_id' => 'required',
            'mother_designation' => 'required|check_mother_designation',
            'mother_occupation' => 'required|check_mother_occupation',
            'mother_organisation' => 'required|check_mother_organisation',
            'mother_income' => 'required|numeric|check_mother_annual_income|check_mother_annual_income_value',
            'mother_mobile_no' => 'nullable|numeric|digits:10',
            'mother_email' => 'nullable|email',
            'mother_aadhar_number' => 'required|numeric|digits:12',

            'emergency_contact_1' => 'required|numeric|digits:10',
            'relation_with_student_1' => 'required|check_relation',
            'emergency_contact_2' => 'required|numeric|digits:10',
            'relation_with_student_2' => 'required|check_relation_two',
        ];
    }

    public function messages()
    {
        return
        [
            'father_name.required' => 'Father Name Is Required',
            'father_name.check_father_name' => 'Enter Valid Father Name',

            'father_qualification_id.required' => 'Father Qualification Is Required',
            // 'father_qualification_id.check_father_qualification'    => 'Enter Valid Father Qualification',

            'father_designation.required' => 'Designation Is Required',
            'father_designation.check_father_designation' => 'Enter Valid Designation',

            'father_occupation.required' => 'Occupation Is Required',
            'father_occupation.check_father_occupation' => 'Enter Valid Occupation',

            'father_organisation.required' => 'Organisation Name Is Required',
            'father_organisation.check_father_organisation' => 'Enter Valid Organisation Name',

            'father_income.required' => 'Income Is Required',
            'father_income.numeric' => 'Income Should Be In Numbers',
            'father_income.check_father_annual_income' => 'Income Should Be Lesser Than 9 Digits',
            'father_income.check_father_annual_income_value' => 'Enter Valid Income',

            'father_mobile_no.required' => 'Mobile Number Is Required',
            'father_mobile_no.numeric' => 'Enter Valid Mobile Number',
            'father_mobile_no.digits:10' => 'Mobile Number Should Be Of 10 Digits',

            'father_email.required' => 'Email Is Required',
            'father_email.email' => 'Enter Valid Email',

            'father_aadhar_number.required' => 'Aadhaar Number Is Required',
            'father_aadhar_number.numeric' => 'Enter Valid Aadhaar Number',
            'father_aadhar_number.digits:12' => 'Aadhaar Number Should Be Of 12 Digits',

            'mother_name.required' => 'Mother Name Is Required',
            'mother_name.check_mother_name' => 'Enter Valid Mother Name',

            'mother_qualification_id.required' => 'Mother Qualification Is Required',
            // 'mother_qualification_id.check_mother_qualification'    => 'Enter Valid Mother Qualification',

            'mother_designation.required' => 'Designation Is Required',
            'mother_designation.check_mother_designation' => 'Enter Valid Designation',

            'mother_occupation.required' => 'Occupation Is Required',
            'mother_occupation.check_mother_occupation' => 'Enter Valid Occupation',

            'mother_organisation.required' => 'Organisation Name Is Required',
            'mother_organisation.check_mother_organisation' => 'Enter Valid Organisation Name',

            'mother_income.required' => 'Income Is Required',
            'mother_income.numeric' => 'Income Should Be In Numbers',
            'mother_income.check_mother_annual_income' => 'Income Should Be Greater Lesser Than 9 Digits',
            'mother_income.check_mother_annual_income_value' => 'Enter Valid Income',

            'mother_mobile_no.numeric' => 'Enter Valid Mobile Number',
            'mother_mobile_no.digits:10' => 'Mobile Number Should Be Of 10 Digits',

            'mother_email.email' => 'Enter Valid Email',

            'mother_aadhar_number.required' => 'Aadhaar Number Is Required',
            'mother_aadhar_number.numeric' => 'Enter Valid Aadhaar Number',
            'mother_aadhar_number.digits:12' => 'Aadhaar Number Should Be Of 12 Digits',

            'relation_with_student_1.required' => 'Relationship Is Required',
            'relation_with_student_1.check_relation' => 'Enter Valid Relation',

            'relation_with_student_2.required' => 'Relationship Is Required',
            'relation_with_student_2.check_relation_two' => 'Enter Valid Relation',

            'emergency_contact_1.required' => 'Mobile Number Is Required',
            'emergency_contact_1.numeric' => 'Enter Valid Mobile Number',
            'emergency_contact_1.digits:10' => 'Mobile Number Should Be Of 10 Digits',

            'emergency_contact_2.required' => 'Mobile Number Is Required',
            'emergency_contact_2.numeric' => 'Enter Valid Mobile Number',
            'emergency_contact_2.digits:10' => 'Mobile Number Should Be Of 10 Digits',
        ];
    }
}
