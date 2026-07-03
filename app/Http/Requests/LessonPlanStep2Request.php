<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class LessonPlanStep2Request extends FormRequest
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
        Validator::extend('check_objective', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', $attribute);
        });

        Validator::extend('check_materials_required', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', $attribute);
        });

        Validator::extend('check_assessment', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', $attribute);
        });

        return [
            //
            'objective' => 'required|check_objective',
            'materials_required' => 'required|check_materials_required',
            'assessment' => 'required|check_assessment',
        ];
    }

    public function messages()
    {
        return [
            'objective.required' => 'Objective is required',
            'objective.check_objective' => 'Enter Valid Objective',

            'materials_required.required' => 'Materials Required is required',
            'materials_required.check_materials_required' => 'Enter Valid Materials Required',

            'assessment.required' => 'Assessment is required',
            'assessment.check_assessment' => 'Enter Valid Assessment',
        ];
    }
}
