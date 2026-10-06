<?php

namespace Tests\Feature;

use App\Http\Resources\Api\V1\Auth\AuthResource;
use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthFailureTest extends TestCase
{
    public function test_invalid_credentials_return_validation_error(): void
    {
        $credentials = ['email' => 'test@example.com', 'password' => 'incorrecta'];

        Auth::shouldReceive('userResolver')->andReturn(fn () => null);
        Auth::shouldReceive('attempt')->once()->with($credentials)->andReturn(false);

        $this->postJson(route('api.login'), $credentials)
            ->assertStatus(422)
            ->assertJsonValidationErrors('message')
            ->assertJsonMissingPath('access_token');
    }

    public function test_invalid_registration_does_not_create_a_user(): void
    {
        $this->mock(UserRepositoryInterface::class)
            ->shouldNotReceive('create');

        $this->postJson(route('api.register'), [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password'])
            ->assertJsonMissingPath('access_token');
    }

    public function test_auth_resource_uses_the_service_token_key(): void
    {
        $user = new User(['name' => 'Antonio', 'email' => 'test@example.com']);
        $user->id = 1;

        $resource = new AuthResource(['user' => $user, 'access_token' => 'test-token']);

        $this->assertSame([
            'user' => ['id' => 1, 'name' => 'Antonio', 'email' => 'test@example.com'],
            'access_token' => 'test-token',
        ], $resource->resolve());
    }
}
