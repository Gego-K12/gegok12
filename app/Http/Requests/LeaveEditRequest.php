<?php

namespace App\Http\Requests;

use App\Helpers\SiteHelper;
use App\Models\TeacherLeaveApplication;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeaveEditRequest extends FormRequest
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
        Validator::extend('check_remarks', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z_~\-!@#\$%\^&*.,:(\)\s]+$/', $attribute);
        });

        Validator::extend('check_from_date_exists', function ($attribute, $value, $parameters, $validator) {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);

            $from_date = date('Y-m-d', strtotime(request('from_date')));

            $application = TeacherLeaveApplication::where([
                ['id', '!=', request('id')],
                ['user_id', Auth::id()],
                ['school_id', $school_id],
                ['academic_year_id', $academic_year->id],
                ['status', 'pending'],
            ])
                ->where(function ($query) use ($from_date) {
                    $query->whereDate('from_date', $from_date)
                        ->orWhereDate('to_date', $from_date);
                })
                ->latest()
                ->first();

            if ($application == null) {
                return true;
            }

            return false;
        });

        Validator::extend('check_from_date', function ($attribute, $value, $parameters, $validator) {
            $academic_year = SiteHelper::getAcademicYear($school_id);
            $start_date = date('Y-m-d H:i:s', strtotime($academic_year->start_date));
            $end_date = date('Y-m-d H:i:s', strtotime($academic_year->end_date));
            $from_date = date('Y-m-d H:i:s', strtotime(request('from_date')));

            if (($start_date <= $from_date) || ($end_date >= $from_date)) {
                return true;
            }

            return false;
        });

        Validator::extend('check_to_date', function ($attribute, $value, $parameters, $validator) {
            $academic_year = SiteHelper::getAcademicYear($school_id);
            $start_date = date('Y-m-d H:i:s', strtotime($academic_year->start_date));
            $end_date = date('Y-m-d H:i:s', strtotime($academic_year->end_date));
            $to_date = date('Y-m-d H:i:s', strtotime(request('to_date')));

            if (($start_date <= $to_date) || ($end_date >= $to_date)) {
                return true;
            }

            return false;
        });

        return [
            //
            'from_date' => 'required|check_from_date|check_from_date_exists',
            'to_date' => 'required|after:from_date|check_to_date',
            'reason_id' => 'required',
            'remarks' => 'nullable|check_remarks',
            'leave_type_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            //
            'from_date.required' => 'From Date is required',

            'from_date.check_from_date' => 'Enter Valid From Date',

            'from_date.check_from_date_exists' => 'Leave Request Already Exists For This Date',

            'to_date.required' => 'To Date is required',

            'to_date.after' => 'To Date should be greater than From date',

            'to_date.check_to_date' => 'Enter Valid To Date',

            'reason_id.required' => 'Reason is required',

            'remarks.check_remarks' => 'Enter Valid Remarks',

            'leave_type_id.required' => 'Leave Type is required',
        ];
    }
}
