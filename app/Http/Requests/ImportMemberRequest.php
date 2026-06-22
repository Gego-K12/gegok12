<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SiteHelper;

class ImportMemberRequest extends FormRequest
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
        Validator::extend('file_extension', function ($attribute, $value, $parameters, $validator)
        {
            $extension = $value->getClientOriginalExtension();
            return $extension != '' && in_array($extension, $parameters);
        });

        Validator::extend('check_academic_year', function ($attribute, $value, $parameters, $validator)
        {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);
            if($academic_year)
            {
                return true;
            }
            return false;
        });

        return [
            //
            'import_file' => 'required|check_academic_year|mimes:xlsx,xls',
        ];
    }

     public function messages()
    {
        return
        [
            'import_file.required'              => 'File is required',
            'import_file.check_academic_year'   => 'Academic Year Is Not Found',
            'import_file.file_extension'        => 'Choose csv file',
            'import_file.max'                   => 'Maximum file size to upload is 2MB',
        ];
    }
}
