<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendMessageTeacherEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    public $school_id;
    public $admin_email;
    public $admin;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data,$school_id,$admin_email,$admin)
    {
        //
        $this->data = $data;
        $this->school_id = $school_id;
        $this->admin_email = $admin_email;
        $this->admin = $admin;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
