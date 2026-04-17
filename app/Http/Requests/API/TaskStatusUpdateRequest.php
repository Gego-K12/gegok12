<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TaskStatusUpdateRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'task_completed' => 'required|array|min:1',
            'task_completed.*' => 'required|integer|exists:task,id',

        ];
    }
    public function messages(): array
    {
        return [
            'task_completed.required' => 'Please provide task IDs.',
            'task_completed.array' => 'Task data must be an array format.',
            'task_completed.min' => 'At least one task must be selected.',

            'task_completed.*.required' => 'Task ID is required.',
            'task_completed.*.integer' => 'Task ID must be a valid number.',
            'task_completed.*.exists' => 'One or more selected tasks do not exist.',
        ];
    }
}
