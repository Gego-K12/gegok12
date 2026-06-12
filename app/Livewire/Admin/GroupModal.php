<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class GroupModal extends Component
{
    
    public $showModal = false;
    public $group_name;
    public $standardLink_id;

    protected $listeners = ['openGroupModal'];

    public function mount($standardLink_id)
    {
        $this->standardLink_id = $standardLink_id;
    }

    public function openGroupModal()
    {
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'group_name' => 'required'
        ]);

        Group::create([
            'group_name' => $this->group_name,
            'standards_link_id' => $this->standardLink_id
        ]);

        $this->reset('group_name');
        $this->showModal = false;

        session()->flash('success', 'Group added successfully');
    }
    public function render()
    {
        return view('livewire.admin.group-modal');
    }
}
