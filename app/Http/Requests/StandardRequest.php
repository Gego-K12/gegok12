<?php

namespace App\Http\Requests;

use App\Models\Standard;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StandardRequest extends FormRequest
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
        Validator::extend('check_unique_standard', function ($attribute, $value, $parameters, $validator) {
            $standard = Standard::where([['school_id', Auth::user()->school_id], ['name', '=', request('standard')]])->exists();
            if ($standard) {
                return false;
            }

            return true;
        });

        Validator::extend('check_standard', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[A-Za-z0-9_~\-!@#\$%\^&*.,:(\)\s]+$/', request('standard'));
        });

        Validator::extend('check_standard_value', function ($attribute, $value, $parameters, $validator) {
            if ((request('standard') < 13) || (strtolower(request('standard')) == 'prekg') || (strtolower(request('standard')) == 'lkg') || (strtolower(request('standard')) == 'ukg')) {
                return true;
            }

            return false;
        });

        $rules = [
            //
            'standard' => 'required|check_standard|check_standard_value|check_unique_standard',
        ];

        if (count(request('standardlist')) > 0) {
            $rules['position'] = 'required';
            $rules['ref_standard_id'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'standard.required' => 'Standard Name Is Required',
            'standard.check_standard' => 'Enter A Valid Standard Name',
            'standard.check_standard_value' => 'Enter A Valid Standard Name',
            'standard.check_unique_standard' => 'Standard Name Already Exists',

            'position.required' => 'Position Is Required',

            'ref_standard_id.required' => 'Standard Is Required',
        ];
    }
}
