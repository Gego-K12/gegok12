<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Livewire\Admin;

use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ChangeCredential extends Component
{
    use Common, LogActivity;

    public string $name;

    public bool $showModal = false;

    public string $email = '';

    public string $mobile_no = '';

    public $usergroup_id;

    public function mount(string $name)
    {
        $this->name = $name;
        $this->loadCredentials();
    }

    public function openModal(): void
    {
        $this->loadCredentials();
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetErrorBag();
    }

    protected function loadCredentials(): void
    {
        $user = User::where('name', $this->name)->where('school_id', Auth::user()->school_id)->first();

        $this->email = $user->email ?? '';
        $this->mobile_no = $user->mobile_no ?? '';
        $this->usergroup_id = $user->usergroup_id;
    }

    protected function rules(): array
    {
        $mobileRule = [
            'numeric',
            'digits:10',
            Rule::unique('users', 'mobile_no')
                ->where('school_id', Auth::user()->school_id)
                ->where('usergroup_id', $this->usergroup_id)
                ->ignore($this->name, 'name'),
        ];

        $emailRule = [
            'email',
            Rule::unique('users', 'email')
                ->where('school_id', Auth::user()->school_id)
                ->ignore($this->name, 'name'),
        ];

        // usergroup 7 = student, 5/8/10/11/12 = teacher/staff-type groups that
        // require both fields; everyone else keeps them optional -- mirrors
        // CredentialsUpdateRequest's original per-usergroup requirement rules.
        if ($this->usergroup_id == 7) {
            return [
                'mobile_no' => array_merge(['required'], $mobileRule),
                'email' => array_merge(['nullable'], $emailRule),
            ];
        }

        if (in_array($this->usergroup_id, [5, 8, 10, 11, 12])) {
            return [
                'mobile_no' => array_merge(['required'], $mobileRule),
                'email' => array_merge(['required'], $emailRule),
            ];
        }

        return [
            'mobile_no' => array_merge(['nullable'], $mobileRule),
            'email' => array_merge(['nullable'], $emailRule),
        ];
    }

    protected function messages(): array
    {
        return [
            'mobile_no.unique' => 'Mobile Number Already exists',
            'email.unique' => 'Email Already exists',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'mobile_no' => 'Mobile Number',
            'email' => 'Email',
        ];
    }

    public function submit()
    {
        $this->validate();

        $user = User::where('name', $this->name)
            ->where('usergroup_id', $this->usergroup_id)
            ->where('school_id', Auth::user()->school_id)
            ->first();

        $user->tokens()->delete();
        $user->email = $this->email !== '' ? $this->email : null;
        $user->mobile_no = $this->mobile_no !== '' ? $this->mobile_no : null;
        $user->platform_token = null;
        $user->device_id = null;
        $user->save();

        $message = __('admin_userprofile.credentials_update');

        $this->doActivityLog(
            $user,
            Auth::user(),
            ['ip' => $this->getRequestIP(), 'details' => request()->userAgent()],
            LOGNAME_CHANGE_CREDENTIALS,
            $message
        );

        session()->put('successmessage', $message);

        return $this->redirect('/admin/student/show/'.$this->name);
    }

    public function render()
    {
        return view('livewire.admin.change-credential');
    }
}
