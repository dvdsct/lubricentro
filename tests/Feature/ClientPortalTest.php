<?php

namespace Tests\Feature;

use App\Livewire\ClientPortal;
use App\Livewire\ClientProfile;
use App\Models\Cliente;
use App\Models\Item;
use App\Models\ItemsXOrden;
use App\Models\Orden;
use App\Models\Perfil;
use App\Models\Persona;
use App\Models\Producto;
use App\Models\User;
use Database\Seeders\ClienteRoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ClientPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ClienteRoleSeeder::class);
    }

    public function test_seeder_creates_cliente_role(): void
    {
        $this->assertDatabaseHas('roles', ['name' => 'cliente']);
        $this->assertDatabaseHas('permissions', ['name' => 'portal-cliente']);
    }

    public function test_staff_can_create_web_access_for_client(): void
    {
        $staff = User::factory()->create();

        $persona = Persona::create([
            'nombre' => 'Mario',
            'apellido' => 'Bros',
            'DNI' => '99887766',
            'numero_telefono' => '123456',
            'estado' => 1,
        ]);
        $perfil = Perfil::create(['persona_id' => $persona->id]);
        $cliente = Cliente::create(['perfil_id' => $perfil->id]);

        $this->actingAs($staff);

        Livewire::test(ClientProfile::class, ['cliente' => $cliente])
            ->call('openUserModal')
            ->set('userEmail', 'mario@client.com')
            ->set('userPassword', 'secret123')
            ->call('createWebAccess')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', ['email' => 'mario@client.com']);

        $user = User::where('email', 'mario@client.com')->first();
        $this->assertTrue($user->hasRole('cliente'));
        $this->assertEquals($user->id, $perfil->fresh()->user_id);
    }

    public function test_client_user_can_access_client_portal_and_see_their_turnos(): void
    {
        $persona = Persona::create([
            'nombre' => 'Luigi',
            'apellido' => 'Bros',
            'DNI' => '88776655',
            'estado' => 1,
        ]);

        $clientUser = User::factory()->create([
            'name' => 'Luigi Bros',
            'email' => 'luigi@client.com',
        ]);
        $clientUser->assignRole('cliente');

        $perfil = Perfil::create([
            'persona_id' => $persona->id,
            'user_id' => $clientUser->id,
        ]);
        $cliente = Cliente::create(['perfil_id' => $perfil->id]);

        $producto = Producto::create([
            'descripcion' => 'Filtro de Aceite',
            'codigo' => 'FIL001',
            'precio_venta' => 2500,
            'es_provisional' => 0,
            'estado' => 1,
        ]);

        $sucursalId = \Illuminate\Support\Facades\DB::table('sucursals')->where('id', 1)->value('id');
        if (!$sucursalId) {
            $sucursalId = \Illuminate\Support\Facades\DB::table('sucursals')->insertGetId([
                'id' => 1,
                'nombre_sucursal' => 'Central',
                'estado' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $orden = Orden::create([
            'cliente_id' => $cliente->id,
            'sucursal_id' => $sucursalId,
            'motivo' => '2',
            'fecha_turno' => now()->format('Y-m-d'),
            'horario' => '11:00',
            'estado' => '100',
        ]);

        $item = Item::create([
            'producto_id' => $producto->id,
            'precio' => 2500,
            'cantidad' => 1,
            'subtotal' => 2500,
            'estado' => '2',
        ]);
        ItemsXOrden::create(['item_id' => $item->id, 'orden_id' => $orden->id, 'estado' => '1']);

        $this->actingAs($clientUser);

        // Acceder a la ruta /mi-cuenta
        $response = $this->get('/mi-cuenta');
        $response->assertStatus(200);
        $response->assertSee('Luigi Bros');
        $response->assertSee('Filtro de Aceite');

        // Test Livewire component
        Livewire::test(ClientPortal::class)
            ->assertSee('Luigi')
            ->assertSee('Bros')
            ->assertSee('Filtro de Aceite');
    }

    public function test_client_role_is_redirected_from_dashboard_to_mi_cuenta(): void
    {
        $clientUser = User::factory()->create();
        $clientUser->assignRole('cliente');

        $this->actingAs($clientUser);

        $response = $this->get('/dashboard');
        $response->assertRedirect('/mi-cuenta');
    }

    public function test_client_role_is_blocked_from_staff_routes(): void
    {
        $clientUser = User::factory()->create();
        $clientUser->assignRole('cliente');

        $this->actingAs($clientUser);

        // Intento de acceder a venta, ordenes, productos, clientes, etc.
        $this->get('/venta')->assertRedirect('/mi-cuenta');
        $this->get('/productos')->assertRedirect('/mi-cuenta');
        $this->get('/ordenes')->assertRedirect('/mi-cuenta');
        $this->get('/stock')->assertRedirect('/mi-cuenta');
    }
}
