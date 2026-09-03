<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Livewire\Admin\Student\Profile;

use App\Http\Resources\ActivityLog as ActivityLogResource;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class Timeline extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public string $name;

    public function mount(string $name)
    {
        $this->name = $name;

        $user = User::with('userprofile')->where('name', $name)->first();

        abort_unless(Gate::allows('member', $user), 403);
    }

    public function render()
    {
        $user = User::with('userprofile')->where('name', $this->name)->first();

        $logs = ActivityLog::where('subject_id', $user->userprofile->id)
            ->orWhere('subject_id', $user->members[0]['id'])
            ->paginate(5)
            ->through(fn ($log) => (new ActivityLogResource($log))->toArray(request()));

        return view('livewire.admin.student.profile.timeline', ['logs' => $logs]);
    }
}
