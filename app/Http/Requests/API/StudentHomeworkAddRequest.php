<?php

namespace App\Http\Requests\API;

use App\Models\Homework;
use App\Models\StudentHomework;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class StudentHomeworkAddRequest extends FormRequest
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
            $homework_id = (int) request('homework_id');
            $homework = Homework::where('id', $homework_id)->first();
            $date = date('Y-m-d', strtotime($homework->date));

            if (date('Y-m-d') > $date) {
                return false;
            }

            return true;
        });

        Validator::extend('check_submission', function ($attribute, $value, $parameters, $validator) {
            $homework_id = (int) request('homework_id');
            $homework = Homework::where('id', $homework_id)->first();
            if ($homework->submission_date != null) {
                $date = date('Y-m-d', strtotime($homework->submission_date));

                if (date('Y-m-d') > $date) {
                    return false;
                }
            }

            return true;
        });

        Validator::extend('check_homework', function ($attribute, $value, $parameters, $validator) {
            $homework_id = (int) request('homework_id');
            $student_id = (int) request('student_id');

            $studentHomework = StudentHomework::where([['homework_id', $homework_id], ['user_id', $student_id], ['deleted_at', null]])->first();

            if ($studentHomework != null) {
                return false;
            }

            return true;
        });

        Validator::extend('file_extension', function ($attribute, $value, $parameters, $validator) {
            $extension = $value->getClientOriginalExtension();

            return $extension != '' && in_array($extension, $parameters);
        });

        $files = request('file');
        if (count($files) == 0) {
            $rules['file'] = 'required|max:8092|check_date|check_homework';
        } else {

            // $rules['file.*'] = 'required|file_extension:jpeg,jpg,png|max:8092|check_date|check_homework';
            $rules['file.*'] = 'required|check_submission|check_homework';
        }

        return $rules;
    }

    public function messages()
    {
        return
        [
            'file.required' => 'Homework File Is Required',
            'file.*.mimes' => 'File Extension Error. Select jpg,jpeg,png Files',
            'file.*.max' => 'Maximum file size to upload is 8MB',
            'file.*.check_date' => 'Submission Date Already Expired',
            'file.*.check_homework' => 'Homework File already Exists',
            'file.*.check_submission' => 'Submission Date Already Expired',
        ];
    }
}
