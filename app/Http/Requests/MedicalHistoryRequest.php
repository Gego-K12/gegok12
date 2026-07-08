<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class MedicalHistoryRequest extends FormRequest
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
        Validator::extend('check_medication_problems', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', $attribute);
        });

        Validator::extend('check_medication_needs', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', $attribute);
        });

        Validator::extend('check_medication_allergies', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', $attribute);
        });

        Validator::extend('check_food_allergies', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', $attribute);
        });

        Validator::extend('check_other_allergies', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', $attribute);
        });

        Validator::extend('check_other_medical_information', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', $attribute);
        });

        return [
            //
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'medication_problems' => 'nullable|check_medication_problems|max:300',
            'medication_needs' => 'nullable|check_medication_needs|max:300',
            'medication_allergies' => 'nullable|check_medication_allergies|max:300',
            'food_allergies' => 'nullable|check_food_allergies|max:300',
            'other_allergies' => 'nullable|check_other_allergies|max:500',
            'other_medical_information' => 'nullable|check_other_medical_information|max:500',
        ];
    }

    public function messages()
    {
        return [
            //
            'height.required' => 'Height is required',
            'height.numeric' => "Height should be in cm's",

            'weight.required' => 'Weight is required',
            'weight.numeric' => "Weight should be in kg's",

            'medication_problems.check_medication_problems' => 'Enter Valid Medication Problems',
            'medication_problems.max' => 'Medication Problems cannot be more than 300 characters',

            'medication_needs.check_medication_needs' => 'Enter Valid Medication Needs',
            'medication_needs.max' => 'Medication Needs cannot be more than 300 characters',

            'medication_allergies.check_medication_allergies' => 'Enter Valid Medication Allergies',
            'medication_allergies.max' => 'Medication Allergies cannot be more than 300 characters',

            'food_allergies.check_food_allergies' => 'Enter Valid Food Allergies',
            'food_allergies.max' => 'Food Allergies cannot be more than 300 characters',

            'other_allergies.check_other_allergies' => 'Enter Valid Other Allergies',
            'other_allergies.max' => 'Other Allergies cannot be more than 500 characters',

            'other_medical_information.check_other_medical_information' => 'Enter Valid Other Medication Information',
            'other_medical_information.max' => 'Other Medication Information cannot be more than 500 characters',
        ];
    }
}
