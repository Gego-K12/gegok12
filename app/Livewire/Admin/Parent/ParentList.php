<?php

// SPDX-License-Identifier: MIT
// (c) 2026 GegoSoft Technologies and GegoK12 Contributors

namespace App\Livewire\Admin\Parent;

use App\Models\User;
use App\Models\Users\ParentUser;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Parent list -- ports resources/assets/js/components/parent/List.vue into a
 * Livewire component. Unlike Teacher/Staff, this page has no tabs, alphabet
 * filter, bulk actions, or advanced filter panel in the original Vue version
 * -- just three per-column search boxes (Name, Parent Of, Mobile Number) and
 * a Reset link, all server-side via App\Traits\MemberProcess::ParentFilter().
 */
class ParentList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public string $name = '';

    public string $parentOf = '';

    public string $mobileNo = '';

    protected array $filterProperties = ['name', 'parentOf', 'mobileNo'];

    public function updated(string $property): void
    {
        if (in_array($property, $this->filterProperties, true)) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset($this->filterProperties);
        $this->resetPage();
    }

    protected function baseQuery()
    {
        $schoolId = Auth::user()->school_id;

        $query = ParentUser::where('school_id', $schoolId)
            ->ByRole(User::PARENT_USERGROUP_ID)
            ->whereHas('children.userStudent', fn ($q) => $q->where('status', '!=', 'exit'))
            ->whereHas('userprofile', fn ($q) => $q->where('status', 'active')->orWhere('status', 'inactive'))
            ->with([
                'children.userStudent.userprofile',
                'children.userStudent.studentAcademicLatest.standardLink',
            ]);

        if ($this->name) {
            $query->ByFullNameParent($this->name);
        }

        if ($this->parentOf) {
            $query->ByStudentNameParent($this->parentOf);
        }

        if ($this->mobileNo) {
            $query->ByMobileNoParent($this->mobileNo);
        }

        return $query;
    }

    public function render()
    {
        $parents = $this->baseQuery()->paginate(10);

        return view('livewire.admin.parent.parent-list', [
            'parents' => $parents,
        ]);
    }
}
