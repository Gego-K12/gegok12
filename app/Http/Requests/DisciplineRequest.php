<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DisciplineRequest extends FormRequest
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
        Validator::extend('check_incident_detail',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z_~\-!@#\$%\^&*.,:(\)\s]+$/', request('incident_detail')) ;
        });

        // Validator::extend('check_incident_date',function($attribute,$value,$parameters,$validator)
        // {
        //     if( request('incident_date') <= date('d-m-Y H:i:s'))
        //     {
        //         return true;
        //     }
        //     return false;
        // });

        // Validator::extend('check_incident_date', function ($attribute, $value, $parameters, $validator) {

        //     try {
        //         $incidentDate = Carbon::createFromFormat('d-m-Y H:i:s', $value);
        //         $currentDate  = Carbon::now();

        //         return $incidentDate->lessThanOrEqualTo($currentDate);

        //     } catch (\Exception $e) {
        //         return false;
        //     }
        // });

        return [
            //
            //'student_id'        =>  'required',
            'incident_date'     =>  'required|date|before_or_equal:now',
            'teacher_id'        =>  'required',
            'incident_detail'   =>  'required|check_incident_detail',
            'action_taken'      =>  'required',
            'notify_parents'    =>  'required',
            'attachments'       =>  'nullable|mimes:pdf|max:8092',
        ];
    }

    public function messages()
    {
        return [
            //
            //'student_id.required'                   =>  'Select Student',

            'incident_date.required'                =>  'Select Incident Date',
            'incident_date.check_incident_date'     =>  'Select Valid Incident Date',

            'teacher_id.required'                   =>  'Select Teacher',

            'incident_detail.required'              =>  'Incident Detail is required',
            'incident_detail.check_incident_detail' =>  'Enter a Valid Incident Detail',

            'action_taken.required'                 =>  'Action Taken is required',

            'notify_parents.required'               =>  'Notify Parents in required',

            'attachments.mimes'                     =>  'Choose a PDF File',
            'attachments.max'                       =>  'Maximum file size to upload is 8MB',
        ];
    }
}
