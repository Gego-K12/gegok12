<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Livewire\Admin\Student\Profile;

use App\Http\Resources\AttendanceUser as AttendanceUserResource;
use App\Models\User;
use Livewire\Component;

class Attendance extends Component
{
    public string $name;

    public array $attendances = [];

    public function mount(string $name)
    {
        $this->name = $name;

        $student = User::where('name', $name)->first();

        $this->attendances = $student->AttendanceUserAbsent
            ->map(fn ($item) => (new AttendanceUserResource($item))->toArray(request()))
            ->all();
    }

    public function render()
    {
        return view('livewire.admin.student.profile.attendance');
    }
}
