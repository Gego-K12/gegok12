<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SiteHelper;
use App\Models\Assignment;

class AssignmentUpdateRequest extends FormRequest
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
        $status = request('status');

        Validator::extend('check_title', function ($attribute, $value,$parameters,$validator) 
        {
            //return preg_match('/^[\pL\s]+$/u', request('title')); 
            return preg_match('/\pL\pM*|./u', request('title')); 
        });

        Validator::extend('check_assigned_date', function ($attribute, $value, $parameters, $validator) 
        {
            if( date('Y-m-d',strtotime(request('assigned_date'))) >date('Y-m-d',strtotime('-1 days',strtotime(date('Y-m-d')))))
            {
                return true;
            }
            return false;
        });

        Validator::extend('check_marks', function ($attribute, $value, $parameters, $validator) 
        {
            if( request('marks') < 100)
            {
                return true;
            }
            return false;
        });

        Validator::extend('check_valid_marks', function ($attribute, $value, $parameters, $validator) 
        {
            return preg_match('/^[0-9]+$/', request('marks')) ;
        });

        Validator::extend('check_exists', function ($attribute, $value, $parameters, $validator) 
        {
            $assigned_date = date('Y-m-d',strtotime(request('assigned_date')));
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);
            $assignment = Assignment::where([
                ['school_id',$school_id],
                ['academic_year_id',$academic_year->id],
                ['standardLink_id',request('standardLink_id')],
                ['subject_id',request('subject_id')],
                ['assigned_date',$assigned_date]
            ])->first();
            if( $assignment == null )
            {
                return true;
            }
            return false;
        });

         $rules = [
            'status' => 'required|in:pending,ongoing',

            'title'        => 'nullable|max:50|check_title',
            'description'  => 'nullable|max:255',

            'attachment'   => 'nullable|mimes:jpeg,png,jpg,pdf',

            'marks'        => 'nullable',

            'assigned_date'   => 'nullable|check_assigned_date|check_exists',
            'submission_date' => 'nullable',
        ];

        if ($status === 'ongoing') {

            $rules['title'] = 'required|max:50|check_title'; 
            $rules['description'] = 'required|max:255';     

            $rules['marks'] = 'required|numeric|check_marks|check_valid_marks';

            $rules['assigned_date'] = 'required|check_assigned_date|before:submission_date';
            $rules['submission_date'] = 'required|after:assigned_date';
        }

        if(\Request('attachment')!= '')
        { 
            //$rules['attachment']='nullable|mimes:pdf|max:8092';
            $rules['attachment']='nullable|mimes:jpeg,png,jpg,pdf';
        }

        return $rules;
    }

    public function messages()
    {
        return
        [   
            'title.required'                    =>  'Title is required',
            'title.max'                         =>  'Title cannot be more than 50 characters',
            'title.check_title'                 =>  'Enter Valid Title',

            'description.required'              =>  'Description is required',
            'description.max'                   =>  'Description cannot be more than 255 characters',

            'attachment.mimes'                  =>  'Choose a Valid file', 
            'attachment.max'                    =>  'Maximum file size to upload is 8MB',

            'marks.numeric'                     =>  'Enter Valid Mark',
            'marks.check_marks'                 =>  'Mark cannot be more than 100',
            'marks.check_valid_marks'           =>  'Enter Valid Mark',

            'assigned_date.required'            =>  'Assigned Date Required',
            'assigned_date.check_assigned_date' =>  'Choose Valid Assigned Date',

            'submission_date.required'          =>  'Submission Date Required',
        ];
    }
}