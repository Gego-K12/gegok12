<?php

namespace App\Livewire\Conversations;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Class ConversationMessages
 *
 * Livewire component responsible for displaying
 * and dynamically updating messages within
 * a specific conversation.
 *
 * This component handles:
 * - Initial message collection injection
 * - Listening for real-time message creation events
 * - Prepending new messages to the message list
 */
class ConversationMessages extends Component
{
    /**
     * Collection of conversation messages.
     *
     * @var Collection
     */
    public $messages;

    /**
     * Conversation identifier.
     *
     * @var int|string
     */
    public $conversationId;

    /**
     * Lifecycle hook executed when the component is mounted.
     *
     * Injects the conversation instance and
     * the initial message collection.
     *
     * @return void
     */
    public function mount(Conversation $conversation, Collection $messages)
    {
        $this->conversationId = $conversation->id;
        $this->messages = $messages;
    }

    /**
     * Register Livewire event listeners.
     *
     * Listens for:
     * - Local message creation events
     * - Broadcasted message creation events
     *   on the conversation private channel
     *
     * @return array
     */
    public function getListeners()
    {
        return [
            'message.created' => 'prependMessage',
            "echo-private:conversations.{$this->conversationId},Conversations\\MessageAdded" => 'prependMessageFromBroadcast',
        ];
    }

    /**
     * Prepend a message to the message list.
     *
     * Fetches the message by ID and
     * adds it to the beginning of the collection.
     *
     * @param  int|string  $id  Message ID
     * @return void
     */
    public function prependMessage($id)
    {
        $this->messages->prepend(Message::find($id));
    }

    /**
     * Handle broadcasted message creation event.
     *
     * Extracts the message ID from the payload
     * and prepends the message to the list.
     *
     * @param  array  $payload  Broadcast event payload
     * @return void
     */
    public function prependMessageFromBroadcast($payload)
    {
        $this->prependMessage($payload['message']['id']);
    }

    /**
     * Render the Livewire component view.
     *
     * Displays the conversation messages list.
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.conversations.conversation-messages');
    }
}
