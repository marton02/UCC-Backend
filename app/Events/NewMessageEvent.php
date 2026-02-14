<?php

namespace App\Events;

use App\Http\Resources\HelpdeskMessageResource;
use App\Models\HelpdeskMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public HelpdeskMessage $message)
    {
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('Helpdesk.Ticket.'.$this->message->helpdesk_ticket_id),
            new PrivateChannel('Helpdesk.User.'.$this->message->helpdeskTicket->user_id),
            new PrivateChannel('Helpdesk.Agent.'.$this->message->helpdeskTicket->agent_id),
        ];
    }
    public function broadcastAs(): string
    {
        return 'helpdesk.message';
    }

    public function broadcastWith(): array
    {

        return [
            'ticket_id' => (string) $this->message->helpdesk_ticket_id,
            'message' => (new HelpdeskMessageResource($this->message))->resolve(),
        ];
    }
}
