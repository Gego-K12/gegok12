<?php

namespace App\Http\Requests\Classwall;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostAttachmentRequest extends FormRequest
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
    public function rules(Request $request)
    {
        Validator::extend('check_count', function ($attribute, $value, $parameters, $validator) {
            if (request('count') > 0) {
                $count = (int) request('count');
                $file_count = count(request('file'));
                $total_count = $count + $file_count;

                if ($total_count > 6) {
                    return false;
                }
            }

            return true;
        });

        if (request('count') != null) {
            $rules = [
                'count' => 'nullable|check_count',
            ];
        } else {
            $rules = [
                //
            ];
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            //
            'count.check_count' => 'Attachment images cannot be more than 6',
        ];

        return $messages;
    }
}
