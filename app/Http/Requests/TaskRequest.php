<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class TaskRequest extends FormRequest
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
        Validator::extend('check_task_date',function($attribute,$value,$parameters,$validator)
        { 
            $task_date = date('Y-m-d H:i:s',strtotime(request('task_date')));
            if( $task_date > date('Y-m-d H:i:s') )
            {
                return true;
            } 
            return false;
        });

        Validator::extend('check_student_count',function($attribute,$value,$parameters,$validator)
        {

            if( request('selectedUsersCount') > 0 )
            {
                return true;
            }
            return false;
        });

        Validator::extend('check_teacher_count',function($attribute,$value,$parameters,$validator)
        {
            if(request('selectedTeachersCount') > 0 ) //if( count(request('selectedTeachersCount')) > 0 )
            {
                return true;
            }
            return false;
        });

        Validator::extend('check_title',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z\s]+$/', request('title')) ;
        });

        Validator::extend('check_to_do_list',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('to_do_list')) ;
        });

        $rules = [
            //
            'assignee'      =>  'required',
            'title'         =>  'required|max:100',
            'to_do_list'    =>  'required|max:500',
            'task_date'     =>  'required|date|check_task_date',
            'reminder'      =>  'required',
            'priority'      =>  'required',
        ];

        // if(request('assignee') == 'class')
        // {
        //     $rules['standardLink_id'] = 'required';
        // }
        elseif (request('assignee') == 'student') 
        {
            $rules['standardLink_id']       = 'required';
            $rules['selectedUsersCount']    = 'check_student_count';
        }
        elseif (request('assignee') == 'teacher') 
        {
            $rules['selectedTeachersCount'] = 'check_teacher_count';
        }

        return $rules;
    }

    public function messages()
    {
        return[
            'assignee.required'                         =>  'Assign To Is Required',

            'title.required'                            =>  'Title Is Required',
            'title.max'                                 =>  'Title Should Not Be Greater Than 25 Characters',
            'title.check_title'                         =>  'Enter Valid Title',

            'to_do_list.required'                       =>  'Description Is Required',
            'to_do_list.max'                            =>  'Description Should Not Be Greater Than 100 Characters',
            'to_do_list.check_to_do_list'               =>  'Enter Valid Description',

            'task_date.required'                        =>  'Task Date Is Required',
            'task_date.check_task_date'                 =>  'Enter Valid Task Date',

            'standardLink_id.required'                  =>  'Class Is Required', 

            'selectedUsersCount.check_student_count'    =>  'Select Atleast One Student',

            'selectedTeachersCount.check_teacher_count'              =>  'Select Atleast One Teacher', 
            
            'reminder.required'                         =>  'Reminder Is Required', 
        ];
    }
}