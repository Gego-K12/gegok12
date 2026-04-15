<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HomeworkRequest extends FormRequest
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
        $status = request('status');

        $rules = [
            //
            'status' => 'required|in:draft,publish',
            'standardLink_id' => 'required',
            'subject_id'      => 'required',
            'description'     => 'nullable',
            'attachment'      => 'nullable|mimes:jpeg,png,jpg,pdf|max:20480',
            //mimes:'jpg','jpeg','png','mp3','mp4','pdf'
            'date'            => 'nullable|after_or_equal:today',
            'submission_date' => 'nullable|after_or_equal:date',    
        ];

        if ($status === 'publish') {

            $rules['description']     .= '|required';

            $rules['date']   .= '|required|before:submission_date';
            $rules['submission_date'] .= '|required|after:date';
        }

        if(request('mode') == 'admin')
        {
            $rules['teacher_id'] = 'nullable';
        }
        return $rules;
    }

    public function messages()
    {
        return[
            'standardLink_id.required'  => 'Class Is Required',

            'subject_id.required'       => 'Subject Is Required',

            'teacher_id.required'       => 'Teacher Is Required',

            'description.required'      => 'Description Is Required',

            'date.required'             => 'Date Is Required',
            'date.date_equals'          => 'Select Valid Date',

            'attachment.mimes'          => 'Choose A Valid File', 
            'attachment.max'            => 'Maximum File Size To Upload Is 20MB',    
        ];
    }
}