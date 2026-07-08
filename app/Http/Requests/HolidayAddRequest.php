<?php

namespace App\Http\Requests;

use App\Helpers\SiteHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class HolidayAddRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [];
        $count = $this->input('count');

        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);

        for ($i = 0; $i < $count; $i++) {

            $rules['date'.$i] = [
                'required',
                function ($attribute, $value, $fail) use ($academic_year) {
                    if (
                        $value < date('Y-m-d', strtotime($academic_year->start_date)) ||
                        $value > date('Y-m-d', strtotime($academic_year->end_date))
                    ) {
                        $fail('Enter Valid Date');
                    }
                },
            ];

            $rules['title'.$i] = [
                'required',
                // 'regex:/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/'
            ];
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];
        $count = $this->input('count');

        for ($i = 0; $i < $count; $i++) {

            $messages['date'.$i.'.required'] = 'Date is required';
            $messages['title'.$i.'.required'] = 'Title is required';
            $messages['title'.$i.'.regex'] = 'Enter Valid Title';
        }

        return $messages;
    }
}
