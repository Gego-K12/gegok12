<?php

namespace App\Http\Requests\Admission;

use App\Models\School;
use App\Models\Standard;
use Illuminate\Foundation\Http\FormRequest;

class AdmissionAcademicRequest extends FormRequest
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
        $rules = [
            //
            'english' => 'nullable|numeric|max:100',
            'tamil' => 'nullable|numeric|max:100',
            'maths' => 'nullable|numeric|max:100',
            'science' => 'nullable|numeric|max:100',
            'social' => 'nullable|numeric|max:100',
            'group_selection' => 'nullable',
            'board_of_education' => 'required',
            'choice_of_language' => 'required',
        ];

        $school = School::where('slug', request('slug'))->first();
        $standard = Standard::where([['school_id', $school->id], ['id', request('standard_id')]])->first();

        if (($standard->name == '10') || ($standard->name == '11') || ($standard->name == '12')) {
            $rules['group_selection'] = 'required';
            $rules['board_registration_number'] = 'required|numeric';
        }

        return $rules;
    }

    public function messages()
    {
        return
        [
            'english.numeric' => 'Enter Valid English Marks',
            'english.max' => 'Enter Valid English Marks Cannot Be Greater Than 100',

            'tamil.numeric' => 'Enter Valid Tamil Marks',
            'tamil.max' => 'Enter Valid Tamil Marks Cannot Be Greater Than 100',

            'maths.numeric' => 'Enter Valid Maths Marks',
            'maths.max' => 'Enter Valid Maths Marks Cannot Be Greater Than 100',

            'science.numeric' => 'Enter Valid Science Marks',
            'science.max' => 'Enter Valid Science Marks Cannot Be Greater Than 100',

            'social.numeric' => 'Enter Valid Social Marks',
            'social.max' => 'Enter Valid Social Marks Cannot Be Greater Than 100',

            'board_of_education.required' => 'Board of Study Is Required',

            'choice_of_language.required' => 'Choice of Language Is Required',

            'group_selection.required' => 'Group Selection Is Required',

            'board_registration_number.required' => 'Board Registration Number Is Required',
            'board_registration_number.numeric' => 'Board Registration Number Should Be Numeric',
        ];
    }
}
