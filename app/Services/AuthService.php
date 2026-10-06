<?php

namespace App\Services;

use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Interfaces\Services\AuthServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function login(array $credentials): array
    {
        if (! Auth::attempt($credentials)) {

            throw ValidationException::withMessages([
                'message' => __('auth.failed'),
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('Auth Token')->accessToken;

        return [
            'user' => $user,
            'access_token' => $token,
        ];
    }

    public function register(array $data): array
    {

        $user = $this->userRepository->create($data);
        $token = $user->createToken('Auth Token')->accessToken;

        return [
            'user' => $user,
            'access_token' => $token,
        ];
    }

    public function me(): array
    {
        return [
            'user' => Auth::user()
        ];
    }

    public function logout() :void
    {
        Auth::user()->token()->revoke();
    }
}
