<?php

namespace App\Http\Requests;

use App\Models\Keyword;
use App\Models\School;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DetailRequest extends FormRequest
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
        Validator::extend('checkunique_schoolname', function ($attribute, $value, $parameters, $validator) {
            $school = School::where('name', 'LIKE', '%'.request('name').'%')->where('id', Auth::user()->school_id)->exists();
            if (! $school) {
                return false;
            }

            return true;
        });

        Validator::extend('check_keyword', function ($attribute, $value, $parameters, $validator) {
            $keyword = Keyword::where('name', 'LIKE', '%'.request('name').'%')->exists();
            if ($keyword) {
                return false;
            }

            return true;
        });

        Validator::extend('check_date', function ($attribute, $value, $parameters, $validator) {
            if (date('Y-m-d', strtotime(request('date_of_establishment'))) <= date('Y-m-d', strtotime('-1 days', strtotime(date('Y-m-d'))))) {
                return true;
            }

            return false;
        });

        Validator::extend('check_admission_close_message', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('admission_close_message'));
        });

        Validator::extend('check_admission_close_date', function ($attribute, $value, $parameters, $validator) {
            $date = date('Y-m-d H:i:s', strtotime(request('admission_close_on')));
            $now = date('Y-m-d H:i:s');
            if ($date > $now) {
                return true;
            }

            return false;
        });

        Validator::extend('check_website', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.)*[-a-z0-9]+.*)$/', request('website'));
        });

        $rules =
        [
            'name' => 'required|max:30|checkunique_schoolname|check_keyword',
            'moto' => 'required|max:50',
            'affiliated_by' => 'required|max:100',
            'affiliation_no' => 'required|numeric',
            'date_of_establishment' => 'required|check_date',
            'board' => 'required',
            'landline_no' => 'required|numeric|digits:10',
            'about_us' => 'required|max:250',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'pincode' => 'required|numeric|digits:6',
            'website' => 'nullable|check_website',
            'admission_open' => 'required',
        ];

        if (request('admission_open') == 'on') {
            $rules['admission_close_message'] = 'required|check_admission_close_message|max:250';
            $rules['admission_close_on'] = 'required|check_admission_close_date';
        }

        if (request('school_logo') != null) {
            $rules['school_logo'] = 'required|mimes:png,jpg';
        }

        return $rules;
    }

    public function messages()
    {
        return
        [
            'name.required' => 'School Name Is Required',
            'name.max:15' => 'School Name Should Be Atmost 30 Characters',
            'name.checkunique_schoolname' => 'School Name Already Exists. Try Different Name',
            'name.check_keyword' => 'Enter A Valid School Name',

            'moto.required' => 'Moto Is Required',
            'moto.max' => 'Moto Should Not Exceed More Than 50 Characters',

            'affiliated_by.required' => 'Affiliated Name Is Required',
            'affiliated_by.max' => 'Affiliated Name Should Not Exceed More Than 100 Characters',

            'affiliation_no.required' => 'Affiliation Number Is Required',

            'date_of_establishment.required' => 'Select Establishment Date',
            'date_of_establishment.check_date' => 'Select Valid Date',

            'board.required' => 'Select Board Of Education',

            'school_logo.required' => 'School Logo Is Required',
            'school_logo.mimes' => 'Choose png or jpg File',

            'landline_no.required' => 'Landline Number Is Required',
            'landline_no.digits' => 'Landline Number Should Be 10 digits',

            'about_us.required' => 'About Us Is Required',
            'about_us.max' => 'About Us Should Not Exceed 250 Words',

            'country_id.required' => 'Country Is Required',

            'state_id.required' => 'State Is Required',

            'city_id.required' => 'City Is Required',

            'pincode.required' => 'Pincode Is Required',
            'pincode.numeric' => 'Pincode Should Be Numeric',
            'pincode.digits:6' => 'Pincode Should Be 6 Digits',

            'website.check_website' => 'Enter Valid Website',

            'admission_open.required' => 'Admission Open Status Is Required',

            'admission_close_message.required' => 'Admission Close Message Is Required',
            'admission_close_message.check_admission_close_message' => 'Enter Valid Admission Close Message',
            'admission_close_message.max' => 'Admission Close Message Should Not Exceed 250 Words',

            'admission_close_on.required' => 'Admission Close On Is Required',
            'admission_close_on.check_admission_close_date' => 'Enter Valid Admission Close On',
        ];
    }
}
