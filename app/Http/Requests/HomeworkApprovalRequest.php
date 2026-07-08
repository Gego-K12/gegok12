<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class HomeworkApprovalRequest extends FormRequest
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
            return preg_match('/^[A-Za-z0-9\s]+$/', request('principal_comments'));
        });

        return [
            //
            'principal_comments' => 'nullable|check_comments|max:100',
        ];
    }

    public function messages()
    {
        return [
            'principal_comments.check_comments' => 'Enter Valid Comments',
            'principal_comments.max' => 'Comments Cannot Be More Than 100 Characters',
        ];
    }
}
