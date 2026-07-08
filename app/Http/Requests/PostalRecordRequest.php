<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class PostalRecordRequest extends FormRequest
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

        Validator::extend('check_postal_date', function ($attribute, $value, $parameters, $validator) {
            if (request('postal_date') <= date('Y-m-d')) {
                return true;
            }

            return false;
        });

        return [
            //
            // 'name'          => 'required|max:20',
            'type' => 'required',
            'reference_number' => 'nullable',
            'confidential' => 'required',
            // 'post_type'=>'required',   //new
            'sender_title' => 'nullable',
            'sender_address' => 'nullable',
            'receiver_title' => 'nullable',
            'receiver_address' => 'nullable',
            'attachment' => 'nullable',
            'description' => 'required',
            'postal_date' => 'required|date|check_postal_date',

        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Type is required',
            // 'reference_number.required' =>  'Reference Number is Required',
            'confidential.required' => 'Mark confidential',
            'post_type.required' => 'Post Type Required',
            'sender_title.required' => 'Sender Title is required',
            'sender_address.required' => 'Sender Address is required',
            'receiver_title.required' => 'Receiver title is required',
            'receiver_address.required' => 'Receiver Address is required',
            'description.required' => 'Description required',
            'postal_date.required' => 'Select Date',
            'postal_date.check_postal_date' => 'Enter valid Postal Date',

        ];
    }
}
