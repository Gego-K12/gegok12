<?php

namespace App\Http\Requests;

use App\Models\Assignment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class StudentAssignmentAddRequest extends FormRequest
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
        Validator::extend('check_date', function ($attribute, $value, $parameters, $validator) {
            $assignment = Assignment::where('id', request('assignment_id'))->first();
            $date = date('Y-m-d', strtotime($assignment->submission_date));

            if (date('Y-m-d') > $date) {
                return false;
            }

            return true;
        });

        return [
            //
            // 'assignment_file'   =>  'required|mimes:pdf|max:8092|check_date',
            'assignment_file' => 'required|max:8092|check_date',
        ];
    }

    public function messages()
    {
        return
        [
            'assignment_file.required' => 'Assignment File Is Required',
            'assignment_file.mimes' => 'Choose A Pdf File',
            'assignment_file.max' => 'Maximum File Size To Upload Is 8MB',
            'assignment_file.check_date' => 'Submission Date Already Expired',
        ];
    }
}
