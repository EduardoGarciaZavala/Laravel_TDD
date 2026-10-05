<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function post(LoginRequest $request)
    {

        $credentials = $request->validated();
        if (!Auth()->attempt($credentials)) {
            return response()->json([
                'message' => __('login.LOGIN')
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('Auth Token')->accessToken;

        return response()->json([
            'access_token' => $token
        ], 200);
    }
}
