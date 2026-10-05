<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\ClientRepository;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Comprueba que el login con credenciales correctas
     * responda con un estado HTTP 200 e incluya un access_token.
     */
    public function test_login()
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

        // Si ocurre una excepción, permite ver el error original en la prueba.
        $this->withoutExceptionHandling();

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
     * responda con un estado HTTP 200 e incluya un access_token.
     */
    public function test_register()
    {
        // Mantiene el manejo normal de excepciones para poder comprobar
        $this->withExceptionHandling();

        // Crea una instancia del repositorio que administra los clientes de Passport.
        $clientRepository = new ClientRepository();

        // Crea el cliente que Passport necesita para emitir tokens de acceso personal.
        // Este cliente de Passport no es el usuario que iniciará sesión.
        $clientRepository->createPersonalAccessClient(
            null,                  // Sin un usuario propietario asociado al cliente.
            'Cliente de prueba',   // Nombre del cliente.
            'http://localhost'     // URL de redirección registrada para el cliente.
        );

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
        $this->assertArrayhasKey('access_token',$reponse->json());

        //validar existencia en DB
        $this->assertDataBaseHas('users', [
            'email' => $dataUser['email']
        ]);
    }
}
