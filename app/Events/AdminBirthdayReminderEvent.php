<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminBirthdayReminderEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $church_id;

    public $from;

    public $mobile_no;

    public $mail;

    public $subject;

    public $message;

    public $entity_id;

    public $entity_name;

    public $via;

    public $data;

    public $executed_at;

    public function __construct($church_id, $from, $mobile_no, $mail, $subject, $message, $entity_id, $entity_name, $via, $data, $executed_at)
    {
        //
        $this->church_id = $church_id;
        $this->from = $from;
        $this->mobile_no = $mobile_no;
        $this->mail = $mail;
        $this->subject = $subject;
        $this->message = $message;
        $this->entity_id = $entity_id;
        $this->entity_name = $entity_name;
        $this->via = $via;
        $this->data = $data;
        $this->executed_at = $executed_at;
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
