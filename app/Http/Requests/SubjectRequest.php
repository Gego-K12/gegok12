<?php

namespace App\Http\Requests;

use App\Models\Subject;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubjectRequest extends FormRequest
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
        Validator::extend('check_unique_subject', function ($attribute, $value, $parameters, $validator) {
            $subject = Subject::where([
                ['school_id', Auth::user()->school_id],
                ['name', request('subject')],
                ['standard_id', request('subject_standard_id')],
                ['section_id', request('subject_section_id')],
            ])->exists();

            if ($subject) {
                return false;
            }

            return true;
        });

        Validator::extend('check_subject', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('subject'));
        });

        Validator::extend('check_code', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9]+$/', request('code'));
        });

        return
        [
            // 'subject_standard_id' => 'required',
            // 'subject_section_id'  => 'required',
            'subject' => 'required|check_unique_subject|check_subject',
            'code' => 'nullable|check_code',
            'type' => 'required',
        ];
    }

    public function messages()
    {
        return
        [
            'subject_standard_id.required' => 'Standard Is Required',
            'subject_section_id.required' => 'Section Is Required',
            'subject.required' => 'Name Is Required',
            'subject.check_unique_subject' => 'Subject Already Exists',
            'subject.check_subject' => 'Enter Valid Name',

            'code.required' => 'Code Is Required',
            'code.unique' => 'Code Should Be Unique',
            'code.check_code' => 'Enter A Valid Code',

            'type.required' => 'Type Is Required',
        ];
    }
}
