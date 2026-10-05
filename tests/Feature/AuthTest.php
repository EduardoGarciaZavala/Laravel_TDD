<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login()
    {
        //Artisan::call('passport:install');
        app(\Laravel\Passport\ClientRepository::class)->createPersonalAccessClient(null, 'Cliente de prueba', 'http://localhost');
        $this->withoutExceptionHandling();
        $user = User::Create([
            'name' => "Antonio",
            'full_name' => "Antonio Varela",
            'email' => time() . "test@test.com",
            'password' => bcrypt("12345678"),
            'address' => "test #123",
            'shipping_address' => "test #1231",
            'county' => "Mexico",
            'phone' => "6677859966"

        ]);

        $user->createToken('Auth Token')->accessToken;

        $response = $this->postJson(route('api.login'), [
            'email' => time() . "test@test.com",
            'password' => "12345678"
        ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('access_token', $response->json());
        $this->assertInstanceOf(User::class, $user);
    }
}
