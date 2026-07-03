<?php

namespace App\Http\Requests\Admission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class AdmissionStudentRequest extends FormRequest
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
        Validator::extend('check_name', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('name'));
        });

        Validator::extend('check_birth_place', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('birth_place'));
        });

        Validator::extend('check_nationality', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('nationality'));
        });

        Validator::extend('check_religion', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('religion'));
        });

        Validator::extend('check_community', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('community'));
        });

        Validator::extend('check_mother_tongue', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('mother_tongue'));
        });

        Validator::extend('check_identification_marks', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('identification_marks'));
        });

        Validator::extend('check_school_last_studied', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('school_last_studied'));
        });

        Validator::extend('check_reason_for_leaving', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('reason_for_leaving'));
        });

        return [
            //
            'name' => 'required|check_name',
            'date_of_birth' => 'required|date',
            'gender' => 'required',
            'height' => 'required|numeric|digits:3',
            'weight' => 'required|numeric|min:1|max:150',
            'birth_place' => 'required|check_birth_place',
            'nationality' => 'required|check_nationality',
            'religion' => 'required|check_religion',
            'community' => 'required|check_community',
            'mother_tongue' => 'required|check_mother_tongue',
            'identification_marks' => 'required|check_identification_marks',
            'aadhar_number' => 'required|digits:12',
            'blood_group' => 'required',
            'school_last_studied' => 'nullable|check_school_last_studied',
            'reason_for_leaving' => 'nullable|check_reason_for_leaving',
            'permanent_address' => 'required',
            'address_for_communication' => 'required',
            'siblings' => 'required',
        ];
    }

    public function messages()
    {
        return [
            //
            'name.required' => 'Name Required',
            'name.check_name' => 'Enter Valid Name',

            'date_of_birth.required' => 'Date Of Birth Required',

            'gender.required' => 'Select Gender',

            'height.required' => 'Height Required',
            'height.numeric' => 'Enter Valid Number',
            'height.digits:3' => 'Enter Valid Height',

            'weight.required' => 'Weight Required',
            'weight.numeric' => 'Enter Valid Number',
            'weight.min:1' => 'Weight Must Be Atleast 1',
            'weight.max:150' => 'Weight Cannot Be More Than 150',

            'birth_place.required' => 'Birth Place Required',
            'birth_place.check_birth_place' => 'Enter Valid Birth Place',

            'nationality.required' => 'Nationality Required',
            'nationality.check_nationality' => 'Enter Valid Nationality',

            'religion.required' => 'Religion Required',
            'religion.check_religion' => 'Enter Valid Religion',

            'community.required' => 'Community Required',
            'community.check_community' => 'Enter Valid Community Required',

            'mother_tongue.required' => 'Mother Tongue Required',
            'mother_tongue.check_mother_tongue' => 'Enter Valid Mother Tongue',

            'identification_marks.required' => 'Identification Mark Required',
            'identification_marks.check_identification_marks' => 'Enter Valid Identification Mark',

            'aadhar_number.required' => 'Aadhaar number Required',
            'aadhar_number.digits' => 'Enter Valid Aadhaar Number',

            'blood_group.required' => 'Blood Group Required',

            'school_last_studied.check_school_last_studied' => 'Enter Valid school last studied',

            'reason_for_leaving.check_reason_for_leaving' => 'Enter Valid Reason For Leaving',

            'permanent_address.required' => 'Permanent Address Required',

            'address_for_communication.required' => 'Communication Address Required',

            'siblings.required' => 'Select Siblings',
        ];
    }
}
