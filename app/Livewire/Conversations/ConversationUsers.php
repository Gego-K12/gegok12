<?php

namespace App\Livewire\Conversations;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Class ConversationUsers
 *
 * Livewire component responsible for displaying
 * the list of users participating in a conversation.
 */
class ConversationUsers extends Component
{
    /**
     * Collection of users participating in the conversation.
     *
     * @var Collection
     */
    public $users;

    /**
     * Lifecycle hook executed when the component is mounted.
     *
     * Injects the collection of conversation users
     * into the component.
     *
     * @return void
     */
    public function mount(Collection $users)
    {
        $this->users = $users;
    }

    /**
     * Render the Livewire component view.
     *
     * Displays the conversation users list.
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.conversations.conversation-users');
    }
}
