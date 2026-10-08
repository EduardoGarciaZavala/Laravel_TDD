<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
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
                    'is_active',
                    'sort_order'
                ]

            ]
        ]);
    }

    // Verifica que se pueda crear una categoría.
    public function test_can_create_a_category(): void
    {
        //ver excepciones reales
        $this->WithoutExceptionHandling();

        //Crear personal access client
        $this->clientRepository();

        //Crear Usuario
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

        //Crear token
        $token = $user->createToken('Auth Token Test')->accessToken;

        $response = $this->withToken($token)->postJson(route('api.categories.store'), [
            'name' => 'Pantalón',
            'slug' => 'pantalon',
            'description' => 'Prenda de vestir para la parte inferior del cuerpo.',
            'image' => UploadedFile::fake()->image('pantalon.jpg'),
            'is_active' => true,
            'sort_order' => 1
        ]);
        //Verificar que se creo la categoria
        $response->assertCreated();

        //Validar campos
        $response->assertJson([
            'category' => [
                'name' => 'Pantalón',
                'slug' => 'pantalon',
            ]
        ]);

        //Validar structira
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

        //Verificar registro en la DB
        $this->assertDatabaseHas('categories', [
            'name' => 'Pantalón',
            'slug' => 'pantalon',
            'is_active' => true
        ]);
    }

    // Verifica que se pueda consultar una categoría.
    public function test_can_show_a_category(): void
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

        //Crear categoria
        $category = Category::create([
            'name' => 'Pantalón',
            'slug' => 'pantalon',
            'description' => 'Prenda de vestir para la parte inferior del cuerpo.',
            'image' => UploadedFile::fake()->image('pantalon.jpg'),
            'is_active' => true,
            'sort_order' => 1
        ]);

        //Realizar peticion api.categories.show
        $response = $this->withToken($token)->getJson(route('api.categories.show',['id' => $category->id]));

        //validar status 200
        $response->assertOk();

        //validar campo de la categoria  
        $response->assertJson([
            'category' => [
                'name' => 'Pantalón'
            ]
        ]);
        //Validar estructura de la categoria
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
