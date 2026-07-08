<?php

namespace App\Http\Requests;

use App\Helpers\SiteHelper;
use App\Models\Attendance;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StaffAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        Validator::extend('check_date', function ($attribute, $value, $parameters, $validator) {
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
            $start_date = date('Y-m-d', strtotime($academic_year->start_date));

            if ((request('date') <= date('Y-m-d')) && (request('date') >= $start_date)) {
                return true;
            }

            return false;
        });

        Validator::extend('check_session', function ($attribute, $value, $parameters, $validator) {
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
            $date = date('Y-m-d', strtotime(request('date')));
            $attendance = Attendance::where([
                ['school_id', Auth::user()->school_id],
                ['academic_year_id', $academic_year->id],
                ['date', '=', $date],
                ['session', request('session')],
                ['standardLink_id', null],
            ])->exists();
            if ($attendance) {
                return false;
            }

            return true;
        });

        $rules =
        [
            //
            'date' => 'required|date|check_date',
            'session' => 'required|check_session',
        ];

        for ($i = 0; $i < Request('absentCount'); $i++) {
            Validator::extend('check_remarks', function ($attribute, $value, $parameters, $validator) {
                return preg_match('/^[A-Za-z_~\-!@#\$%\^&*.,:(\)\s]+$/', $value);
            });

            $rules['user_id'.$i] = 'required';
            $rules['reason_id'.$i] = 'required';
            $rules['remarks'.$i] = 'nullable|max:20|check_remarks';
        }

        return $rules;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        $messages =
        [
            //
            'date.required' => 'Date is required',
            'date.check_date' => 'Enter valid Date',
            'session.required' => 'Session is required',
            'session.check_session' => 'Attendance already updated',
        ];

        for ($i = 0; $i < Request('absentCount'); $i++) {
            $messages['user_id'.$i.'.required'] = 'Student is required';
            $messages['reason_id'.$i.'.required'] = 'Reason is required';
            $messages['remarks'.$i.'.check_remarks'] = 'Enter Valid Remarks';
            $messages['remarks'.$i.'.max'] = 'Remarks may not be greater than 20 letters';
        }

        return $messages;
    }
}
