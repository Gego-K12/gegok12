<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class TeacherQualificationUpdateRequest extends FormRequest
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
        $rules = [];

        Validator::extend('check_sub_qualification', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z_~\-!@#\$%\^&*.,:(\)\s]+$/', request('sub_qualification'));
        });

        Validator::extend('check_specialization', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z_~\-!@#\$%\^&*.,:(\)\s]+$/', request('specialization'));
        });

        /*$rules =
        [
            //
            'ug_degree' => 'required',
        ];*/

        /* for($i=0;$i<Request('count');$i++)
         {
             $rules['qualification_id'.$i] = 'required';

             if(Request('qualification_id'.$i) == 1)
             {
                 $rules['sub_qualification'] = 'required|check_sub_qualification';
             }
         }
*/
        if ((Request('ug_degree')) || (Request('pg_degree'))) {
            $rules['specialization'] = 'required|check_specialization';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];

        /* for($i=0;$i<Request('count');$i++)
         {
             $messages['qualification_id'.$i.'.required'] = 'Qualification is required';
         }*/

        $messages =
        [
            'ug_degree.required' => 'UG Degree is required',

            'sub_qualification.required' => 'Sub Qualification is required',
            'sub_qualification.check_sub_qualification' => 'Enter a Valid Sub Qualification',

            'specialization.required' => 'Specialization is required',
            'specialization.check_specialization' => 'Enter a Valid Specialization',
        ];

        return $messages;
    }
}
