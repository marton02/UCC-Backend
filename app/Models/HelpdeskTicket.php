<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class HelpdeskTicket extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'agent_id',
        'subject',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function messages()
    {
        return $this->hasMany(HelpdeskMessage::class,"helpdesk_ticket_id");
    }
}
