<?php

namespace App\Http\Requests\Classwall;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class PageAddRequest extends FormRequest
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
        Validator::extend('check_page_name', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('page_name'));
        });

        Validator::extend('check_description', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', $attribute);
        });

        $rules = [
            //
            'page_name' => 'required|max:25|check_page_name',
            'description' => 'required|max:300|check_description',
            'category' => 'required',
            'cover_image' => 'required|max:2048|mimes:jpg,jpeg,png', // in kb
        ];

        return $rules;
    }

    public function messages()
    {
        $messages = [
            //
            'page_name.required' => 'Page Name is required',
            'page_name.max' => 'Page Name cannot be more than 25 characters',
            'page_name.check_page_name' => 'Enter Valid Page Name',

            'description.required' => 'Description is required',
            'description.max' => 'Description cannot be more than 300 characters',
            'description.check_description' => 'Enter Valid Description',

            'category.required' => 'Category is required',

            'cover_image.required' => 'Cover Photo is required',
            'cover_image.mimes' => "Cover Photo should be 'JPG or PNG'",
            'cover_image.max' => 'Cover Photo size should be within 2MB',
        ];

        return $messages;
    }
}
