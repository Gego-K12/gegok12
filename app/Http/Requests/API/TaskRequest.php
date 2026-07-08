<?php

namespace App\Http\Requests\API;

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
        Validator::extend('check_task_date', function ($attribute, $value, $parameters, $validator) {
            $task_date = date('Y-m-d H:i:s', strtotime(request('task_date')));
            if ($task_date > date('Y-m-d H:i:s')) {
                return true;
            }

            return false;
        });

        Validator::extend('check_title', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z\s]+$/', request('title'));
        });

        Validator::extend('check_to_do_list', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('to_do_list'));
        });

        $rules = [
            //
            'title' => 'required|max:25|check_title',
            'to_do_list' => 'required|max:100|check_to_do_list',
            'task_date' => 'required|date|check_task_date',
            'reminder' => 'required',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'title.required' => 'Title Is Required',
            'title.max' => 'Title Should Not Be Greater Than 25 Characters',
            'title.check_title' => 'Enter Valid Title',

            'to_do_list.required' => 'Description Is Required',
            'to_do_list.max' => 'Description Should Not Be Greater Than 100 Characters',
            'to_do_list.check_to_do_list' => 'Enter Valid Description',

            'task_date.required' => 'Task Date Is Required',
            'task_date.check_task_date' => 'Enter Valid Task Date',

            'reminder.required' => 'Reminder Is Required',
        ];
    }
}
