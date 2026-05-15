<?php

namespace App\Livewire\Admin\Groups;

use Livewire\Component;
use App\Models\Group;
use Livewire\WithPagination;
use App\Models\StandardLink;
use App\Helpers\SiteHelper;
use Auth;

class GroupList extends Component
{
    use WithPagination;

    public $search = '';

    public $showModal = false;
    public $group_name = '';
    public $type = '';
    public $standardLink_id = '';
    public $standardLinks = [];

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'group_name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'standardLink_id' => 'required',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['group_name', 'type']);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetValidation();
        $this->reset(['group_name', 'type']);
    }

    public function saveGroup()
    {
        $this->validate();

        Group::create([
            'group_name' => $this->group_name,
            'type' => $this->type,
            'standardLink_id' => $this->type === 'class'
                            ? $this->standardLink_id
                            : null,
        ]);

        session()->flash('success', 'Group added successfully.');

        $this->closeModal();
        $this->resetPage();
    }

    public function render()
    {
        $groups = Group::orderBy('id', 'desc');

        if ($this->search) {
            $groups = $groups->where(function ($query) {
                $query->where('group_name', 'like', '%' . $this->search . '%');
            });
        }

        $groups = $groups->paginate(10);

        return view('livewire.admin.groups.group-list', [
            'groups' => $groups
        ]);
    }
    public function updatedType($value)
    {
        if ($value == 'class') {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);

            $this->standardLinks = StandardLink::where([
                ['school_id', $school_id],
                ['academic_year_id', $academic_year->id]
            ])->get();

        } else {
            $this->standardLink_id = '';
            $this->standardLinks = [];
        }
    }
}