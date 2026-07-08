<?php

namespace App\Http\Requests\API\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class PerformanceAddRequest extends FormRequest
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
        $rules = [];
        $rules = [

            //
            'student_id' => 'required',
            'incident_detail' => 'required',
            'type' => 'required',
            'media_type' => 'required',
            'attachments' => 'required',

        ];

        if (request('media_type') == 'image') {
            $rules['attachments'] = 'required|mimes:png,jpg,jpeg';
        }

        if (request('media_type') == 'audio') {
            $rules['attachments'] = 'required|mimes:mp3';
        }

        if (request('media_type') == 'video') {
            $rules['attachments'] = 'required|mimes:mp4,mov,ogg | max:20000';
        }

        return $rules;

    }

    public function messages()
    {
        return [
            //
            'student_id.required' => 'Please Select Student',
            'type.required' => 'Please Select type',
            'media_type.required' => 'Please Select media_type',
            'incident_detail.required' => ' Type incident_detail',

        ];
    }
}
