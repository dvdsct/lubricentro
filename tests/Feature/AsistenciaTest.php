<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Asistencia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AsistenciaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Limpiar caché de permisos de Spatie
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_public_scanner_page_can_be_rendered(): void
    {
        $response = $this->get(route('asistencia.scan'));
        $response->assertStatus(200);
        $response->assertSee('Ingresar PIN de Acceso');
    }

    public function test_verify_pin_fails_with_invalid_pin(): void
    {
        $response = $this->post(route('asistencia.verify-pin'), [
            'pin' => 'wrong-pin',
        ]);
        $response->assertRedirect();
        $response->assertSessionHasErrors('pin');
        $this->assertNull(session('asistencia_pin_verified'));
    }

    public function test_verify_pin_succeeds_with_correct_pin(): void
    {
        $response = $this->post(route('asistencia.verify-pin'), [
            'pin' => '1226500',
        ]);
        $response->assertRedirect(route('asistencia.registrar'));
        $this->assertTrue(session('asistencia_pin_verified'));
    }

    public function test_registrar_requires_pin_verified(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('asistencia.registrar'));
        $response->assertRedirect(route('asistencia.scan'));
    }

    public function test_registrar_requires_authentication(): void
    {
        session(['asistencia_pin_verified' => true]);
        $response = $this->get(route('asistencia.registrar'));
        $response->assertRedirect(route('login'));
    }

    public function test_registrar_renders_when_pin_verified_and_authenticated(): void
    {
        session(['asistencia_pin_verified' => true]);
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('asistencia.registrar'));
        $response->assertStatus(200);
        $response->assertSee('Fichaje de Asistencia');
        $response->assertSee(e($user->name));
    }

    public function test_store_creates_attendance_record_and_toggles_type(): void
    {
        session(['asistencia_pin_verified' => true]);
        $user = User::factory()->create();

        // 1. Primera marca (debe ser Entrada)
        $response = $this->actingAs($user)->post(route('asistencia.store'), [
            'latitud' => -34.603722,
            'longitud' => -58.381592,
        ]);

        $response->assertStatus(200);
        $response->assertSee('¡Fichaje Exitoso!');
        $this->assertDatabaseHas('asistencias', [
            'user_id' => $user->id,
            'tipo' => 'entrada',
            'latitud' => -34.60372200,
            'longitud' => -58.38159200,
        ]);
        $this->assertNull(session('asistencia_pin_verified'));

        // 2. Segunda marca (debe ser Salida)
        session(['asistencia_pin_verified' => true]);
        $response = $this->actingAs($user)->post(route('asistencia.store'), [
            'latitud' => -34.603722,
            'longitud' => -58.381592,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('asistencias', [
            'user_id' => $user->id,
            'tipo' => 'salida',
        ]);
    }

    public function test_admin_control_page_requires_adminCajas_permission(): void
    {
        // 1. Un usuario común no debería acceder
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'web')->get(route('asistencia.control'));
        $response->assertStatus(403);

        // 2. Un administrador (con el permiso adminCajas) debe acceder
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $permission = \Spatie\Permission\Models\Permission::create(['name' => 'adminCajas', 'guard_name' => 'web']);
        $permission->assignRole($adminRole);

        $adminUser = User::factory()->create();
        $adminUser->assignRole($adminRole);

        $response = $this->actingAs($adminUser, 'web')->get(route('asistencia.control'));
        $response->assertStatus(200);
        $response->assertSee('CONTROL DE ASISTENCIA');
    }
}
