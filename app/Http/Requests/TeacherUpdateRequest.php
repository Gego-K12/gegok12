<?php

namespace App\Http\Requests;

use App\Models\TeacherProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TeacherUpdateRequest extends FormRequest
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
        Validator::extend('checkunique_employee_id', function ($attribute, $value, $parameters, $validator) {
            $user = User::where('name', Request('teacher_name'))->first();
            $teacher = TeacherProfile::where([['school_id', Auth::user()->school_id], ['employee_id', '=', request('employee_id')], ['id', $user->id]])->exists();
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
            $now = Carbon::now()->subYears(60)->format('Y');

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
            'firstname' => 'required|alpha|max:15',
            'lastname' => 'nullable|alpha|max:15',
            'date_of_birth' => 'required|date|check_date_of_birth',
            'gender' => 'required',
            'blood_group' => 'required',
            'aadhar_number' => 'nullable|numeric|digits:12',
            'employee_id' => 'required|alpha_num|checkunique_employee_id',
            'joining_date' => 'required|date|check_joining_date',
            'designation' => 'required',
        ];

        if (Request('designation') == 'others') {
            $rules['sub_designation'] = 'required|check_sub_designation';
        }

        if ((Request('designation') != 'principal') && (Request('designation') != 'vice_principal')) {
            $rules['reporting_to'] = 'required';
        }

        if (Request('avatar') == '') {
            $rules['avatar'] = 'nullable|mimes:jpg,jpeg,png';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];

        $messages =
        [
            'firstname.required' => 'First Name is required',
            'firstname.alpha' => 'Enter a Valid First Name',
            'firstname.max:15' => 'First Name should be atmost 15 digits',

            'lastname.alpha' => 'Enter a Valid Last Name',
            'lastname.max:15' => 'Last Name should be atmost 15 digits',

            'date_of_birth.required' => 'Date Of Birth is required',
            'date_of_birth.check_date_of_birth' => 'Enter valid Date Of Birth',

            'gender.required' => 'Gender is required',

            'blood_group.required' => 'Blood Group is required',

            'aadhar_number.required' => 'Aadhaar Number is required',
            'aadhar_number.numeric' => 'Aadhaar Number should be Numeric',
            'aadhar_number.digits' => 'Aadhaar Number should be of 12 digits',

            'avatar.mimes' => 'Choose jpg,jpeg,png file',

            'employee_id.required' => 'Employee ID is required',
            'employee_id.numeric' => 'Employee ID should be numeric',
            'employee_id.checkunique_employee_id' => 'Employee ID already exists',

            'joining_date.required' => 'Joining Date is required',
            'joining_date.check_joining_date' => 'Select a Valid Joining Date',

            'designation.required' => 'Designation is required',

            'sub_designation.required' => 'Sub Designation is required',
            'sub_designation.check_sub_designation' => 'Enter a Valid Sub Designation',

            'reporting_to.required' => 'Report To is required',
        ];

        return $messages;
    }
}
