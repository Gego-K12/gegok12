<?php

namespace App\Http\Requests;

use App\Models\Assignment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class StudentAssignmentUpdateRequest extends FormRequest
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
        Validator::extend('check_marks', function ($attribute, $value, $parameters, $validator) {
            $assignment = Assignment::where('id', request('assignment_id'))->first();
            if (request('obtained_marks') <= $assignment->marks) {
                return true;
            }

            return false;
        });

        Validator::extend('check_valid_marks', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[0-9]+$/', request('obtained_marks'));
        });

        Validator::extend('check_comments', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z_~\-!@#\$%\^&*.,:(\)\s]+$/', request('comments'));
        });

        return [
            //
            'obtained_marks' => 'required|check_marks|check_valid_marks',
            'comments' => 'nullable|check_comments',
        ];
    }

    public function messages()
    {
        $assignment = Assignment::where('id', request('assignment_id'))->first();

        return
        [
            'obtained_marks.required' => 'Mark is required',
            'obtained_marks.check_marks' => 'Mark cannot be greater than '.$assignment->marks,
            'obtained_marks.check_valid_marks' => 'Enter Valid Mark',
            'comments.check_comments' => 'Enter Valid Comments',
        ];
    }
}
