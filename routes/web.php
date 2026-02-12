<?php

use Illuminate\Support\Facades\Route;

Route::get("/event",function(){
    event(new \App\Events\SomeEvent([
        'user_id' => "019c499f-16d1-71b3-9652-99166ca5841f",
        'message' => 'hello from laravel',
    ]));

});
