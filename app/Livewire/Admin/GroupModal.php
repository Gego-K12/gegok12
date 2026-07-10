<?php

namespace App\Livewire\Admin;

use Livewire\Component;

/**
 * Class GroupModal
 *
 * Livewire component for creating a group via a modal dialog.
 */
class GroupModal extends Component
{
    /**
     * Whether the modal is currently visible.
     *
     * @var bool
     */
    public $showModal = false;

    /**
     * The name of the group being created.
     *
     * @var string
     */
    public $group_name;

    /**
     * The ID of the standard link the group belongs to.
     *
     * @var int
     */
    public $standardLink_id;

    /**
     * The event listeners for this component.
     *
     * @var array
     */
    protected $listeners = ['openGroupModal'];

    /**
     * Set the standard link the group will be created for.
     *
     * @param  int  $standardLink_id
     * @return void
     */
    public function mount($standardLink_id)
    {
        $this->standardLink_id = $standardLink_id;
    }

    /**
     * Open the group creation modal.
     *
     * @return void
     */
    public function openGroupModal()
    {
        $this->showModal = true;
    }

    /**
     * Create a new group for the current standard link.
     *
     * @return void
     */
    public function save()
    {
        $this->validate([
            'group_name' => 'required',
        ]);

        Group::create([
            'group_name' => $this->group_name,
            'standards_link_id' => $this->standardLink_id,
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
