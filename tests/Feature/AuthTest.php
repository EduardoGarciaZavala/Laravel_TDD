<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\ClientRepository;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function clientRepository()
    {
        // Crea una instancia del repositorio que administra los clientes de Passport.
        $clientRepository = new ClientRepository();

        // Crea el cliente que Passport necesita para emitir tokens de acceso personal.
        // Este cliente de Passport no es el usuario que iniciará sesión.
        $clientRepository->createPersonalAccessClient(
            null,                  // Sin un usuario propietario asociado al cliente.
            'Cliente de prueba',   // Nombre del cliente.
            'http://localhost'     // URL de redirección registrada para el cliente.
        );
    }

    /**
     * Comprueba que el login con credenciales correctas
     * responda con un estado HTTP 200 e incluya un access_token.
     */
    public function test_login()
    {

        // Si ocurre una excepción, permite ver el error original en la prueba.
        $this->withoutExceptionHandling();

        $this->clientRepository();

        // Crea y guarda en la base de datos el usuario que intentará iniciar sesión.
        $user = User::create([
            'name' => 'Antonio',
            'full_name' => 'Antonio Varela',
            'email' => time() . 'test@test.com',
            'password' => bcrypt('12345678'),
            'address' => 'test #123',
            'shipping_address' => 'test #1231',
            'county' => 'Mexico',
            'phone' => '6677859966',
        ]);

        // Simula una petición POST con datos JSON a la ruta llamada "api.login".
        $response = $this->postJson(route('api.login'), [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        // Verifica que el endpoint responda con HTTP 200.
        $response->assertStatus(200);

        // verificar exista la clave "access_token".
        $this->assertArrayHasKey('access_token', $response->json());

        // Verifica que la variable $user contenga una instancia del modelo User.
        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * Comprueba que el registro de usuario se realize correctamente
     * responda con un estado HTTP 201 e incluya un access_token.
     */
    public function test_register()
    {
        // Mantiene el manejo normal de excepciones para poder comprobar
        $this->withExceptionHandling();

        $this->clientRepository();

        // Crea arreglo de los datos para registrar al usuario
        $dataUser = [
            'name' => 'Antonio',
            'full_name' => 'Antonio Varela',
            'email' => time() . 'test@test.com',
            'password' => "12345678",
            'password_confirmation' => "12345678",
            'role' => 1,
            'address' => 'test #123',
            'shipping_address' => 'test #1231',
            'county' => 'Mexico',
            'phone' => '6677859966',
        ];

        //realizar una consulta post a la ruta api.register
        $reponse = $this->postJson(route('api.register', $dataUser));

        //validar que se resiva un status 200
        $reponse->assertStatus(201);

        //validar estructura
        $reponse->assertJsonStructure([
            'user',
            'access_token'
        ]);

        //validar que existe un access token 
        $this->assertArrayhasKey('access_token', $reponse->json());

        //validar existencia en DB
        $this->assertDataBaseHas('users', [
            'email' => $dataUser['email']
        ]);
    }

    /**
     * Comprueba que se obtenga informacion de usuario
     * responda con un estado HTTP 200 e incluya un array con la informacion del usuario.
     */
    public function test_authenticated_user_can_retrieve_his_information(): void
    {
        //ver excepciones reales
        $this->WithoutExceptionHandling();

        $this->clientRepository();

        // crear Usuario 
        $user = User::create([
            'name' => 'Antonio',
            'full_name' => 'Antonio Varela',
            'email' => time() . 'test@test.com',
            'password' => bcrypt('12345678'),
            'address' => 'test #123',
            'shipping_address' => 'test #1231',
            'county' => 'Mexico',
            'phone' => '6677859966',
        ]);

        $token = $user->createToken('Auth Token')->accessToken;
        $response = $this->WithToken($token)->getJson(route('api.me'));

        //codigo de exito esperado 
        $response->assertStatus(200);

        //validar que envia la instancia del usuario
        $this->assertArrayHasKey('user', $response->json());

        $response->assertJsonStructure([
            'user'
        ]);
    }

    /**
     * Comprueba que destruya el token del usuario
     * responda con un estado HTTP 200 
     */
    public function test_user_can_logout(): void
    {
        $this->clientRepository();

        // crear Usuario 
        $user = User::create([
            'name' => 'Antonio',
            'full_name' => 'Antonio Varela',
            'email' => time() . 'test@test.com',
            'password' => bcrypt('12345678'),
            'address' => 'test #123',
            'shipping_address' => 'test #1231',
            'county' => 'Mexico',
            'phone' => '6677859966',
        ]);

        $token = $user->createToken('Logout test');

        $response = $this->withToken($token->accessToken)
            ->postJson(route('api.logout'));

        //codigo 204
        $response->assertNoContent();
        // revisar que este revocado el token
        $this->assertDatabaseHas('oauth_access_tokens', [
            'id' => $token->token->id,
            'user_id' => $user->id,
            'revoked' => true,
        ]);
    }
}
