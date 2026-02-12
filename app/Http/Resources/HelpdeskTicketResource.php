<?php

namespace App\Http\Resources;

use App\Models\HelpdeskTicket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin HelpdeskTicket */
class HelpdeskTicketResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'status' => $this->status,
            'updated_at' => $this->updated_at,
            'messages' => new HelpdeskMessageResource($this->whenLoaded('messages')),
        ];
    }
}
