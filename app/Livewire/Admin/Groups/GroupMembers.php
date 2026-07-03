<?php

namespace App\Livewire\Admin\Groups;

use App\Models\GroupMember;
use Livewire\Component;
use Livewire\WithPagination;

class GroupMembers extends Component
{
    use WithPagination;

    public $group_id;

    public function mount($id)
    {
        $this->group_id = $id;
    }

    public function render()
    {
        $groups = GroupMember::where('group_id', $this->group_id)->paginate(10);

        return view('livewire.admin.groups.group-members', [
            'groups' => $groups,
        ]);
    }
}
