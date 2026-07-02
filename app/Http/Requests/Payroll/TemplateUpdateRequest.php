<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\PayrollTemplate;

class TemplateUpdateRequest extends FormRequest
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
        Validator::extend('check_use', function ($attribute, $value, $parameters, $validator) 
        {
             $template=PayrollTemplate::find($value);

            if(count($template->salaries)>0)
            { 
                return FALSE;
            }
            else
            {
                return TRUE;
            }      
        });

         for ($i=0; $i < request('payrollscount') ; $i++)
          { 
            if(request('category_id'.$i)==4)
            {
             $rules['category_value'.$i]='required';
            }
             $rules['head_id'.$i]='required';
             $rules['category_id'.$i]='required';
          }

           $rules['template_id']='required|check_use';
           $rules['name']='required';
           $rules['status']='required|boolean';
         
           return $rules;
    }

    public function messages()
  {
    $messages['name.required']="Please Enter the name";
    $messages['status.required']="Please select the status"; 
    $messages['template_id.check_use']="Template already used";  
                     
    for($i=0 ; $i < Request('payrollscount') ; $i++)
    {
      $messages['category_id'.$i.'.required'] = 'Please select one';     
    }
    return $messages;
  }
}
