<?php

namespace App\Http\Requests\API;

use App\Models\Authentication;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class ResetChangePasswordRequest extends FormRequest
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
        Validator::extend('checkpassword', function ($attribute, $value, $parameters, $validator) {
            // $user = User::where('mobile_no',request('mobile_no'))->first();
            $user = User::where('mobile_no', request('mobile_no'))->where('usergroup_id', request('usergroup'))->first();

            $authentication = Authentication::where([
                ['user_id', $user->id],
                ['status', 1],
                ['type', 'reset'],
            ])->orderBy('id', 'DESC')->get();

            if ($authentication[0]['token'] == request('oldpassword')) {
                return true;
            }

            return false;
        });

        return [
            'oldpassword' => 'required|checkpassword',
            'newpassword' => 'required|min:8',
            'confirmpassword' => 'required|same:newpassword',
            'usergroup' => 'required',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'oldpassword.required' => __('userprofile.oldpassword'),
            'oldpassword.checkpassword' => __('userprofile.oldpassword_err'),
            'newpassword.required' => __('userprofile.newpassword'),
            'newpassword.min:8' => __('userprofile.newpassword_min'),
            'confirmpassword.required' => __('userprofile.confirmpassword'),
            'confirmpassword.same' => __('userprofile.same_confirmpassword'),
        ];
    }
}
