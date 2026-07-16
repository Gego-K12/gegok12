<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\StandardLink;
use App\Models\Userprofile;
use App\Models\Standard;
use App\Models\User;
use Carbon\Carbon;

class UserProfileAddRequest extends FormRequest
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
        Validator::extend('checkunique_email',function($attribute,$value,$parameters,$validator)
        {
             $user=User::where('email','LIKE','%'.request('email').'%')->exists();
             if($user)
             {
                return false;
             }
             return true;
        });

        Validator::extend('check_mobile_no',function($attribute,$value,$parameters,$validator)
        {
             $user=User::where('mobile_no','=',request('mobile_no'))->exists();
             if($user)
             {
                return false;
             }
             return true;
        });

        Validator::extend('check_date_of_birth',function($attribute,$value,$parameters,$validator)
        { 
            $start = date('Y-06-01',strtotime('-20 years',strtotime(date('Y-m-d'))));
            $end = date('Y-06-01',strtotime('-3 years',strtotime(date('Y-m-d'))));
            if( (request('date_of_birth') <= $end)  && (request('date_of_birth') >= $start) )
            { 
                return true;
            }
            return false;
        });

        Validator::extend('check_joining_date',function($attribute,$value,$parameters,$validator)
        { 
            $now  = Carbon::now()->subYears(18)->format('Y');

            if((request('joining_date')<=date('Y-m-d')) && ( date('Y',strtotime(request('joining_date'))) >= $now ) )
            {
                return true;
            }
            return false;
        });

        Validator::extend('checknotes',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('notes')) ;
        });

        Validator::extend('check_firstname',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z\s]+$/', request('firstname')) ;
        });

        Validator::extend('check_lastname',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z\s]+$/', request('lastname')) ;
        });

        Validator::extend('check_birth_place',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z\s]+$/', request('birth_place')) ;
        });

        Validator::extend('check_native_place',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z\s]+$/', request('native_place')) ;
        });

        Validator::extend('check_mother_tongue',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z\s]+$/', request('mother_tongue')) ;
        });

        Validator::extend('check_driver_name',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z\s]+$/', request('driver_name')) ;
        });

        Validator::extend('check_unique_mobile',function($attribute,$value,$parameters,$validator)
        {
            $user=User::where('mobile_no','=',request('driver_contact_number'))->exists();
            if($user)
            {
                return false;
            }
            return true;
        });

        Validator::extend('check_unique_aadhar_number',function($attribute,$value,$parameters,$validator)
        {
            $user=Userprofile::where('aadhar_number',request('aadhar_number'))->exists();
            if($user)
            {
                return false;
            }
            return true;
        });

        Validator::extend('check_unique_registration_number', function ($attribute, $value, $parameters, $validator) {
            $user = User::where('registration_number', request('registration_number'))->exists();
            if ($user) {
                return false;
            }

            return true;
        });

        $rules =
        [
            //
            'firstname'                 => 'required|check_firstname|max:15',
            'lastname'                  => 'nullable|check_lastname|max:15',
            'email'                     => 'nullable|email|checkunique_email',
            'mobile_no'                 => 'nullable|numeric|digits:10|check_mobile_no',
            'date_of_birth'             => 'required|date|check_date_of_birth',
            'gender'                    => 'required',
            'blood_group'               => 'required',
            'aadhar_number'             => 'nullable|numeric|digits:12|check_unique_aadhar_number',
            'city_id'                   => 'required',
            'state_id'                  => 'required',
            'country_id'                => 'required',
            'pincode'                   => 'nullable|numeric|digits:6',
            'birth_place'               => 'nullable|check_birth_place',
            'native_place'              => 'nullable|check_native_place',
            'mother_tongue'             => 'required|check_mother_tongue',
            'caste'                     => 'required',
            'avatar'                    => 'required|mimes:jpg,jpeg,png',
            'notes'                     => 'nullable|string|checknotes',
            'registration_number'       => 'required|numeric|check_unique_registration_number',
            'EMIS_number'               => 'nullable|numeric',
            'joining_date'              => 'required|date|check_joining_date',
            'standard'                  => 'required',
            'roll_number'               => 'required|numeric',
            'id_card_number'            => 'nullable|numeric',  
            'mode_of_transport'         => 'nullable',
            'siblings'                  => 'required',
            'board_registration_number' => 'nullable|numeric',
        ];

        $standardLink = StandardLink::where('school_id',Auth::user()->school_id)->where('id',request('standard'))->first();
        $standard = Standard::where('school_id',Auth::user()->school_id)->where('id',$standardLink->standard_id)->first();

        if( ( $standard->name == '10' ) || ( $standard->name == '11' )  || ( $standard->name == '12' ) )
        {
            $rules['board_registration_number'] = 'required|numeric'; 
        }

        if( (request('mode_of_transport') == 'auto') || (request('mode_of_transport') == 'rickshaw') || (request('mode_of_transport') == 'taxi') )
        {
            $rules['driver_name']           = 'nullable|check_driver_name';
            $rules['driver_contact_number'] = 'nullable|numeric|digits:10|check_unique_mobile';
        }

        for($i=0 ; $i<Request('count') ; $i++)
        {  
            Validator::extend('check_sibling_name',function($attribute,$value,$parameters,$validator)
            { 
                return preg_match('/^[A-Za-z\s]+$/', $value);
            });

            Validator::extend('check_sibling_date_of_birth',function($attribute,$value,$parameters,$validator)
            { 
                if( ($value<=date('Y-m-d')) && ($value>="2000-01-01") )
                {
                    return true;
                } 
                return false;
            });

            if(request('siblings') == 'yes')
            {
                $rules['sibling_relation'.$i]      = 'required';
                $rules['sibling_name'.$i]          = 'required|check_sibling_name';
                $rules['sibling_date_of_birth'.$i] = 'required|check_sibling_date_of_birth';
                $rules['sibling_standard'.$i]      = 'nullable';
            }
        }

        return $rules;
    }

    public function messages()
    {
        $start = date('01-06-Y',strtotime('-20 years',strtotime(date('Y-m-d'))));
        $end = date('01-06-Y',strtotime('-3 years',strtotime(date('Y-m-d'))));

        $messages =         
        [
            'firstname.required'                                => 'First Name Is Required',
            'firstname.check_firstname'                         => 'Enter A Valid First Name',
            'firstname.max:15'                                  => 'First Name Should Be Atmost 15 Characters',

            'lastname.check_lastname'                           => 'Enter A Valid Last Name',
            'lastname.max:15'                                   => 'Last Name Should Be Atmost 15 Characters',

            'email.required'                                    => 'Email ID Is Required',
            'email.email'                                       => 'Enter A Valid Email ID ',
            'email.checkunique_email'                           => 'Email ID Already In Use. Enter Different Email ID',

            'mobile_no.required'                                => 'Mobile Number Is Required',
            'mobile_no.numeric'                                 => 'Mobile Number Should Be Numeric',
            'mobile_no.digits:10'                               => 'Mobile Number Should Be 10 Digits',
            'mobile_no.check_mobile_no'                         => 'Mobile Number Already In Use. Enter Different Mobile Number',

            'date_of_birth.required'                            => 'Date Of Birth Is Required',
            'date_of_birth.check_date_of_birth'                 => 'Date Of Birth Should Be Greater Than '.$start.' Or Lesser Than '.$end,

            'gender.required'                                   => 'Gender Is Required',

            'blood_group.required'                              => 'Blood Group Is Required',

            'aadhar_number.required'                            => 'Aadhaar Number Is Required',
            'aadhar_number.numeric'                             => 'Aadhaar Number Should Be Numeric',
            'aadhar_number.digits:12'                           => 'Aadhaar Number Should Be Of 12 Digits',
            'aadhar_number.check_unique_aadhar_number'          => 'Aadhaar Number Already Exists.Enter Different Aadhar Number',

            'city_id.required'                                  => 'City Is Required',

            'state_id.required'                                 => 'State Is Required',

            'country_id.required'                               => 'Country Is Required',

            'pincode.required'                                  => 'Pincode Is Required',
            'pincode.numeric'                                   => 'Pincode Should Be Numeric',
            'pincode.digits:6'                                  => 'Pincode Should Be 6 Digits',

            'birth_place.required'                              => 'Birth Place Is Required',
            'birth_place.check_birth_place'                     => 'Enter Valid Birth Place',

            'native_place.required'                             => 'Native Place Is Required',
            'native_place.check_native_place'                   => 'Enter Valid Native Place',

            'mother_tongue.required'                            => 'Mother Tongue Is Required',
            'mother_tongue.check_mother_tongue'                 => 'Enter Valid Mother Tongue',

            'caste.required'                                    => 'Caste Is Required',

            'avatar.required'                                   => 'Avatar Is Required',
            'avatar.mimes'                                      => 'Choose jpg,jpeg,png File',

            'notes.string'                                      => 'Enter Valid Notes',
            'notes.checknotes'                                  => 'Enter Valid Notes',

            'registration_number.required'                      => 'Registration Number Is Required',
            'registration_number.numeric'                       => 'Registration Number Should Be Numeric',
            'registration_number.check_unique_registration_number' => 'Registration Number Already Exists.Enter Different Registration Number',

            'EMIS_number.required'                              => 'EMIS Number Is Required',
            'EMIS_number.numeric'                               => 'EMIS Number Should Be Numeric',

            'joining_date.required'                             => 'Joining Date Is Required',
            'joining_date.check_joining_date'                   => 'Select A Valid Joining Date',

            'standard.required'                                 => 'Class Is Required',

            'roll_number.required'                              => 'Roll Number Is Required',
            'roll_number.numeric'                               => 'Roll Number Should Be Numeric',

            'id_card_number.required'                           => 'ID Card Number Is Required',
            'id_card_number.numeric'                            => 'ID Card Number Should Be Numeric',

            'board_registration_number.required'                => 'Board Registration Number Is Required',
            'board_registration_number.numeric'                 => 'Board Registration Number Should Be Numeric',

            'mode_of_transport.required'                        => 'Mode Of Transport Is Required',

            'driver_name.required'                              => 'Driver Name Is Required',
            'driver_name.check_driver_name'                     => 'Enter Valid Driver Name',

            'driver_contact_number.required'                    => 'Driver Contact Number Is Required',
            'driver_contact_number.numeric'                     => 'Driver Contact Number Should Be Numeric',
            'driver_contact_number.digits:10'                   => 'Driver Contact Number should be 10 digits',
            'driver_contact_number.check_unique_mobile'         => 'Contact Number Already In Use. Enter Different Contact Number',

            'siblings.required'                                 => 'Siblings Is Required',
        ];

        for($i=0 ; $i < Request('count') ; $i++)
        {
            $messages['sibling_relation'.$i.'.required']                            = 'Sibling Relation Is Required';

            $messages['sibling_name'.$i.'.required']                                = 'Sibling Name Is Required';

            $messages['sibling_name'.$i.'.check_sibling_name']                      = 'Enter Valid Sibling Name';

            $messages['sibling_date_of_birth'.$i.'.required']                       = 'Sibling Date Of Birth Is Required';

            $messages['sibling_date_of_birth'.$i.'.check_sibling_date_of_birth']    = 'Enter Valid Sibling Date Of Birth';

            $messages['sibling_standard'.$i.'.required']                            = 'Sibling Class Is Required';
        }

        return $messages;
    }
}