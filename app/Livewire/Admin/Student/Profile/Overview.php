<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Livewire\Admin\Student\Profile;

use App\Http\Resources\UserDetail as UserDetailResource;
use App\Models\User;
use Livewire\Component;

class Overview extends Component
{
    public string $name;

    public array $details = [];

    public $entityId;

    public $schoolId;

    public function mount(string $name)
    {
        $this->name = $name;

        $user = User::with('userprofile')->where('name', $name)->first();

        $this->details = (new UserDetailResource($user))->toArray(request());
        $this->entityId = $user->id;
        $this->schoolId = $user->school_id;
    }

    public function render()
    {
        return view('livewire.admin.student.profile.overview');
    }
}
