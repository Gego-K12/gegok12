<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\StudentAssignment;
use App\Models\Assignment;

class StudentAssignmentAddRequest extends FormRequest
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
        Validator::extend('check_assignment_exists', function ($attribute, $value, $parameters, $validator) 
        {
            $student_id = (int)request('student_id');
            $assignment_id = (int)request('assignment_id');
            $studentassignment = StudentAssignment::where([['assignment_id',$assignment_id],['user_id',$student_id],['deleted_at',null]])->first();
    
            if( $studentassignment == null )
            {
                return true;
            }
            return false;
        });

        Validator::extend('check_date', function ($attribute, $value, $parameters, $validator) 
        {
            $id = (int)request('assignment_id');
            $assignment = Assignment::where('id',$id)->first();
            $date = date('Y-m-d',strtotime($assignment->submission_date));
    
            if( date('Y-m-d') > $date)
            {
                return false;
            }
            return true;
        });

        return [
            //
            'assignment_file'   =>  'required|file|mimes:pdf,jpg,png|max:8192|check_date|check_assignment_exists',
        ];
    }

    public function messages()
    {
        return
        [   
            'assignment_file.required'                  =>  'Assignment File Is Required', 
            'assignment_file.mimes'                     =>  'Choose A Pdf File', 
            'assignment_file.max'                       =>  'Maximum File Size To Upload Is 8MB',
            'assignment_file.check_date'                =>  'Submission Date Already Expired',
            'assignment_file.check_assignment_exists'   =>  'Assignment File Already Exists',
        ];
    }
}