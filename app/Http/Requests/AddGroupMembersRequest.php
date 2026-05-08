<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\GroupMember;
use App\Models\Userprofile;

class AddGroupMembersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules.
     */
    public function rules(): array
    {
        return [
            'group_id' => 'required|exists:groups,id',
            'selectedUsers' => 'required|array|min:1',
        ];
    }

    /**
     * Custom validation.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $existingMembers = GroupMember::where(
                'group_id',
                $this->group_id
            )
            ->whereIn('member_id', $this->selectedUsers)
            ->pluck('member_id')
            ->toArray();

            if(count($existingMembers) > 0)
            {
                $studentNames = Userprofile::whereIn(
                    'user_id',
                    $existingMembers
                )
                ->get(['firstname', 'lastname'])
                ->map(function ($user) {

                    return $user->firstname . ' ' . $user->lastname;

                })
                ->toArray();

                $validator->errors()->add(
                    'selectedUsers',
                    implode(', ', $studentNames) . ' already exist in this group.'
                );
            }
        });
    }

    /**
     * Custom messages.
     */
    public function messages(): array
    {
        return [
            'group_id.required' => 'Please select group',
            'group_id.exists'   => 'Selected group invalid',
            'selectedUsers.required' => 'Please select students',
        ];
    }
}