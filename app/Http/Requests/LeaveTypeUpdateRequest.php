<?php

namespace App\Http\Requests;

use App\Models\LeaveType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class LeaveTypeUpdateRequest extends FormRequest
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
        Validator::extend('check_unique_name', function ($attribute, $value, $parameters, $validator) {
            $leaveType = LeaveType::where([
                ['school_id', $school_id],
                ['academic_year_id', $academic_year->id],
                ['name', 'LIKE', '%'.request('name').'%'],
                ['id', request('id')],
            ])->exists();
            if (! $leaveType) {
                return true;
            }

            return false;
        });

        Validator::extend('check_name', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z_~\-!@#\$%\^&*.,:(\)\s]+$/', request('name'));
        });

        $rules =
        [
            //
            'name' => 'required|max:30|check_unique_name|check_name',
            'max_no_of_days' => 'required|numeric|max:100',
        ];

        return $rules;
    }

    public function messages()
    {
        $messages =
        [
            'name.required' => 'Title is required',
            'name.max:30' => 'Title should be atmost 30 characters',
            'name.check_unique_name' => 'Title Already Exists',
            'name.check_name' => 'Enter a Valid Title',

            'max_no_of_days.required' => 'Max. No. Of Days is required',
            'max_no_of_days.numeric' => 'Max. No. Of Days should be numeric.',
            'max_no_of_days.max:100' => 'Enter a Valid Max. No. Of Days',
        ];

        return $messages;
    }
}
