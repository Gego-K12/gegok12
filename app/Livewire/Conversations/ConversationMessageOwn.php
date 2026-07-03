<?php

namespace App\Livewire\Conversations;

use App\Models\Message;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Class ConversationMessageOwn
 *
 * Livewire component responsible for rendering
 * a single message sent by the authenticated user
 * within a conversation.
 */
class ConversationMessageOwn extends Component
{
    /**
     * Message instance authored by the authenticated user.
     *
     * @var Message
     */
    public $message;

    /**
     * Lifecycle hook executed when the component is mounted.
     *
     * Assigns the message model instance to the component.
     *
     * @return void
     */
    public function mount(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Render the Livewire component view.
     *
     * Displays the authenticated user's conversation message.
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.conversations.conversation-message-own');
    }
}
