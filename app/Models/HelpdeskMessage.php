<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class HelpdeskMessage extends Model
{
    use HasUuids;

    protected $fillable = [
        'content',
        'helpdesk_ticket_id',
        'user_id',
    ];

    public function helpdeskTicket()
    {
        return $this->belongsTo(HelpdeskTicket::class, 'helpdesk_ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
