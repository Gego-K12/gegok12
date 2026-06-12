<?php

namespace App\Http\Requests\Classwall;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PostRequest extends FormRequest
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
        Validator::extend('check_description',function ($attribute,$value,$parameters,$validatior)
        {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', $attribute);
        });


        // Validator::extend('check_posted_at',function($attribute,$value,$parameters,$validator)
        // { 
        //     if ( request('posted_at') > date('d-m-Y H:i:s') )
        //     {
        //         return true;
        //     }
        //     return false;
        // });

         Validator::extend('check_posted_at', function ($attribute, $value, $parameters, $validator) {
            // dd($value);
            if (!$value) {
                return false;
            }

            try {
                // Your input format: 05/16/2026 13:03
                $postedAt = Carbon::createFromFormat('m/d/Y, H:i', $value);

                return $postedAt->greaterThan(now());
            } catch (\Exception $e) {
                return false;
            }
        });

        $rules = [
            //
            //'description'   =>  'required|max:500|check_description',
            'description'   =>  'required',
            'visibility'    =>  'required',
        ];

        if(request('visibility') == 'select_class')
        {
            $rules['visible_for']   =   'required';
        }

        if(request('post_later') == 'true')
        {
            $rules['posted_at'] = 'check_posted_at';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            //
            'description.required'          =>  'Description is required',
            'description.max'               =>  'Description cannot be more than 500 characters',
            'description.check_description' =>  'Enter Valid Description',

            'visibility.required'           =>  'Visibility is required',

            'visible_for.required'          =>  'Select Class is required',

            'posted_at.check_posted_at'     =>  'Enter Future Date Time',
        ];

        return $messages;
    }
}