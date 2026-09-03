<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Livewire\Admin\Student\Profile;

use App\Http\Resources\SiblingListResource;
use App\Models\StandardLink;
use App\Models\StudentAcademic;
use App\Models\StudentParentLink;
use App\Models\User;
use Livewire\Component;

class Siblings extends Component
{
    public string $name;

    public array $siblings = [];

    public array $siblingDetails = [];

    public function mount(string $name)
    {
        $this->name = $name;

        $student = User::with('userprofile')->where('name', $name)->first();

        $parentIds = StudentParentLink::where('student_id', $student->id)->pluck('parent_id')->toArray();
        $siblingLinks = StudentParentLink::where('student_id', '!=', $student->id)
            ->whereIn('parent_id', $parentIds)
            ->get()
            ->unique('student_id');

        $this->siblings = $siblingLinks
            ->map(fn ($link) => (new SiblingListResource($link))->toArray(request()))
            ->values()
            ->all();

        $academic = StudentAcademic::where('user_id', $student->id)->orderByDesc('id')->first();

        if ($academic !== null && $academic->siblings === 'yes' && $academic->sibling_details !== null) {
            foreach ($academic->sibling_details as $sibling) {
                $standardSection = 'Not Studying In This School';

                if ($sibling['sibling_standard'] !== 'not_studying') {
                    $standardLink = StandardLink::where('id', $sibling['sibling_standard'])->first();
                    $standardSection = $standardLink->StandardSection ?? '';
                }

                $this->siblingDetails[] = [
                    'fullname' => ucfirst($sibling['sibling_name']),
                    'relation' => ucfirst($sibling['sibling_relation']),
                    'date_of_birth' => date('d-m-Y', strtotime($sibling['sibling_date_of_birth'])),
                    'standard_section' => $standardSection,
                ];
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.student.profile.siblings');
    }
}
