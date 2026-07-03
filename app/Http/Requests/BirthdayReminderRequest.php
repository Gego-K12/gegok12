<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BirthdayReminderRequest extends FormRequest
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
            'to' => 'required',
            'message' => 'required|max:150',
        ];
    }

    public function messages()
    {
        return [
            'to.required' => 'To is required',
            'message.required' => 'Message is required',
            'message.max:150' => 'Message should be atmost 150 characters',
        ];
    }
}
