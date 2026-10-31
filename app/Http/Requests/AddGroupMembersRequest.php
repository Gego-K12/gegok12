<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddGroupMembersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && (int) $this->user()->usergroup_id === 3;
    }

    public function rules(): array
    {
        return [
            'group_id' => ['required', 'integer', Rule::exists('groups', 'id')->whereNull('deleted_at')],
            'selectedUsers' => ['required', 'array', 'min:1', 'max:200'],
            'selectedUsers.*' => ['required', 'integer', 'distinct', Rule::exists('users', 'id')
                ->where('school_id', $this->user()->school_id)->where('usergroup_id', 6)->where('status', 'active')->whereNull('deleted_at')],
        ];
    }
}
