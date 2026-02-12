<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\HelpdeskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'auth:api',
], function () {

    Route::get('/user', function (Request $request) {
        return new \App\Http\Resources\UserResource($request->user());
    });

    Route::apiResource("events",EventController::class);

    Route::patch("/events/{event}/restore",EventController::class."@restore")->withTrashed();
    Route::delete("/events/{event}/force",EventController::class."@forceDestroy")->withTrashed();

    Route::get("/logout",function (){
        $token = Auth::user()->token();
        $token->revoke();
        return response()->json([],201);
    });

    Route::get("helpdesk",HelpdeskController::class."@index");
});

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
