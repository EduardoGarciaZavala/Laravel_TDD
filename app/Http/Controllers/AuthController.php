<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
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
            'user' => $user,
            'access_token' => $token
        ], 200);
    }

    public function register(RegisterRequest $request)
    {

        $data = $request->validated();

        $user = User::Create($data);
        $token = $user->createToken('Auth Token')->accessToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token
        ], 201);
    }
}
