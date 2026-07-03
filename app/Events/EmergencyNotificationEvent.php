<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmergencyNotificationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $datas;

    public $school_id;

    public $admin_email;

    public $admin;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($datas, $school_id, $admin_email, $admin)
    {
        //
        $this->datas = $datas;
        $this->school_id = $school_id;
        $this->admin_email = $admin_email;
        $this->admin = $admin;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
