<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class SendMailRequest extends FormRequest
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
        Validator::extend('checkmessage', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('message'));
        });

        $rules = [
            //
            'subject' => 'required|max:100',
            'message' => 'required|max:1000',
            'attachments' => 'nullable|mimes:pdf,jpg,jpeg,png,bmp,doc,csv,txt,xlsx,xls,docx,ppt,',
        ];

        if (request('send_later') == 'true') {
            $rules['executed_at'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'subject.required' => 'Subject is required',
            'subject.max:30' => 'Subject should be atmost 30 characters',
            'message.required' => 'Message is required',
            'message.checkmessage' => 'Enter a Valid Message',
            'message.max:150' => 'Message should be atmost 1000 characters',
            'executed_at.required' => 'Select Date and Time',
            'attachments.mimes' => 'Choose Proper file.The required extensions are :".pdf",".jpg",".jpeg",".png",".bmp",".doc",".csv",".txt",".xlsx",".xls",".docx",".ppt" ',
        ];
    }
}
