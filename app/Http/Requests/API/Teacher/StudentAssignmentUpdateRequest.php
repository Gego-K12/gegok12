<?php

namespace App\Http\Requests\API\Teacher;

use App\Models\StudentAssignment;
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
            $assignment_id = (int) request('id');
            $studentassignment = StudentAssignment::where('id', $assignment_id)->first();
            if (request('obtained_marks') <= $studentassignment->assignment->marks) {
                return true;
            }

            return false;
        });

        Validator::extend('check_mark_exists', function ($attribute, $value, $parameters, $validator) {
            $assignment_id = (int) request('id');
            $studentassignment = StudentAssignment::where('id', $assignment_id)->first();
            if ($studentassignment->obtained_marks == null) {
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
            'obtained_marks' => 'required|check_marks|check_valid_marks|check_mark_exists',
            'comments' => 'nullable|check_comments',
        ];
    }

    public function messages()
    {
        $assignment_id = (int) request('id');
        $studentassignment = StudentAssignment::where('id', $assignment_id)->first();

        return
        [
            'obtained_marks.required' => 'Mark is required',
            'obtained_marks.check_marks' => 'Mark cannot be greater than '.$studentassignment->assignment->marks,
            'obtained_marks.check_valid_marks' => 'Enter Valid Mark',
            'obtained_marks.check_mark_exists' => 'Mark Already Exists For This Assignment',

            'comments.check_comments' => 'Enter Valid Comments',
        ];
    }
}
