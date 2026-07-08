<?php

namespace App\Http\Requests;

use App\Helpers\SiteHelper;
use App\Models\StandardLink;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StandardDetailRequest extends FormRequest
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

        Validator::extend('check_unique_class_teacher', function ($attribute, $value, $parameters, $validator) {
            $standardLink = StandardLink::where('class_teacher_id', '=', request('class_teacher_id'))->exists();
            if ($standardLink) {
                return false;
            }

            return true;
        });

        Validator::extend('check_standard_link', function ($attribute, $value, $parameters, $validator) {
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
            $standardLink = StandardLink::where([
                ['school_id', Auth::user()->school_id],
                ['academic_year_id', $academic_year->id],
                ['standard_id', request('standard_id')],
                ['section_id', request('section_id')],
            ])->exists();
            if ($standardLink) {
                return false;
            }

            return true;
        });

        Validator::extend('check_other_stream', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('other_stream'));
        });

        $rules = [];

        $rules =
        [
            //
            'standard_id' => 'required|check_standard_link',
            // 'no_of_students'    =>  'required|numeric',
            'section_id' => 'required',
            'class_teacher_id' => 'nullable|check_unique_class_teacher',
        ];

        if ((request('standard_name') == '11') || (request('standard_name') == '12')) {
            $rules['stream'] = 'required';
            if (request('stream') == 'others') {
                $rules['other_stream'] = 'required|check_other_stream|max:25';
            }
        }

        for ($i = 0; $i < Request('count'); $i++) {
            $rules['subject_id'.$i] = 'required';
            // $rules['teacher_id'.$i] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];

        $messages =
        [
            'standard_id.required' => 'Standard is required',
            'standard_id.check_standard_link' => 'Class Already Exists',
            'section_id.required' => 'Section is required',
            'no_of_students.required' => 'No Of Students is required',
            'no_of_students.numeric' => 'No Of Students Should be Number',
            'class_teacher_id.required' => 'Class Teacher is required',
            'class_teacher_id.check_unique_class_teacher' => 'Class Teacher already selected',
            'stream.required' => 'Select Stream',
            'other_stream.required' => 'Enter Stream Name',
            'other_stream.check_other_stream' => 'Enter Valid Stream Name',
            'other_stream.max' => 'Stream Name cannot be more than 25 characters',
        ];

        for ($i = 0; $i < Request('count'); $i++) {
            $messages['subject_id'.$i.'.required'] = 'Subject is required';
            $messages['teacher_id'.$i.'.required'] = 'Teacher is required';
        }

        return $messages;
    }
}
