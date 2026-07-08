<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Userprofile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class EditUserDetailRequest extends FormRequest
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
        Validator::extend('check_marriage_start_date', function ($attribute, $value, $parameters, $validator) {
            if (request('gender') == 'female') {
                $user = User::where('id', request('ref_id'))->first();
                if ($user != '') {
                    if (date('Y-m-d', strtotime(optional($user->userprofile)->marriage_start_date)) == date('Y-m-d', strtotime(request('marriage_start_date')))) {
                        return true;
                    }

                    return false;
                }

                return true;
            }

            return true;
        });

        Validator::extend('check_marriage_start_date_value', function ($attribute, $value, $parameters, $validator) {
            if ((request('marriage_start_date') <= date('Y-m-d')) && (request('marriage_start_date') >= '1940-01-01')) {
                return true;
            }

            return false;
        });

        /*Validator::extend('check_marriage_end_date',function($attribute,$value,$parameters,$validator)
        {
            $user = User::where('id',request('ref_id'))->first();
            if(date('Y-m-d',strtotime(optional($user->userprofile)->marriage_end_date))==date('Y-m-d',strtotime(request('marriage_end_date'))))
            {
                return true;
            }
            return false;
        });

        Validator::extend('check_marriage_end_date_value',function($attribute,$value,$parameters,$validator)
        {
            if((request('marriage_end_date')<=date('Y-m-d')) && (request('marriage_end_date')>="1940-01-01"))
            {
                return true;
            }

            return false;
        });*/

        Validator::extend('check_date_of_birth', function ($attribute, $value, $parameters, $validator) {
            if ((request('date_of_birth') <= date('Y-m-d')) && (request('date_of_birth') >= '1920-01-01')) {
                return true;
            }

            return false;
        });

        $rules = [
            //
            'firstname' => 'required|alpha|max:15',
            'lastname' => 'nullable|alpha|max:15',
            'birth_firstname' => 'nullable|alpha|max:15',
            'birth_lastname' => 'nullable|alpha|max:15',
            'gender' => 'required',
            'date_of_birth' => 'required|date|check_date_of_birth',
            'aadhar_number' => 'required|numeric|digits:12',
            // 'was_baptized'    =>  'required',
            'profession' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'pincode' => 'required|numeric|min:6',
            'family' => 'nullable',
            'marriage_status' => 'required',
            'giving_no' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ];

        if ((request('marriage_status') == 'married') || (request('marriage_status') == 'ended_by_death') || (request('marriage_status') == 'ended_by_divorce') || (request('marriage_status') == 'separated')) {
            $rules['marriage_start_date'] = 'required|check_marriage_start_date_value'; // |check_marriage_start_date

            /*if((request('marriage_status')== "ended_by_death") || (request('marriage_status')== "ended_by_divorce") || (request('marriage_status')== "separated"))
            {
                $rules['marriage_end_date']='required|check_marriage_end_date|check_marriage_end_date_value';
            }*/

        }

        if (Input::hasFile('avatar') != '') {
            $rules['avatar'] = 'required|mimes:jpg,jpeg,png,bmp';
        }

        return $rules;
    }

    public function messages()
    {
        return
        [
            'firstname.required' => 'First Name is required',
            'firstname.alpha' => 'Enter a Valid First Name',
            'firstname.max:15' => 'First Name should be atmost 15 characters',
            'lastname.alpha' => 'Enter a Valid Last Name',
            'lastname.max:15' => 'Last Name should be atmost 15 characters',
            'birth_firstname.alpha' => 'Enter a Valid Birth First Name',
            'birth_firstname.max:15' => 'Birth First Name should be atmost 15 characters',
            'birth_lastname.alpha' => 'Enter a Valid Birth Last Name',
            'birth_lastname.max:15' => 'Birth  Last Name should be atmost 15 characters',
            'gender.required' => 'Gender is required',
            'date_of_birth.required' => 'Date Of Birth is required',
            'date_of_birth.check_date_of_birth' => 'Enter valid Date Of Birth',
            /*'was_baptized.required'=>'Baptism is required',
            'baptism_date.required'=>'Baptism Date is required',*/
            'profession.required' => 'Profession is required',
            'address.required' => 'Address is required',
            'city.required' => 'City is required',
            'state.required' => 'State is required',
            'country.required' => 'Country is required',
            'pincode.required' => 'Pincode is required',
            'pincode.numeric' => 'Pincode should be numeric',
            'pincode.min:6' => 'Pincode should be atleast 6 digits',
            // 'family.required'=>'Family is required',
            'marriage_status.required' => 'Marriage Status is required',
            'marriage_start_date.required' => 'Marriage Start Date is required',
            // 'marriage_end_date.required'=>'Marriage End Date is required',
            'marriage_start_date.check_marriage_start_date' => 'Enter Valid Marriage Start Date',
            'marriage_start_date.check_marriage_start_date_value' => 'Enter Valid Marriage Start Date',
            // 'marriage_end_date.check_marriage_end_date'=>'Enter Valid Marriage End Date',
            // 'marriage_end_date.check_marriage_end_date_value'=>'Enter Valid Marriage End Date',
            'aadhar_number.required' => 'Aadhaar Number is required',
            'aadhar_number.numeric' => 'Aadhaar Number should be Numeric',
            'aadhar_number.digits:12' => 'Aadhaar Number should be of 12 digits',
            'notes.string' => 'Enter Valid Notes',
            'avatar.required' => 'Avatar is required',
            'avatar.mimes' => 'Choose an image file',
        ];
    }
}
