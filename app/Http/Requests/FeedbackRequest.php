<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class FeedbackRequest extends FormRequest
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
        Validator::extend('check_message', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('message'));
        });

        Validator::extend('check_count', function ($attribute, $value, $parameters, $validator) {
            $file_count = count(request('files'));
            if ($file_count > 6) {
                return false;
            }

            return true;
        });

        Validator::extend('check_file_extension', function ($attribute, $value, $parameters, $validator) {
            $extension = $value->getClientOriginalExtension();

            return $extension != '' && in_array($extension, $parameters);
        });

        $rules = [
            //
            'message' => 'required|max:600',
            'category' => 'required', // |check_count
        ];

        // $files = request('files');
        // $rules['files.*'] = 'check_file_extension:jpeg,jpg,png|max:8192';

        return $rules;
    }

    public function messages()
    {
        return [
            //
            'message.required' => 'Message Is Required',
            'message.max' => 'Message Cannot Be More Than 300 Characters',
            'message.check_message' => 'Enter Valid Message(Special character did not accept (!@#\$%\^&*.,))',

            'category.required' => 'Category Is Required',
            'category.check_count' => 'Attachment Cannot Be More Than 5',

            'files.max' => 'Attachment Size Should Be Within 8MB',
            'files.*' => "Attachment Should Be 'JPG or PNG'",
        ];
    }
}
