<?php

namespace App\Livewire\Conversations;

use App\Models\Conversation;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Class ConversationList
 *
 * Livewire component responsible for displaying
 * and dynamically updating the list of conversations
 * for the authenticated user.
 *
 * This component handles:
 * - Initial conversation list injection
 * - Listening for real-time conversation creation events
 * - Prepending new conversations to the list
 */
class ConversationList extends Component
{
    /**
     * Collection of conversations.
     *
     * @var Collection
     */
    public $conversations;

    /**
     * Lifecycle hook executed when the component is mounted.
     *
     * Injects the initial conversation collection
     * into the component.
     *
     * @return void
     */
    public function mount(Collection $conversations)
    {
        $this->conversations = $conversations;
    }

    /**
     * Register Livewire event listeners.
     *
     * Listens for broadcasted conversation creation
     * events on the authenticated user's private channel.
     *
     * @return array
     */
    public function getListeners()
    {
        return [
            'echo-private:users.'.auth()->id().',Conversations\\ConversationCreated' => 'prependConversationFromBroadcast',
        ];
    }

    /**
     * Prepend a conversation to the conversation list.
     *
     * Fetches the conversation by ID and
     * adds it to the beginning of the collection.
     *
     * @param  int|string  $id  Conversation ID
     * @return void
     */
    public function prependConversation($id)
    {
        $this->conversations->prepend(Conversation::find($id));
    }

    /**
     * Handle conversation creation event broadcast.
     *
     * Extracts conversation ID from the payload
     * and prepends the conversation to the list.
     *
     * @param  array  $payload  Broadcast event payload
     * @return void
     */
    public function prependConversationFromBroadcast($payload)
    {
        $this->prependConversation($payload['conversation']['id']);
    }

    /**
     * Render the Livewire component view.
     *
     * Displays the conversation list.
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.conversations.conversation-list');
    }
}
