<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Livewire\Admin\Student\Profile;

use App\Http\Resources\API\BookLending as BookLendingResource;
use App\Models\User;
use Livewire\Component;

class LibraryActivity extends Component
{
    public string $name;

    public array $lent = [];

    public function mount(string $name)
    {
        $this->name = $name;

        $student = User::with('lending')->where('name', $name)->first();

        $this->lent = $student->lending
            ->map(fn ($item) => (new BookLendingResource($item))->toArray(request()))
            ->all();
    }

    public function render()
    {
        return view('livewire.admin.student.profile.library-activity');
    }
}
