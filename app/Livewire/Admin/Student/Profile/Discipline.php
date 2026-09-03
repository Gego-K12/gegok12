<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Livewire\Admin\Student\Profile;

use App\Http\Resources\Discipline as DisciplineResource;
use App\Models\User;
use Livewire\Component;

class Discipline extends Component
{
    public string $name;

    public array $disciplines = [];

    public function mount(string $name)
    {
        $this->name = $name;

        $student = User::with('disciplineUser', 'disciplineTeacher')->where('name', $name)->first();

        $this->disciplines = $student->disciplineUser
            ->map(fn ($item) => (new DisciplineResource($item))->toArray(request()))
            ->all();
    }

    public function render()
    {
        return view('livewire.admin.student.profile.discipline');
    }
}
