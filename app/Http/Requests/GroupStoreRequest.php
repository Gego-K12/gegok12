<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GroupStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && (int) $this->user()->usergroup_id === 3;
    }

    public function rules(): array
    {
        $school = $this->user()->school_id;
        return [
            'group_name' => ['required', 'string', 'max:255'],
            'standards_link_id' => ['nullable', 'required_without:level', 'integer',
                Rule::exists('standards_link', 'id')->where('school_id', $school)->whereNull('deleted_at')],
            'level' => ['nullable', 'required_without:standards_link_id', 'integer', 'between:1,6'],
            'teacher_id' => ['nullable', 'required_without:standards_link_id', 'integer',
                Rule::exists('users', 'id')->where('school_id', $school)->where('usergroup_id', 5)
                    ->where('status', 'active')->whereNull('deleted_at')],
        ];
    }
}
