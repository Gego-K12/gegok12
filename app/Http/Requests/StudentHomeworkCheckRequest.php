<?php

namespace App\Http\Requests;

use App\Models\StudentHomework;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class StudentHomeworkCheckRequest extends FormRequest
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
        Validator::extend('check_comments', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('comments'));
        });

        Validator::extend('check_status', function ($attribute, $value, $parameters, $validator) {
            $studentHomework = StudentHomework::where('id', request('id'))->first();
            if ($studentHomework->status == 'checked') {
                return false;
            }

            return true;
        });

        return [
            //
            'comments' => 'nullable|check_status|check_comments|max:100',
        ];
    }

    public function messages()
    {
        return [
            'comments.check_comments' => 'Enter Valid Comments',
            'comments.check_status' => 'Homework Already Checked',
            'comments.max' => 'Comments Cannot Be More Than 100 Characters',
        ];
    }
}
