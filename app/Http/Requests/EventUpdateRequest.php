<?php

namespace App\Http\Requests;

use App\Models\Events;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class EventUpdateRequest extends FormRequest
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
        Validator::extend('checklocation', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('location'));
        });

        Validator::extend('check_freq', function ($attribute, $value, $parameters, $validator) {
            if (request('freq') == '0') {
                return false;
            }

            return true;
        });

        Validator::extend('check_freq_term', function ($attribute, $value, $parameters, $validator) {
            if (request('freq_term') == '0') {
                return false;
            }

            return true;
        });

        Validator::extend('alpha_spaces', function ($attribute, $value, $parameters, $validator) {
            // This will only accept alpha and spaces.
            // If you want to accept hyphens use: /^[\pL\s-]+$/u.
            return preg_match('/^[\pL\s]+$/u', request('title'));
        });

        Validator::extend('check_start_date', function ($attribute, $value, $parameters, $validator) {
            if (date('Y-m-d', strtotime(request('start_date'))) > date('Y-m-d', strtotime('-1 days', strtotime(date('Y-m-d'))))) {
                return true;
            }

            return false;
        });

        Validator::extend('check_start_time', function ($attribute, $value, $parameters, $validator) {
            $start_date = date('Y-m-d H:i:s', strtotime(request('start_date')));
            $end_date = date('Y-m-d H:i:s', strtotime(request('end_date')));
            $events = Events::where([['id', '!=', request('id')], ['standard_id', request('standard_id')]])->ByDate($start_date, $end_date)->exists();

            if ($events) {
                return false;
            }

            return true;
        });

        Validator::extend('check_end_time', function ($attribute, $value, $parameters, $validator) {
            $start_date = date('Y-m-d H:i:s', strtotime(request('start_date')));
            $end_date = date('Y-m-d H:i:s', strtotime(request('end_date')));

            $events = Events::ByDate($start_date, $end_date)->exists();

            if ($events) {
                return false;
            }

            return true;
        });

        $rules = [
            'title' => 'required|max:100|alpha_spaces',
            'description' => 'required|max:100',
            'repeats' => 'required',
            'location' => 'required|checklocation',
            'category' => 'required',
            'organised_by' => 'required',
            // 'image'      => 'required|mimes:jpg,png,jpeg',
            'start_date' => 'required|before_or_equal:end_date|check_start_date',
            'end_date' => 'required|after_or_equal:start_date',
        ];

        if (request('select_type') == 'class') {
            $rules['standard_id'] = 'required|check_start_time';
        }

        if (request('select_type') == 'alumni') {
            $rules['batch'] = 'required';
        }

        if (request('select_type') == 'school') {
            $rules['start_date'] = 'required|check_end_time';
        }

        if (request('repeats') == '1') {
            $rules['freq'] = 'required|check_freq';
            $rules['freq_term'] = 'required|check_freq_term';
        }

        /*if(request('image')!= '')
        {
            $rules['image']='nullable|mimes:jpg,jpeg,png';
        }*/

        return $rules;
    }

    public function messages()
    {
        return
        [
            'title.required' => 'Title Is Required',
            'title.alpha_spaces' => 'Enter Only Alphabets',

            'description.required' => 'Description Is Required',

            'repeats.required' => 'Select Repeats',

            'standard_id.required' => 'Class Is Required',
            'standard_id.check_start_time' => 'Event Already Exists For This Class',

            'freq.required' => 'Freq Is Required',
            'freq.check_freq' => 'Freq Is Required',

            'freq_term.required' => 'Freq Term Is Required',
            'freq_term.check_freq_term' => 'Freq Term Is Required',

            'location.required' => 'Location Is Required',
            'location.checklocation' => 'Enter A Valid Location',

            'category.required' => 'Event Category Is Required',

            'organised_by.required' => 'Organised By Is Required',

            'image.required' => 'Upload Image',
            'image.mimes' => 'File Extension Error',

            'start_date.after' => 'Please Select Upcoming Date',
            'start_date.required' => 'Start Date Is Required',
            'start_date.check_start_date' => 'Start Date Should Be After Yesterday',
            'start_date.check_end_time' => 'Already Scheduled',

            'end_date.required' => 'End Date Is Required',
            'end_date.checkunique_end' => 'End Date Should Be After Start Date',

            'batch.required' => 'Batch Is Required',
        ];
    }
}
