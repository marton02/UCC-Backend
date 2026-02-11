<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => 'auth:api']);

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return ["user" => $user, "id" => $id];
});
