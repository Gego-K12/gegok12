<?php

namespace App\Http\Requests\API\Teacher;

use App\Helpers\SiteHelper;
use App\Models\Attendance;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttendanceAddRequest extends FormRequest
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
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
            $start_date = date('Y-m-d', strtotime($academic_year->start_date));
            $date = date('Y-m-d', strtotime(request('Date')));

            if (($date <= date('Y-m-d')) && ($date >= $start_date)) {
                return true;
            }

            return false;
        });

        Validator::extend('check_session', function ($attribute, $value, $parameters, $validator) {
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);
            $date = date('Y-m-d', strtotime(request('Date')));
            $Standardlinkid = (int) request('Standardlinkid');
            $attendance = Attendance::where([
                ['school_id', Auth::user()->school_id],
                ['academic_year_id', $academic_year->id],
                ['date', '=', $date],
                ['session', request('Session')],
                ['standardLink_id', $Standardlinkid],
            ])->exists();
            if ($attendance) {
                return false;
            }

            return true;
        });

        Validator::extend('check_user', function ($attribute, $value, $parameters, $validator) {
            $count = 0;
            for ($i = 0; $i < count(Request('AbsentList')); $i++) {
                if ($value == request('AbsentList.'.$i.'.student_id')) {
                    $count++;
                }
            }
            if ($count <= 1) {
                return true;
            }

            return false;
        });

        $rules =
        [
            //
            'Standardlinkid' => 'required',
            'Date' => 'required|date|check_date',
            'Session' => 'required|check_session',
            'AbsentList.*.student_id' => 'required|check_user',
            'AbsentList.*.reasonId' => 'required|numeric|max:200',
            // 'AbsentList.*.remarks' => 'required',
        ];

        return $rules;
    }

    public function messages()
    {
        $messages =
        [
            //
            'Standardlinkid.required' => 'Class Is Required',

            'Date.required' => 'Date Is Required',
            'Date.check_date' => 'Enter Valid Date',

            'Session.required' => 'Session Is Required',
            'Session.check_session' => 'Attendance Already Updated',
            'AbsentList.*.student_id.check_user' => 'Student Already exist',
        ];

        /*for($i=0 ; $i < count(Request('AbsentList')) ; $i++)
        {
            $messages['student_id'.$i.'.required']         = 'Student is required';
            $messages['reasonId'.$i.'.required']       = 'Reason is required';
            $messages['remarks'.$i.'.check_remarks']    = 'Enter Valid Remarks';
            $messages['remarks'.$i.'.max']              = 'Remarks may not be greater than 20 letters';
        }*/

        return $messages;
    }
}
