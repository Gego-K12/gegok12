<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class LessonPlanStep3Request extends FormRequest
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
        Validator::extend('check_introduction', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', $attribute);
        });

        Validator::extend('check_procedure', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', $attribute);
        });

        Validator::extend('check_conclusion', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:"?(\)\s]+$/', $attribute);
        });

        return [
            //
            'introduction' => 'required|check_introduction',
            'procedure' => 'required|check_procedure',
            'conclusion' => 'required|check_conclusion',
        ];
    }

    public function messages()
    {
        return [
            'introduction.required' => 'Introduction is required',
            'introduction.check_introduction' => 'Enter Valid Introduction',

            'procedure.required' => 'Procedure is required',
            'procedure.check_procedure' => 'Enter Valid Procedure',

            'conclusion.required' => 'Conclusion is required',
            'conclusion.check_conclusion' => 'Enter Valid Conclusion',
        ];
    }
}
