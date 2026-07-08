<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatRoomMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string,
     */
    public function rules(): array
    {
        return [
            'type' => ['required'],
            'user_id' => ['required'],

        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Type is required.',

            'user_id.required' => 'user_id is required.',

        ];
    }
}
