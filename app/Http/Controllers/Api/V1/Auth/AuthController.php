<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Interfaces\Services\AuthServiceInterface;

class AuthController extends Controller
{
    public function __construct(
        private AuthServiceInterface $auth
    ) {}

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        return response()->json($this->auth->login($credentials));
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        return response()->json($this->auth->register($data), 201);
    }

    public function me()
    {
        return response()->json($this->auth->me());
    }
}
