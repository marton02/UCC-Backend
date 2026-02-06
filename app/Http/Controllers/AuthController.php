<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $credentials = request(['email', 'password']);

        try{
            if (Auth::once($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('token',["*"],Carbon::now()->addHours(8))->plainTextToken;
                $success['token'] =  $token;
                $success['name'] =  $user->name;
                return response()->json(['success' => $success], 200);
            }
        }catch(\Exception $exception){
            Log::error($exception->getMessage());
            return response()->json(['error' => $exception->getMessage()], 500);
        }

        return response()->json(['error' => 'Cannot find user with the given credentials.'], 404);
    }
}
