<?php

namespace App\Events;

use App\Models\Charge;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChargeUpdateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $obj;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Charge $obj)
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

    public function getCharge()
    {
        return $this->obj;
    }
}
