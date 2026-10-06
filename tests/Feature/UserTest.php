<?php

namespace Tests\Feature;

use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Passport\ClientRepository;

class UserTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_can_authenticate_with_valid_credentials()
    {

        //ver excepciones reales
        $this->WithoutExceptionHandling();

        // Crea una instancia del repositorio que administra los clientes de Passport.
        $clientRepository = new ClientRepository();

        // Crea el cliente que Passport necesita para emitir tokens de acceso personal.
        // Este cliente de Passport no es el usuario que iniciará sesión.
        $clientRepository->createPersonalAccessClient(
            null,                  // Sin un usuario propietario asociado al cliente.
            'Cliente de prueba',   // Nombre del cliente.
            'http://localhost'     // URL de redirección registrada para el cliente.
        );

        //Datos del usuario
        $data = [
            'name' => 'Antonio',
            'full_name' => 'Antonio Varela',
            'email' => time() . 'test@test.com',
            'password' => "12345678",
            'role' => 0,
            'address' => 'test #123',
            'shipping_address' => 'test #1231',
            'county' => 'Mexico',
            'phone' => '6677859966',
        ];

        //Crear el usuario
        $user = User::Create($data);

        //validar que se puedo autentificar
        if (!Auth()->attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ])) {
            return response()->json([
                'message' => 'las credenciales son incorrectas'
            ]);
        }

        return $user->createToken('Auth Token')->accessToken;
    }


    /**
     * Este 
     */
    public function test_authenticated_user_can_retrieve_his_information(): void
    {
        //ver excepciones reales
        $this->WithoutExceptionHandling();

        $token = $this->test_user_can_authenticate_with_valid_credentials();
        //realizar la peticion a api.me 
        // $response = $this->withHeaders([
        //     'Authorization' => 'Bearer' . $token
        // ])->get(route('api.me'));
        $response = $this->WithToken($token)->getJson(route('api.me'));

        //codigo de exito esperado 
        $response->assertStatus(200);

        //validar que envia la instancia del usuario
        $this->assertArrayHasKey('user', $response->json());

        $response->assertJsonStructure([
            'user'
        ]);
    }
}
