<?php

use App\Http\Controllers\EventController;
use Illuminate\Http\Request;

Route::get('/user', function (Request $request) {
    return new \App\Http\Resources\UserResource($request->user());
})->middleware('auth:api');

Route::apiResource("events",EventController::class)->middleware('auth:api');

Route::patch("/events/{event}/restore",EventController::class."@restore")->middleware('auth:api')->withTrashed();
Route::delete("/events/{event}/force",EventController::class."@forceDestroy")->middleware('auth:api')->withTrashed();

Route::get("/logout",function (){
    $token = Auth::user()->token();
    $token->revoke();
    return response()->json([],201);
})->middleware('auth:api');

Route::post("forgot-password",function (Request $request){
    $request->validate(['email'=>'required|email']);
    $status = Password::sendResetLink($request->only('email'));
    return $status === Password::RESET_LINK_SENT ? response()->json(["statusCode"=>200,"data"=>$status]) : response()->json(["statusCode"=>400]);
})->middleware('guest');

Route::post("reset-password",function (Request $request){
    $request->validate(['token' => 'required','email'=>'required|email','password' => 'required|confirmed|min:8']);
    $input = $request->only('email','token', 'password', 'password_confirmation');
    $status = Password::reset($input, function ($user, $password) {
        $user->password = Hash::make($password);
        $user->save();
    });
    return $status == Password::PASSWORD_RESET ? response()->json(["statusCode"=>200,"data"=>$status]) : response()->json(["statusCode"=>400]);
})->middleware('guest')->name('password.reset');
