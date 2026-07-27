<?php

namespace Tests\Feature;

use App\Livewire\ClientProfile;
use App\Models\Cliente;
use App\Models\Perfil;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ClientProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_render_client_profile_component(): void
    {
        $user = User::factory()->create();

        $persona = Persona::create([
            'nombre' => 'Juan',
            'apellido' => 'Perez',
            'DNI' => '12345678',
            'numero_telefono' => '11223344',
            'fecha_nac' => '1990-01-01',
            'estado' => 1,
        ]);

        $perfil = Perfil::create([
            'persona_id' => $persona->id,
        ]);

        $cliente = Cliente::create([
            'perfil_id' => $perfil->id,
        ]);

        $this->actingAs($user);

        Livewire::test(ClientProfile::class, ['cliente' => $cliente])
            ->assertSee('Juan')
            ->assertSee('Perez')
            ->assertSee('12345678')
            ->assertSee('Modificar Datos');
    }

    public function test_can_update_client_profile_data(): void
    {
        $user = User::factory()->create();

        $persona = Persona::create([
            'nombre' => 'Carlos',
            'apellido' => 'Gomez',
            'DNI' => '11111111',
            'numero_telefono' => '55555555',
            'fecha_nac' => '1985-05-15',
            'estado' => 1,
        ]);

        $perfil = Perfil::create([
            'persona_id' => $persona->id,
        ]);

        $cliente = Cliente::create([
            'perfil_id' => $perfil->id,
        ]);

        $this->actingAs($user);

        Livewire::test(ClientProfile::class, ['cliente' => $cliente])
            ->call('openEditModal')
            ->set('nombre', 'Carlos Alberto')
            ->set('apellido', 'Gomez Lopez')
            ->set('dni', '22222222')
            ->set('numero_telefono', '99999999')
            ->set('fecha_nac', '1985-05-20')
            ->call('updateClient')
            ->assertHasNoErrors();

        $persona->refresh();
        $this->assertEquals('Carlos Alberto', $persona->nombre);
        $this->assertEquals('Gomez Lopez', $persona->apellido);
        $this->assertEquals('22222222', $persona->DNI);
        $this->assertEquals('99999999', $persona->numero_telefono);
        $this->assertEquals('1985-05-20', $persona->fecha_nac);
    }
}
