<?php

namespace App\Http\Requests;

use App\Models\School;
use App\Models\User;
use Hash;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class TeacherLoginRequest extends FormRequest
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
        $this->user = User::where('mobile_no', request('email'))->where('usergroup_id', 5)->first();

        Validator::extend('check_school', function ($attribute, $value, $parameters, $validator) {
            $user = $this->user;
            $school = School::IsActive($user->school_id)->exists();

            if ($school == false) {
                return false;
            } else {
                return true;
            }
        });

        Validator::extend('check_status', function ($attribute, $value, $parameters, $validator) {
            $user = $this->user;
            if ($user != null) {
                if ($user->status == 'active') {
                    return true;
                } else {
                    return false;
                }
            }
        });

        Validator::extend('check_exit', function ($attribute, $value, $parameters, $validator) {
            $user = $this->user;
            if ($user != null) {
                if ($user->status == 'exit') {
                    return false;
                } else {
                    return true;
                }
            }
        });

        Validator::extend('check_user', function ($attribute, $value, $parameters, $validator) {
            $user = $this->user;
            if (is_null($user)) {
                return false;
            }

            return true;
        });

        Validator::extend('check_password', function ($attribute, $value, $parameters, $validator) {
            $user = $this->user;
            if ($user != null) {
                if (Hash::check(request('password'), $user->password)) {
                    return true;
                }

                return false;
            }
        });

        Validator::extend('check_usergroup_id', function ($attribute, $value, $parameters, $validator) {
            $user = $this->user;
            if ($user != null) {
                if ($user->usergroup_id == 5) {
                    return true;
                }

                return false;
            }
        });

        Validator::extend('check_device_id', function ($attribute, $value, $parameters, $validator) {
            if ($user != null) {
                if ($this->user->device_id == null) {
                    return true;
                }

                return false;
            }
        });

        $rules =
        [
            'email' => 'bail|check_user|check_school|check_exit|check_status|check_password|check_usergroup_id|required',
            'password' => 'required',
            // 'device_id' =>'required|check_device_id',
        ];

        return $rules;
    }

    public function messages()
    {
        $messages =
        [
            'email.check_school' => 'Contact School for more Details',
            'email.check_user' => 'Mobile  number is not registered.Contact School for more Details',
            'email.check_status' => 'User Account Suspended. Please Contact School',
            'email.check_exit' => 'You have exited this school',
            'email.check_password' => 'Incorrect Password',
            'email.check_usergroup_id' => 'Invalid Usergroup',
            'password.required' => 'Password is required',
            'device_id.check_device_id' => 'Your account is currently logged onto another device. Please log out of the other device or contact your administrator ',
        ];

        return $messages;
    }
}
