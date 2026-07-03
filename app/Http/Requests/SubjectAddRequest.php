<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubjectAddRequest extends FormRequest
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
        $rules['subject_standard_id'] = 'required';
        $rules['subject_section_id'] = 'required';
        for ($i = 0; $i < request('subjectscount'); $i++) {
            $rules['subject_name'.$i] = 'required';
            $rules['subject_code'.$i] = 'required';
            $rules['subject_type'.$i] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        $messages['subject_standard_id.required'] = 'Standar is required';
        $messages['subject_section_id.required'] = 'Subject is required';

        for ($i = 0; $i < Request('subjectscount'); $i++) {
            $messages['subject_name'.$i.'.required'] = 'Subject is required';
            $messages['subject_code'.$i.'.required'] = 'Code is required';
            $messages['subject_type'.$i.'.required'] = 'Type is required';
        }

        return $messages;
    }
}
