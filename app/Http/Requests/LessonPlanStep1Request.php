<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class LessonPlanStep1Request extends FormRequest
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
        Validator::extend('check_unit_name',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z\s]+$/', request('unit_name'));
        });

        Validator::extend('check_title',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', $attribute);
        });

        Validator::extend('check_description',function($attribute,$value,$parameters,$validator)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', $attribute);
        });
        
        $rules =
        [
            //
            'unit_no'               => 'required|numeric',
            'unit_name'             => 'required|check_unit_name',
            'duration'              => 'required|numeric',
            'title'                 => 'required|check_title|max:50',
            'description'           => 'required|check_description',
            // 'start_date'            => 'required|date|after_or_equal:today',
            // 'end_date'              => 'required|date|after_or_equal:start_date',
        ];

        if(request('type') == 'add')
        {
            $rules['standardLink_id']   = 'required';
            $rules['subject_id']        = 'required';     
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'standardLink_id.required'                  => 'Class Is Required',

            'subject_id.required'                       => 'Subject Is Required',

            'unit_no.required'                          => 'Unit Number Is Required',
            'unit_no.numeric'                           => 'Unit Number Should Be Number',

            'unit_name.required'                        => 'Unit Name Is Required',
            'unit_name.check_unit_name'                 => 'Enter Valid Unit Name',

            'duration.required'                         => 'Duration Is Required',
            'duration.numeric'                          => 'Duration Should Be Number',

            'title.required'                            => 'Title Is Required',
            'title.check_title'                         => 'Enter Valid Title',
            'title.max'                                 => 'Title Cannot Be More Than 50 Characters',

            'description.required'                      => 'Unit Breakdown Is Required',
            'description.check_description'             => 'Enter Valid Unit Breakdown',
        ];
    }
}