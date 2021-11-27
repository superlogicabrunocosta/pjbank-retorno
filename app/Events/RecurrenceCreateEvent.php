<?php

namespace App\Events;

use App\Models\Recurrence;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RecurrenceCreateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $obj;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Recurrence $obj)
    {
        //
        $this->obj = $obj;
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

    public function getRecurrence()
    {
        return $this->obj;
    }
}
