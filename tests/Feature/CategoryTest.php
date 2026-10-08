<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    // Crear el cliente de acceso personal.
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
        // Verificar excepciones reales.
        $this->WithoutExceptionHandling();

        // Crear el cliente de acceso personal.
        $this->clientRepository();

        // Crear usuario.
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

        // Crear token.
        $token = $user->createToken('Auth Token Test')->accessToken;

        // Petición GET a api.categories.index
        $response = $this->WithToken($token)->getJson(route('api.categories.index'));

        // Verificar el estado 200.
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
                    'is_active',
                    'sort_order'
                ]

            ]
        ]);
    }

    // Verifica que se pueda crear una categoría.
    public function test_can_create_a_category(): void
    {
        // Verificar excepciones reales.
        $this->WithoutExceptionHandling();

        // Crear el cliente de acceso personal.
        $this->clientRepository();

        // Crear usuario.
        $user = User::create([
            'name' => 'Antonio',
            'full_name' => 'Antonio Varela',
            'email' => time() . 'test@test.com',
            'password' => bcrypt('12345678'),
            'address' => 'test #123',
            'shipping_address' => 'test #1231',
            'country' => 'Mexico',
            'phone' => '6677859966'
        ]);

        // Crear token.
        $token = $user->createToken('Auth Token Test')->accessToken;

        $response = $this->withToken($token)->postJson(route('api.categories.store'), [
            'name' => 'Pantalón',
            'slug' => 'pantalon',
            'description' => 'Prenda de vestir para la parte inferior del cuerpo.',
            'image' => 'pantalon.jpg',
            'is_active' => true,
            'sort_order' => 1
        ]);
        // Verificar que se creó la categoría.
        $response->assertCreated();

        // Validar campos.
        $response->assertJson([
            'category' => [
                'name' => 'Pantalón',
                'slug' => 'pantalon',
                'description' => 'Prenda de vestir para la parte inferior del cuerpo.',
                'image' => 'categories/' . $image->hashName(),
                'is_active' => true,
                'sort_order' => 1
            ]
        ]);

        // Validar estructura.
        $response->assertJsonStructure([
            'category' => [
                'name',
                'slug',
                'description',
                'image',
                'is_active',
                'sort_order'
            ]
        ]);

        // Verificar el registro en la base de datos.
        $this->assertDatabaseHas('categories', [
            'name' => 'Pantalón',
            'slug' => 'pantalon',
            'is_active' => true
        ]);

        //Verificar imagen
        Storage::disk('public')->assertExists(
            'categories/' . $image->hashName()
        );
    }

    // Verifica que se pueda consultar una categoría.
    public function test_can_show_a_category(): void
    {
        // Verificar excepciones reales.
        $this->WithoutExceptionHandling();

        // Crear el cliente de acceso personal.
        $this->clientRepository();

        // Crear usuario.
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

        // Crear token.
        $token = $user->createToken('Auth Token Test')->accessToken;

        // Crear categoría.
        $category = Category::create([
            'name' => 'Pantalón',
            'slug' => 'pantalon',
            'description' => 'Prenda de vestir para la parte inferior del cuerpo.',
            'image' => 'pantalon.jpg',
            'is_active' => true,
            'sort_order' => 1
        ]);

        // Realizar petición a api.categories.show.
        $response = $this->withToken($token)->getJson(route('api.categories.show', ['id' => $category->id]));

        // Validar el estado 200.
        $response->assertOk();

        // Validar el campo de la categoría.
        $response->assertJson([
            'category' => [
                'name' => 'Pantalón',
                'slug' => 'pantalon',
                'description' => 'Prenda de vestir para la parte inferior del cuerpo.',
                'image' => 'pantalon.jpg',
                'is_active' => true,
                'sort_order' => 1
            ]
        ]);

        //Validar imagen
        Storage::disk('public')->assertExists(
            'categories/' . $image->hashName()
        );

        // Validar la estructura de la categoría.
        $response->assertJsonStructure([
            'category' => [
                'id',
                'name',
                'slug',
                'description',
                'image',
                'is_active',
                'sort_order'
            ]
        ]);
    }

    // Verifica que se pueda actualizar una categoría.
    public function test_can_update_a_category(): void {}

    // Verifica que se pueda eliminar una categoría.
    public function test_can_delete_a_category(): void {}
}
