<?php

namespace App\Http\Requests\API;

use App\Helpers\SiteHelper;
use App\Models\TeacherLeaveApplication;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StudentLeaveUpdateRequest extends FormRequest
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
            return preg_match("/^[A-Za-z_~\-!@#\$%\^&*.,':(\)\s]+$/", request('remarks'));
        });

        Validator::extend('check_from_date_exists', function ($attribute, $value, $parameters, $validator) {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);
            $id = (int) request('id');

            $from_date = date('Y-m-d', strtotime(request('from_date')));
            $application = TeacherLeaveApplication::where([
                ['id', '!=', $id],
                ['user_id', Auth::id()],
                ['school_id', $school_id],
                ['academic_year_id', $academic_year->id],
                ['status', 'pending'],
                [\DB::raw("(DATE_FORMAT(from_date,'%Y-%m-%d'))"), '=', $from_date],
            ])->orWhere([
                ['id', '!=', $id],
                ['user_id', Auth::id()],
                ['school_id', $school_id],
                ['academic_year_id', $academic_year->id],
                ['status', 'pending'],
                [\DB::raw("(DATE_FORMAT(to_date,'%Y-%m-%d'))"), '=', $from_date],
            ])->latest()->first();

            if ($application == null) {
                return true;
            }

            return false;
        });

        Validator::extend('check_from_date', function ($attribute, $value, $parameters, $validator) {
            $from_date = date('Y-m-d H:i:s', strtotime(request('from_date')));
            $yesterday = Carbon::now()->subDay(1)->format('Y-m-d 00:00:00');
            $today = date('Y-m-d H:i:s');
            if ($yesterday >= $from_date) {
                return true;
            } elseif ($from_date >= $today) {
                return true;
            }

            return false;
        });

        Validator::extend('check_to_date', function ($attribute, $value, $parameters, $validator) {
            $to_date = date('Y-m-d H:i:s', strtotime(request('to_date')));
            $yesterday = Carbon::now()->subDay(1)->format('Y-m-d 00:00:00');
            $today = date('Y-m-d H:i:s');
            if ($yesterday >= $to_date) {
                return true;
            } elseif ($to_date >= $today) {
                return true;
            }

            return false;
        });

        Validator::extend('check_start_date', function ($attribute, $value, $parameters, $validator) {

            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);
            $student_id = (int) request('student_id');
            $id = (int) request('id');
            $studentLeave = TeacherLeaveApplication::find($id);
            $from_date = date('Y-m-d H:i:s', strtotime(request('from_date')));
            $to_date = date('Y-m-d H:i:s', strtotime(request('to_date')));

            $application = TeacherLeaveApplication::where([
                ['id', '!=', $id],
                ['user_id', $studentLeave->user_id],
                ['school_id', $school_id],
                ['academic_year_id', $academic_year->id],
                ['status', 'pending'],
            ])->ByDate($from_date, $to_date)->exists();

            if ($application) {

                return false;
            }

            return true;
        });

        Validator::extend('check_end_date', function ($attribute, $value, $parameters, $validator) {

            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);
            $student_id = (int) request('student_id');
            $id = (int) request('id');
            $studentLeave = TeacherLeaveApplication::find($id);
            $from_date = date('Y-m-d H:i:s', strtotime(request('from_date')));
            $to_date = date('Y-m-d H:i:s', strtotime(request('to_date')));

            $application = TeacherLeaveApplication::where([
                ['id', '!=', $id],
                ['user_id', $studentLeave->user_id],
                ['school_id', $school_id],
                ['academic_year_id', $academic_year->id],
                ['status', 'pending'],
            ])->ByDate($from_date, $to_date)->exists();

            if ($application) {

                return false;
            }

            return true;
        });

        $rules = [
            //
            // 'from_date'     =>  'required|check_from_date|check_from_date_exists',
            'from_date' => 'required|check_from_date|check_start_date',
            'to_date' => 'required|after:from_date|check_to_date|check_end_date',
            'reason_id' => 'required',
            'remarks' => 'required',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            //
            'from_date.required' => 'From Date Is Required',

            'from_date.check_from_date' => 'Enter Valid From Date',

            'from_date.check_from_date_exists' => 'Leave Request Already Exists For This Date',

            'from_date.check_start_date' => 'Leave Request Already Exists For This Date',

            'to_date.check_end_date' => 'Leave Request Already Exists For This Date',

            'to_date.required' => 'To Date Is Required',

            'to_date.after' => 'To Date Sould Be Greater Than From date',

            'to_date.check_to_date' => 'Enter Valid To Date',

            'reason_id.required' => 'Reason Is Required',

            'remarks.required' => 'Remarks Is Required',

            'remarks.check_remarks' => 'Enter Valid Remarks',
        ];
    }
}
