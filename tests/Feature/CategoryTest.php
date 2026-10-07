<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    //Crear Personal access client
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
    // Verifica que se puedan listar las categorías.
    public function test_can_list_categories(): void
    {
        //ver excepciones reales
        $this->WithoutExceptionHandling();

        //Crear personal access client
        $this->clientRepository();

        //Crear usuario
        $user = User::Create([
            'name' => 'Antonio',
            'full_name' => 'Antonio Varela',
            'email' => time() . 'test@test.com',
            'password' => bcrypt('12345678'),
            'address' => 'test #123',
            'shipping_address' => 'test #1231',
            'country' => 'Mexico',
            'phone' => '6677859966',
        ]);

        //crea token
        $token = $user->createToken('Auth Token Test')->accessToken;

        // Crear categorías

        // Petición GET a api.categories.index
        $response = $this->WithToken($token)->getJson(route('api.categories.index'));

        //verificar status 200
        $response->assertOk();

        // Estructura esperada de la API
        $response->assertJsonStructure([
            'categories' => [
                '*' => [
                    'id',
                    'name',
                    'slug',
                    'description',
                    'image',
                    'is_active'
                ]

            ]
        ]);
    }

    // Verifica que se pueda crear una categoría.
    public function test_can_create_a_category(): void {}

    // Verifica que se pueda consultar una categoría.
    public function test_can_show_a_category(): void {}

    // Verifica que se pueda actualizar una categoría.
    public function test_can_update_a_category(): void {}

    // Verifica que se pueda eliminar una categoría.
    public function test_can_delete_a_category(): void {}
}
