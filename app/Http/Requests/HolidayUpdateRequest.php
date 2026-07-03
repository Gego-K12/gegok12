<?php

namespace App\Http\Requests;

use App\Helpers\SiteHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HolidayUpdateRequest extends FormRequest
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
        Validator::extend('check_date', function ($attribute, $value, $parameters, $validator) {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);

            if (request('date') >= date('Y-m-d', strtotime($academic_year->start_date)) && (request('date') <= date('Y-m-d', strtotime($academic_year->end_date)))) {
                return true;
            }

            return false;
        });

        Validator::extend('check_title', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z_~\-!@#\$%\^&*.,:(\)\s]+$/', request('title'));
        });

        return [
            //
            'date' => 'required|check_date',
            'title' => 'required|check_title',
        ];
    }

    public function messages()
    {
        return [
            //
            'date.required' => 'Date is required',
            'date.check_date' => 'Enter Valid Date',

            'title.required' => 'Title is required',
            'title.check_title' => 'Enter Valid Title',
        ];
    }
}
