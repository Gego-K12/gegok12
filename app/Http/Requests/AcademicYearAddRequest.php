<?php

namespace App\Http\Requests;

use App\Models\AcademicYear;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AcademicYearAddRequest extends FormRequest
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
        Validator::extend('check_start_date', function ($attribute, $value, $parameters, $validator) {
            $prev_four_year = date('Y', strtotime('-4 years', strtotime(date('Y-m-d'))));
            $start_date = date('Y', strtotime(request('start_date')));

            if ($start_date >= $prev_four_year) {
                return true;
            }

            return false;
        });

        Validator::extend('check_end_date', function ($attribute, $value, $parameters, $validator) {
            $next_year_from_start_date = date('Y-m-d', strtotime('+1 year', strtotime(request('start_date'))));
            $end_date = date('Y-m-d', strtotime(request('end_date')));

            if ($end_date <= $next_year_from_start_date) {
                return true;
            }

            return false;
        });

        Validator::extend('check_description', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('description'));
        });

        Validator::extend('check_status', function ($attribute, $value, $parameters, $validator) {
            if (request('status') == 'current') {
                $academic_year = AcademicYear::where([['school_id', Auth::user()->school_id], ['status', 1]])->first();
                if ($academic_year == null) {
                    return true;
                }

                return false;
            }

            return true;
        });

        return [
            //
            // 'name'          => 'required|max:20',
            'description' => 'nullable|check_description|max:100',
            'start_date' => 'required|date|check_start_date',
            'end_date' => 'required|date|check_end_date',
            'status' => 'required|check_status',
        ];
    }

    public function messages()
    {
        return [
            // 'name.required'                 =>  'Title is required',
            // 'name.max:20'                   =>  'Title should not be greater than 20 digits',

            'start_date.required' => 'Start Date Is Required',
            'start_date.check_start_date' => 'Enter Valid Start Date',

            'end_date.required' => 'End Date Is Required',
            'end_date.check_end_date' => 'Enter Valid End Date',

            'description.check_description' => 'Enter Valid Description',
            'description.max:100' => 'Description Should Not Be Greater Than 100 Characters',

            'status.required' => 'Type Is Required',
            'status.check_status' => 'Current Academic Year Already Assigned',
        ];
    }
}
