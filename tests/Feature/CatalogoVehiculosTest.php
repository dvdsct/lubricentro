<?php

namespace Tests\Feature;

use App\Livewire\AddPresupuesto;
use App\Livewire\CatalogoVehiculos;
use App\Livewire\FormCreateOrder;
use App\Models\Cliente;
use App\Models\MarcaVehiculo;
use App\Models\ModeloVehiculo;
use App\Models\Perfil;
use App\Models\Persona;
use App\Models\TipoVehiculo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CatalogoVehiculosTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        TipoVehiculo::create(['descripcion' => 'AUTO', 'estado' => 1]);
        TipoVehiculo::create(['descripcion' => 'CAMIONETA', 'estado' => 1]);
        MarcaVehiculo::create(['descripcion' => 'FORD', 'tipo_vehiculo_id' => 1]);
    }

    public function test_catalogo_puede_crear_nuevo_modelo_con_marca_y_tipo_existentes(): void
    {
        $tipo = TipoVehiculo::where('descripcion', 'AUTO')->first();
        $marca = MarcaVehiculo::where('descripcion', 'FORD')->first();

        Livewire::test(CatalogoVehiculos::class)
            ->call('openModal')
            ->set('tipo_id', $tipo->id)
            ->set('marca_id', $marca->id)
            ->set('nuevo_modelo_nombre', 'Focus III')
            ->call('saveModelo');

        $this->assertDatabaseHas('modelo_vehiculos', [
            'descripcion' => 'Focus III',
            'marca_vehiculo_id' => $marca->id,
            'tipo_vehiculo_id' => $tipo->id,
        ]);
    }

    public function test_catalogo_puede_crear_nueva_marca_como_bmw_y_modelo(): void
    {
        $tipo = TipoVehiculo::where('descripcion', 'AUTO')->first();

        Livewire::test(CatalogoVehiculos::class)
            ->call('openModal')
            ->set('tipo_id', $tipo->id)
            ->call('toggleNuevaMarca')
            ->set('nueva_marca_nombre', 'BMW')
            ->set('nuevo_modelo_nombre', 'Serie 3 320i')
            ->call('saveModelo');

        $this->assertDatabaseHas('marca_vehiculos', [
            'descripcion' => 'BMW',
            'tipo_vehiculo_id' => $tipo->id,
        ]);

        $bmw = MarcaVehiculo::where('descripcion', 'BMW')->first();
        $this->assertDatabaseHas('modelo_vehiculos', [
            'descripcion' => 'Serie 3 320i',
            'marca_vehiculo_id' => $bmw->id,
        ]);
    }

    public function test_add_presupuesto_permite_crear_marca_y_modelo_y_los_preselecciona(): void
    {
        $persona = Persona::create(['nombre' => 'Test', 'apellido' => 'Presu', 'estado' => 1]);
        $perfil = Perfil::create(['persona_id' => $persona->id]);
        $cliente = Cliente::create(['perfil_id' => $perfil->id]);

        $tipo = TipoVehiculo::where('descripcion', 'AUTO')->first();

        $comp = Livewire::test(AddPresupuesto::class)
            ->set('cliente', $cliente)
            ->call('openModalNuevoCatalogo')
            ->set('cat_tipo_id', $tipo->id)
            ->call('toggleCatNuevaMarca')
            ->set('cat_nueva_marca_nombre', 'AUDI')
            ->set('cat_nuevo_modelo_nombre', 'A3 Sportback')
            ->call('saveNuevoCatalogo');

        $this->assertDatabaseHas('marca_vehiculos', ['descripcion' => 'AUDI']);
        $this->assertDatabaseHas('modelo_vehiculos', ['descripcion' => 'A3 Sportback']);

        $audi = MarcaVehiculo::where('descripcion', 'AUDI')->first();
        $modelo = ModeloVehiculo::where('descripcion', 'A3 Sportback')->first();

        $comp->assertSet('tipo', $tipo->id)
             ->assertSet('marca', $audi->id)
             ->assertSet('modelo', $modelo->id);
    }

    public function test_add_presupuesto_permite_crear_solo_marca_sin_modelo(): void
    {
        $persona = Persona::create(['nombre' => 'Test', 'apellido' => 'Presu', 'estado' => 1]);
        $perfil = Perfil::create(['persona_id' => $persona->id]);
        $cliente = Cliente::create(['perfil_id' => $perfil->id]);

        $tipo = TipoVehiculo::where('descripcion', 'AUTO')->first();

        $comp = Livewire::test(AddPresupuesto::class)
            ->set('cliente', $cliente)
            ->call('openModalNuevoCatalogo', 'marca')
            ->set('cat_tipo_id', $tipo->id)
            ->set('cat_nueva_marca_nombre', 'FERRARI')
            ->call('saveNuevoCatalogo');

        $this->assertDatabaseHas('marca_vehiculos', ['descripcion' => 'FERRARI']);

        $ferrari = MarcaVehiculo::where('descripcion', 'FERRARI')->first();

        $comp->assertSet('tipo', $tipo->id)
             ->assertSet('marca', $ferrari->id);
    }

    public function test_form_create_order_permite_crear_marca_y_modelo_y_los_preselecciona(): void
    {
        $tipo = TipoVehiculo::where('descripcion', 'AUTO')->first();

        $comp = Livewire::test(FormCreateOrder::class, ['fecha' => now()->format('Y-m-d')])
            ->call('openModalNuevoCatalogo')
            ->set('cat_tipo_id', $tipo->id)
            ->call('toggleCatNuevaMarca')
            ->set('cat_nueva_marca_nombre', 'BMW')
            ->set('cat_nuevo_modelo_nombre', 'X5')
            ->call('saveNuevoCatalogo');

        $this->assertDatabaseHas('marca_vehiculos', ['descripcion' => 'BMW']);
        $this->assertDatabaseHas('modelo_vehiculos', ['descripcion' => 'X5']);

        $bmw = MarcaVehiculo::where('descripcion', 'BMW')->first();
        $modelo = ModeloVehiculo::where('descripcion', 'X5')->first();

        $comp->assertSet('tipo', $tipo->id)
             ->assertSet('marca', $bmw->id)
             ->assertSet('modelo', $modelo->id);
    }
}
