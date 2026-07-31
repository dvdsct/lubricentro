<?php

namespace Tests\Feature;

use App\Livewire\VehicleProfile;
use App\Models\MarcaVehiculo;
use App\Models\ModeloVehiculo;
use App\Models\Vehiculo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class VehicleProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_render_vehicle_profile_component(): void
    {
        $marca = MarcaVehiculo::create(['descripcion' => 'Toyota']);
        $modelo = ModeloVehiculo::create(['descripcion' => 'Corolla', 'marca_vehiculo_id' => $marca->id]);

        $vehiculo = Vehiculo::create([
            'dominio' => 'AA123BB',
            'color' => 'Gris',
            'version' => 'XEI 2.0',
            'año' => 2021,
            'modelo_vehiculo_id' => $modelo->id,
            'marca_vehiculo_id' => $marca->id,
            'estado' => 1,
        ]);

        Livewire::test(VehicleProfile::class, ['vehiculo' => $vehiculo])
            ->assertStatus(200)
            ->assertSee('AA123BB')
            ->assertSee('Toyota')
            ->assertSee('Corolla');
    }

    public function test_can_update_vehicle_profile_data(): void
    {
        $marca = MarcaVehiculo::create(['descripcion' => 'Ford']);
        $modelo = ModeloVehiculo::create(['descripcion' => 'Focus', 'marca_vehiculo_id' => $marca->id]);

        $vehiculo = Vehiculo::create([
            'dominio' => 'AB999CD',
            'color' => 'Negro',
            'version' => 'SE',
            'año' => 2018,
            'modelo_vehiculo_id' => $modelo->id,
            'marca_vehiculo_id' => $marca->id,
            'estado' => 1,
        ]);

        Livewire::test(VehicleProfile::class, ['vehiculo' => $vehiculo])
            ->call('openEditModal')
            ->assertSet('dominio', 'AB999CD')
            ->assertSet('color', 'Negro')
            ->set('dominio', 'AC111DD')
            ->set('color', 'Rojo')
            ->set('año', 2022)
            ->call('updateVehicle')
            ->assertHasNoErrors()
            ->assertSet('showEditModal', false);

        $this->assertDatabaseHas('vehiculos', [
            'id' => $vehiculo->id,
            'dominio' => 'AC111DD',
            'color' => 'Rojo',
            'año' => 2022,
        ]);
    }
}
