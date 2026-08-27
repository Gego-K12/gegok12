<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SiteHelper;
use App\Models\Timetable;

class TimetableUpdateRequest extends FormRequest
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
        Validator::extend('check_period_count',function($attribute,$value,$parameters,$validator)
        {  
            if( request('periodCount') < 10 )
            {
                return true;
            }
                
            return false;
        });

        $rules =
        [
            //
            'periodCount'       => 'numeric|check_period_count',
        ];

        for($i=0 ; $i<Request('count') ; $i++)
        {  
            $rules['day'.$i]    = 'required';
            for($j=0 ; $j<Request('periodCount') ; $j++)
            {   
                $request = request();
                Validator::extend('check_time',function($attribute,$value,$parameters,$validator) use($i,$j,$request)
                {  
                    $start = 'start_time'.$i.$j;
                    $end = 'end_time'.$i.$j;
                    //dd($start,$end);
                    $start_time = date('Y-m-d H:i:s',strtotime($request->$start));
                    $end_time   = date('Y-m-d H:i:s',strtotime($request->$end));
                    if($start_time < $end_time  )
                    {
                        return true;
                    }
                        
                    return false;
                });

                $rules['period'.$i.$j]         = 'required';
                $rules['subject_id'.$i.$j]     = 'required';
                $rules['teacher_name'.$i.$j]   = 'nullable';
                $rules['start_time'.$i.$j]     = 'required';
                $rules['end_time'.$i.$j]       = 'required';//|check_time';
            }
        }

        return $rules;
    }

    public function messages()
    {
        $messages = 
        [
            //
            'periodCount.numeric'               =>  'No. Of Period must be a number',
            'periodCount.check_period_count'    =>  'Total No. Of Period cannot be more than 9',
        ];

        for($i=0 ; $i < Request('count') ; $i++)
        {
            $messages['day'.$i.'.required']         = 'Day is required';
            for($j=0 ; $j<Request('periodCount') ; $j++)
            {
                $messages['period'.$i.$j.'.required']       = 'Period is required';
                $messages['subject_id'.$i.$j.'.required']   = 'Subject is required';
                $messages['start_time'.$i.$j.'.required']   = 'Start Time is required';
                $messages['end_time'.$i.$j.'.required']     = 'End Time is required';
                $messages['end_time'.$i.$j.'.check_time']   = 'End Time Cannot Be Less Than Start Time';
            }
        }

        return $messages;
    }
}