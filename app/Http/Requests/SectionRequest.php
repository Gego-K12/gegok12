<?php

namespace App\Http\Requests;

use App\Models\Section;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SectionRequest extends FormRequest
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
        Validator::extend('check_unique_section', function ($attribute, $value, $parameters, $validator) {
            $section = Section::where([['school_id', Auth::user()->school_id], ['name', '=', request('section')]])->exists();
            if ($section) {
                return false;
            }

            return true;
        });

        Validator::extend('check_section', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[[:<:]][A-Za-z][[:>:]]+$/', request('section'));
        });

        return [
            //
            'section' => 'required|check_section|check_unique_section',
        ];
    }

    public function messages()
    {
        return [
            'section.required' => 'Section is required',
            'section.check_section' => 'Enter a Valid Section Name',
            'section.check_unique_section' => 'Section Name already Exists',
        ];
    }
}
