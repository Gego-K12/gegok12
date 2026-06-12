<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentTagRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tag_name' => 'required|string|max:255',
            'selectedUsers' => 'required|array|min:1',
        ];
    }

    public function messages()
    {
        return [
            'tag_name.required' => 'Please enter a tag name.',
            'tag_name.max' => 'Tag name must not exceed 255 characters.',
            'selectedUsers.required' => 'Please select at least one student.',
            'selectedUsers.min' => 'Please select at least one student.',
        ];
    }
}