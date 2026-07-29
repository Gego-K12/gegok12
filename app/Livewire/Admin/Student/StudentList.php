<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Livewire\Admin\Student;

use App\Models\Userprofile;
use App\Models\Users\StudentUser;
use Auth;
use Livewire\Component;
use Livewire\WithPagination;

class StudentList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';

    public $standardId = '';

    public $letter = '';

    public $sortField = 'firstname';

    public $sortDirection = 'asc';

    public $selected = [];

    public $selectPage = false;

    public $standardLinks = [];

    public $birthday = false;

    public $feeEnabled = false;

    public $alphabets = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
        'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
    ];

    public function mount($standardLinks = [], $defaultStandardId = '', $birthday = false, $defaultLetter = '')
    {
        // $standardLinks arrives as an AnonymousResourceCollection (from
        // SiteHelper::getStandardLinkList) -- Livewire only supports plain
        // arrays/Collections/Models as public property types, so flatten it.
        $this->standardLinks = json_decode(json_encode($standardLinks), true) ?? [];
        $this->standardId = $defaultStandardId;
        $this->birthday = $birthday;
        $this->letter = $defaultLetter;
        $this->feeEnabled = config('gfee.enabled', false);
    }

    public function updatingSearch()
    {
        $this->resetPage();
        $this->selected = [];
        $this->selectPage = false;
    }

    public function updatingStandardId()
    {
        $this->resetPage();
        $this->selected = [];
        $this->selectPage = false;
    }

    public function updatingLetter()
    {
        $this->resetPage();
        $this->selected = [];
        $this->selectPage = false;
    }

    public function updatedPage()
    {
        $this->selectPage = false;
    }

    public function clearFilters()
    {
        $this->reset(['search', 'standardId', 'letter', 'selected', 'selectPage']);
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatedSelectPage($value)
    {
        $ids = $this->currentPageIds();

        if ($value) {
            $this->selected = array_values(array_unique(array_merge($this->selected, $ids)));
        } else {
            $this->selected = array_values(array_diff($this->selected, $ids));
        }
    }

    protected function currentPageIds()
    {
        return $this->baseQuery()->paginate(10)->pluck('id')->all();
    }

    protected function baseQuery()
    {
        $schoolId = Auth::user()->school_id;

        $students = StudentUser::BySchool($schoolId)
            ->ByRole(6)
            ->where('status', '!=', 'exit')
            ->with(['userprofile', 'studentAcademicLatest.standardLink', 'parents.userParent']);

        if ($this->search) {
            $students->where(function ($query) {
                $query->ByFirstName($this->search)->orWhere(function ($q) {
                    $q->ByLastName($this->search);
                });
            });
        }

        if ($this->letter) {
            $students->ByFirstName($this->letter);
        }

        if ($this->standardId) {
            $students->ByStandard($this->standardId);
        }

        if ($this->sortField === 'firstname') {
            $students->orderBy(
                Userprofile::select('firstname')->whereColumn('userprofiles.user_id', 'users.id'),
                $this->sortDirection
            );
        } elseif ($this->sortField === 'status') {
            $students->orderBy('users.status', $this->sortDirection);
        }

        return $students;
    }

    public function render()
    {
        $students = $this->baseQuery()->paginate(5);

        return view('livewire.admin.student.student-list', [
            'students' => $students,
        ]);
    }
}
