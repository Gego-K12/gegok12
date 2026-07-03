<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class NoticeRequest extends FormRequest
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
        Validator::extend('check_title', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z_~\-!@#\$%\^&*.,:(\)\s]+$/', request('title'));
        });

        Validator::extend('check_publish_date', function ($attribute, $value, $parameters, $validator) {
            if (date('Y-m-d', strtotime(request('publish_date'))) > date('Y-m-d', strtotime('-1 days', strtotime(date('Y-m-d'))))) {
                return true;
            }

            return false;
        });

        $rules = [
            //
            'type' => 'required',
            'title' => 'required|max:20|check_title',
            'publish_date' => 'required|before_or_equal:expire_date|check_publish_date',
            'expire_date' => 'required|after_or_equal:publish_date',
            'description' => 'required',
            'attachment_file' => 'nullable|mimes:pdf|max:8092',
            // 'attachment_file'   => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        if (request('type') == 'class') {
            $rules['standardLink_id'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return
        [
            'type.required' => 'Notice To is required',

            'title.required' => 'Title is required',
            'title.check_title' => 'Enter Only alphabets',

            'publish_date.required' => 'Publish Date Required',
            'publish_date.check_publish_date' => 'Enter Valid Publish Date',

            'expire_date.required' => 'Expire Date Required',

            'description.required' => 'Description is required',

            'attachment_file.mimes' => 'Choose a image file',
            'attachment_file.max' => 'Maximum file size to upload is 2MB',

            'standardLink_id.required' => 'Class is required',
        ];
    }
}
