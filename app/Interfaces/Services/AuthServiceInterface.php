<?php

namespace App\Interfaces\Services;

interface AuthServiceInterface
{
    public function login(array $credentials): array;

    public function register(array $data): array;

    public function me(): array;
}
