<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => 'auth:api']);

Broadcast::channel('Helpdesk.User.{id}', function ($user, $id) {
    return (string) $user->id === (string) $id;
}, ['guards' => ['api']]);

Broadcast::channel('Helpdesk.Ticket.{ticketId}', function (User $user, string $ticketId) {
    $user->load('tickets');
    return $user->tickets()->where("id",$ticketId)->first() !== null;
}, ['guards' => ['api']]);
