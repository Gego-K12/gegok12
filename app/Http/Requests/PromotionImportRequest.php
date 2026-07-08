<?php

namespace App\Http\Requests;

use App\Models\Standard;
use App\Models\StandardLink;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PromotionImportRequest extends FormRequest
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
        Validator::extend('file_extension', function ($attribute, $value, $parameters, $validator) {
            $extension = $value->getClientOriginalExtension();

            return $extension != '' && in_array($extension, $parameters);
        });

        Validator::extend('check_next_class', function ($attribute, $value, $parameters, $validator) {
            $standardLink = StandardLink::where('school_id', Auth::user()->school_id)->where('id', request('curr_standardlink_id'))->first();
            $standard = Standard::where('school_id', Auth::user()->school_id)->where('id', $standardLink->standard_id)->first();

            if (request('next_standardlink_id') == 'alumni') {
                if ($standard->name == '12') {
                    return true;
                }

                return false;
            } elseif ($standard->name == '12') {
                if (request('next_standardlink_id') == 'alumni') {
                    return true;
                }

                return false;
            }

            return true;
        });

        return
        [
            //
            'promotion_file' => 'required|file_extension:csv',
            'next_academic_year_id' => 'required',
            'next_standardlink_id' => 'required|check_next_class',
        ];
    }

    public function messages()
    {
        return
        [
            //
            'promotion_file.required' => 'Import File Is Required',
            'promotion_file.file_extension' => 'Choose Csv File',

            'next_academic_year_id.required' => 'Next Academic Year Is Required',

            'next_standardlink_id.required' => 'Next Class Is Required',
            'next_standardlink_id.check_next_class' => 'Select Valid Next Class',
        ];
    }
}
