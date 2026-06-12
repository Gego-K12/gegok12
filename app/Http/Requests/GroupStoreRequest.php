<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Group;

class GroupStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'group_name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {

                    $exists = Group::whereRaw('LOWER(group_name) = ?', [strtolower($value)])
                        ->where('standardLink_id', $this->standards_link_id)
                        ->exists();

                    if ($exists) {
                        $fail('This group already exists for this class.');
                    }
                },
            ],

            'standards_link_id' => [
                'required',
                'exists:standards_link,id'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'group_name.required' => 'Group name is required.',
            'group_name.max' => 'Group name should not exceed 255 characters.',
            'standards_link_id.required' => 'Class is required.',
            'standards_link_id.exists' => 'Invalid class selected.',
        ];
    }
}