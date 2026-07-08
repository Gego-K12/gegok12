<?php

namespace App\Http\Requests;

use App\Models\TeacherProfile;
use App\Models\User;
use App\Models\Userprofile;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TeacherProfileAddRequest extends FormRequest
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
        Validator::extend('check_firstname', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('firstname'));
        });

        Validator::extend('check_lastname', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('lastname'));
        });

        Validator::extend('checkunique_email', function ($attribute, $value, $parameters, $validator) {
            $teacher = User::where([['school_id', Auth::user()->school_id], ['email', 'LIKE', '%'.request('email').'%']])->exists();
            if ($teacher) {
                return false;
            }

            return true;
        });

        Validator::extend('checkunique_mobile', function ($attribute, $value, $parameters, $validator) {
            $teacher = User::where([['mobile_no', '=', request('mobile_no')], ['usergroup_id', 5]])->exists();
            if ($teacher) {
                return false;
            }

            return true;
        });

        Validator::extend('checkunique_employee_id', function ($attribute, $value, $parameters, $validator) {
            $teacher = TeacherProfile::where([['school_id', Auth::user()->school_id], ['employee_id', '=', request('employee_id')]])->exists();
            if ($teacher) {
                return false;
            }

            return true;
        });

        Validator::extend('checkunique_aadhar_no', function ($attribute, $value, $parameters, $validator) {
            $teacher = Userprofile::where([['school_id', Auth::user()->school_id], ['aadhar_number', '=', request('aadhar_number')]])->WhereIn('usergroup_id', [5, 13, 10, 11, 8])->exists();
            if ($teacher) {
                return false;
            }

            return true;
        });

        Validator::extend('check_date_of_birth', function ($attribute, $value, $parameters, $validator) {
            if ((request('date_of_birth') <= date('Y-m-d')) && (request('date_of_birth') >= '1940-01-01')) {
                return true;
            }

            return false;
        });

        Validator::extend('check_joining_date', function ($attribute, $value, $parameters, $validator) {
            $now = Carbon::now()->subYears(40)->format('Y');

            if ((request('joining_date') <= date('Y-m-d')) && (date('Y', strtotime(request('joining_date'))) >= $now)) {
                return true;
            }

            return false;
        });

        Validator::extend('check_sub_designation', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z_~\-!@#\$%\^&*.,:(\)\s]+$/', request('sub_designation'));
        });

        $rules =
        [
            //
            'firstname' => 'required|check_firstname|max:15',
            'lastname' => 'nullable|check_lastname|max:15',
            'date_of_birth' => 'required|date|check_date_of_birth',
            'mobile_no' => 'required|numeric|digits:10|checkunique_mobile',
            'email' => 'nullable|email|checkunique_email',
            'gender' => 'required',
            'blood_group' => 'required',
            'aadhar_number' => 'nullable|numeric|digits:12|checkunique_aadhar_no',
            // 'avatar'                => 'required|mimes:jpg,jpeg,png',
            'employee_id' => 'required|alpha_num|checkunique_employee_id',
            'joining_date' => 'required|date|check_joining_date',
            'designation' => 'required',
            'job_type' => 'required',
            'marital_status' => 'required',
        ];

        if (Request('staff_status') == 'teaching') {
            $rules['email'] = 'required|email|checkunique_email';
        }

        if (Request('designation') == 'others') {
            $rules['sub_designation'] = 'required|check_sub_designation';
        }

        /* if( (Request('designation') != 'principal') && (Request('designation') != 'vice_principal') )
         {
             $rules['reporting_to'] = 'required';
         }*/

        return $rules;
    }

    public function messages()
    {
        $messages = [];

        $messages =
        [
            'firstname.required' => 'First Name Is Required',
            'firstname.check_firstname' => 'Enter A Valid First Name',
            'firstname.max:15' => 'First Name should Be Atmost 15 Characters',

            'lastname.check_lastname' => 'Enter A Valid Last Name',
            'lastname.max:15' => 'Last Name Should Be Atmost 15 Characters',

            'mobile_no.required' => 'Mobile Number is required',
            'mobile_no.numeric' => 'Mobile Number should be numeric',
            'mobile_no.digits' => 'Mobile Number should be 10 digits',
            'mobile_no.checkunique_mobile' => 'Mobile Number already in use. Enter different Mobile Number',

            'email.required' => 'Email ID Is Required',
            'email.email' => 'Enter A valid Email ID ',
            'email.checkunique_email' => 'Email ID Already In Use. Enter Different Email ID',

            'date_of_birth.required' => 'Date Of Birth Is Required',
            'date_of_birth.check_date_of_birth' => 'Enter Valid Date Of Birth',

            'gender.required' => 'Gender Is Required',

            'blood_group.required' => 'Blood Group Is Required',

            'aadhar_number.required' => 'Aadhaar Number Is Required',
            'aadhar_number.numeric' => 'Aadhaar Number Should Be Numeric',
            'aadhar_number.digits' => 'Aadhaar Number Should Be Of 12 Digits',
            'aadhar_number.checkunique_aadhar_no' => 'Aadhaar Number Already In Use. Enter Different Aadhaar Number ',

            'avatar.required' => 'Avatar Is Required',
            'avatar.mimes' => 'Choose jpg,jpeg,png File',

            'employee_id.required' => 'Employee ID Is Required',
            'employee_id.numeric' => 'Employee ID Should Be Numeric',
            'employee_id.checkunique_employee_id' => 'Employee ID Already Exists',

            'joining_date.required' => 'Joining Date Is Required',
            'joining_date.check_joining_date' => 'Select A Valid Joining Date',

            'designation.required' => 'Designation Is Required',

            'sub_designation.required' => 'Sub Designation Is Required',
            'sub_designation.check_sub_designation' => 'Enter A Valid Sub Designation',

            'reporting_to.required' => 'Report To Is Required',
        ];

        return $messages;
    }
}
