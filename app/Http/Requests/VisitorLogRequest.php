<?php

namespace App\Http\Requests;

use App\Helpers\SiteHelper;
use App\Models\ParentProfile;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VisitorLogRequest extends FormRequest
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
        Validator::extend('check_relation', function ($attribute, $value, $parameters, $validator) {
            $student = User::where('id', request('student_id'))->first();

            foreach ($student->parents as $parent) {
                $parentprofile = ParentProfile::where('user_id', $parent->userParent->id)->first();

                if (request('relation_with_student') == $parentprofile->relation) {
                    return true;
                }
            }

            return false;
        });

        Validator::extend('check_time', function ($attribute, $value, $parameters, $validator) {
            if (request('date_of_visit') == date('Y-m-d')) {
                if (request('entry_time') <= date('H:i')) {
                    return true;
                }
            } elseif (request('date_of_visit') < date('Y-m-d')) {
                if (request('entry_time') >= date('H:i')) {
                    return true;
                }
            }

            return false;
        });

        Validator::extend('check_company_name', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('company_name'));
        });

        Validator::extend('check_address', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('address'));
        });

        Validator::extend('check_name', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('name'));
        });

        Validator::extend('check_relation_name', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('relation_name'));
        });

        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $start_date = date('Y-m-d', strtotime($academic_year->start_date));
        $end_date = date('Y-m-d', strtotime($academic_year->end_date));

        $rules = [
            //
            'employee_id' => 'required',
            'relation' => 'required',
            'visiting_purpose' => 'required',
            // 'date_of_visit'     =>  'required|date|after:'.$start_date.'|before:'.$end_date,
            'date_of_visit' => 'required|date|before_or_equal:'.date('Y-m-d'),
            'email' => 'nullable|email',
            'address' => 'nullable|check_address',
            // 'entry_time'        =>    'required|check_time',
            // 'exit_time'         =>    'nullable|after:entry_time',
        ];

        if (request('relation') == 'parent') {
            $rules['student_id'] = 'required';
            $rules['relation_with_student'] = 'required';
            $rules['standardLink_id'] = 'required';
            if (request('relation_with_student') == 'others') {
                $rules['relation_name'] = 'required|check_relation_name';
            } else {
                $rules['relation_with_student'] = 'required|check_relation';
            }
        } else {
            $rules['name'] = 'required|max:20|check_name';
            $rules['contact_number'] = 'required|numeric|digits:10';
            /*$rules['company_name']='required';
            $rules['address']='required';*/
        }

        if (request('relation') == 'other' && request('visiting_purpose') == 'business') {
            $rules['company_name'] = 'required|check_company_name';
        }

        return $rules;
    }

    public function messages()
    {
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $start_date = date('d-m-Y', strtotime($academic_year->start_date));
        $end_date = date('d-m-Y', strtotime($academic_year->end_date));

        return [
            'name.required' => 'Visitor Name Is Required',

            'employee_id.required' => 'Employee Is Required',

            'relation.required' => 'Visitor Type Is Required',

            'visiting_purpose.required' => 'Visiting Purpose Is Required',

            'student_id.required' => 'Student Is Required',

            'standardLink_id.required' => 'Class Is Required',

            'company_name.required' => 'Organization Name Is Required',
            'company_name.check_company_name' => 'Enter Valid Organization Name',

            'address.required' => 'Address Is Required',

            'relation_with_student.required' => 'Relation With Student Is Required',
            'relation_with_student.check_relation' => 'This Relation Does Not Exist For Student',

            'relation_name.required' => 'Relation With Student Is Required',
            'relation_name.check_relation_name' => 'Enter Valid Relation With Student',

            'contact_number.required' => 'Contact Number Is Required',
            'contact_number.numeric' => 'Enter Valid Contact Number',
            'contact_number.digits:10' => 'Contact Number Should Be Of 10 digits',

            'date_of_visit.required' => 'Date Of Visit Is Required',
            'date_of_visit.after' => 'Date Of Visit Should Be Greater Than '.$start_date,
            'date_of_visit.before' => 'Date Of Visit Should Be Lesser Than '.$end_date,

            'entry_time.required' => 'Entry Time Is Required',
            'entry_time.check_time' => 'Check entry time',

            'email.email' => 'Enter a valid Email ID ',
        ];
    }
}
