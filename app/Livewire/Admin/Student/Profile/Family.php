<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Livewire\Admin\Student\Profile;

use App\Http\Resources\UserRelation as UserRelationResource;
use App\Models\User;
use Livewire\Component;

class Family extends Component
{
    public string $name;

    public array $parents = [];

    public function mount(string $name)
    {
        $this->name = $name;

        $student = User::with('userprofile')->where('name', $name)->first();

        $this->parents = $student->parents
            ->map(fn ($parent) => (new UserRelationResource($parent))->toArray(request()))
            ->all();
    }

    public function render()
    {
        return view('livewire.admin.student.profile.family');
    }
}
