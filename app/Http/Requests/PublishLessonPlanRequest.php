<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PublishLessonPlanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ];
    }

    public function messages()
    {
        return [
            'start_date.required'       => 'Start date is required',
            'start_date.date'           => 'Start date should be valid date',
            'start_date.after_or_equal' => 'Start date should not be before today',

            'end_date.required'         => 'End date is required',
            'end_date.date'             => 'End date should be valid date',
            'end_date.after_or_equal'   => 'End date should be same as or after start date',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
            'errors'  => $validator->errors(),
        ], 422));
    }
}