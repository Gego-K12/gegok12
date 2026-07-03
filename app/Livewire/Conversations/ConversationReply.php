<?php

namespace App\Livewire\Conversations;

use App\Events\Conversations\MessageAdded;
use App\Models\Conversation;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Class ConversationReply
 *
 * Livewire component responsible for handling
 * replies within an existing conversation.
 *
 * This component handles:
 * - Message validation
 * - Creating new messages
 * - Updating conversation metadata
 * - Resetting read status for other participants
 * - Broadcasting message events in real-time
 */
class ConversationReply extends Component
{
    /**
     * Conversation instance to which the reply belongs.
     *
     * @var Conversation
     */
    public $conversation;

    /**
     * Message body entered by the user.
     *
     * @var string
     */
    public $body = '';

    /**
     * Lifecycle hook executed when the component is mounted.
     *
     * Assigns the conversation model instance
     * to the component.
     *
     * @return void
     */
    public function mount(Conversation $conversation)
    {
        $this->conversation = $conversation;
    }

    /**
     * Send a reply message in the conversation.
     *
     * Validates the message body, creates a new message,
     * updates conversation metadata, resets read status
     * for other participants, broadcasts the message,
     * and emits a Livewire event.
     *
     * @return void
     */
    public function reply()
    {
        $this->validate([
            'body' => 'required',
        ]);

        $message = $this->conversation->messages()->create([
            'user_id' => auth()->id(),
            'body' => $this->body,
        ]);

        $this->conversation->update([
            'last_message_at' => now(),
        ]);

        foreach ($this->conversation->others as $user) {
            $user->conversations()->updateExistingPivot(
                $this->conversation,
                ['read_at' => null]
            );
        }

        broadcast(new MessageAdded($message))->toOthers();

        $this->emit('message.created', $message->id);

        $this->body = '';
    }

    /**
     * Render the Livewire component view.
     *
     * Displays the conversation reply input UI.
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.conversations.conversation-reply');
    }
}
