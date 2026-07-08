<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventGalleryRequest extends FormRequest
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

        return [

            // 'image-upload' => 'required',
            'path' => 'size:2000K',

        ];
    }

    public function messages()
    {
        return
        [
            // 'image-upload.required' =>'Select Atleast one file',
            'path.size' => 'Image size should be less than 2mb',

        ];
    }
}
