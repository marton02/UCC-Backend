<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class SomeEvent implements ShouldBroadcast
{
    use Dispatchable;

    public function __construct(public array $data) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('App.Models.User.'.$this->data['user_id']);
    }

    public function broadcastAs(): string
    {
        return 'SomeEvent';
    }
}
