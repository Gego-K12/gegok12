<?php

namespace App\Http\Requests\Admission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class AdmissionAvatarRequest extends FormRequest
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
        Validator::extend('file_extension', function ($attribute, $value, $parameters, $validator) {
            $image_parts = explode(';base64,', request('avatar'));
            $image_type_aux = explode('image/', $image_parts[0]);
            $image_type = $image_type_aux[1];

            if ($image_type == 'jpg') {
                return true;
            } elseif ($image_type == 'jpeg') {
                return true;
            } elseif ($image_type == 'png') {
                return true;
            }

            return false;
        });

        return [
            //
            'avatar' => 'required|file_extension',
        ];
    }

    public function messages()
    {
        return [
            'avatar.required' => 'Avatar is required',
            'avatar.file_extension' => 'Choose jpg,jpeg,png file',
        ];
    }
}
