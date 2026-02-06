<?php

use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

Route::post('/login',AuthController::class.'@login');

Route::get('/user', function (Request $request) {
    return new \App\Http\Resources\UserResource($request->user());
})->middleware('auth:api');

Route::apiResource("events",EventController::class)->middleware('auth:api');

Route::patch("/events/{event}/restore",EventController::class."@restore")->middleware('auth:api')->withTrashed();
Route::delete("/events/{event}/force",EventController::class."@forceDestroy")->middleware('auth:api')->withTrashed();
