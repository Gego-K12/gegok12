<?php

namespace App\Livewire\Conversations;

use App\Models\Message;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Class ConversationMessage
 *
 * Livewire component responsible for rendering
 * a single message within a conversation.
 */
class ConversationMessage extends Component
{
    /**
     * Message instance to be displayed.
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
     * Displays the conversation message.
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.conversations.conversation-message');
    }
}
