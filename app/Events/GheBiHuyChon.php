<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GheBiHuyChon implements ShouldBroadcast
{
    public $gheId;
    public $userId;

    public function __construct($gheId, $userId)
    {
        $this->gheId = $gheId;
        $this->userId = $userId;
    }

    public function broadcastOn()
    {
        return new Channel('ghe-bi-huy');
    }

    public function broadcastAs()
    {
        return 'ghe-bi-huy';
    }
}
