<?php

namespace Pace\AccessTelemetry\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequestLoginEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public $credentials;

    /**
     * @var array
     */
    public $server;

    /**
     * Create a new event instance.
     *
     * @param array $credentials
     * @param array $server
     */
    public function __construct(array $credentials, array $server)
    {
        $this->credentials = $credentials;
        $this->server      = $server;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array|\Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        return new PrivateChannel('currently-active-users-online');
    }
}
