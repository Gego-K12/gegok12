<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Salary;
use App\Models\Payroll;

class PayrollDetailRequest extends FormRequest
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
         Validator::extend('checksalary', function ($attribute, $value, $parameters, $validator) 
        {
             $salary=Salary::where([['school_id',Auth::user()->school_id],['staff_id',$value],['effective_date','>=',date('Y-m-d')]])->first();

            if(!$salary)
            { 
                return FALSE;
            }
            else
            {
                if($salary->effective_date>= date('Y-m-d')){
                 return true;
              }
                return FALSE;
            }      
        });
        

     Validator::extend('check_start_date', function ($attribute, $value, $parameters, $validator) {

        $start_date=request('start_date');
        $end_date=request('end_date');

        
        $events=Payroll::where('staff_id',request('staff_id'))->ByDate($start_date,$end_date)->exists();
       

        if($events)

        {
          
          return FALSE;
        }
       
        return TRUE;
      });

     Validator::extend('check_end_date', function ($attribute, $value, $parameters, $validator) {


         $start_date=request('start_date');
         $end_date=request('end_date'); 


        $events=Payroll::where('staff_id',request('staff_id'))->ByDate($start_date,$end_date)->exists();
        
        if($events)

        {
          
          return FALSE;
        }
       
        return TRUE;
      });

        $rules=[];
          $rules['staff_id']='required|checksalary';
          $rules['start_date']='required|check_start_date';
          $rules['end_date']='required|after_or_equal:start_date|check_end_date';
         
           return $rules;
    }

    public function messages()
    {
        return [
             'staff_id.required'=>'Please select staff',
            'staff_id.checksalary'=>'Staff doesn`t have salary structure',
            'start_date.check_start_date'=>'Salary period already exists',
            'end_date.check_end_date'=>'Salary period already exists',

        ];
    }
}
