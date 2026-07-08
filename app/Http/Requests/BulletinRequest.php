<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulletinRequest extends FormRequest
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
            //
            'name' => 'required|max:15',
            // 'type'          => 'required',
            'cover_image' => 'nullable|mimes:jpg,jpeg,png',
            'year' => 'required',
            'bulletin_file' => 'required|mimes:pdf|max:8092',
        ];
    }

    public function messages()
    {
        return [
            //
            'name.required' => 'Magazine Name Is Required',
            'name.max:15' => 'Magazine Name Should Be Atmost 15 Characters',

            'year.required' => 'Year Is Required',

            'cover_image.mimes' => 'Choose Jpg Or Png Image',

            'bulletin_file.required' => 'Magazine File Is Required',
            'bulletin_file.mimes' => 'Choose A Pdf File',
            'bulletin_file.max' => 'Maximum File Size To Upload Is 8MB',
        ];
    }
}
