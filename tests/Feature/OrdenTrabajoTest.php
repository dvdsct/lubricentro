<?php

namespace Tests\Feature;

use App\Livewire\VehicleProfile;
use App\Models\Cliente;
use App\Models\MarcaVehiculo;
use App\Models\ModeloVehiculo;
use App\Models\OrdenTrabajo;
use App\Models\Perfil;
use App\Models\Persona;
use App\Models\TipoVehiculo;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class OrdenTrabajoTest extends TestCase
{
    use RefreshDatabase;

    protected Vehiculo $vehiculo;
    protected Cliente $cliente;
    protected User $mecanico;

    protected function setUp(): void
    {
        parent::setUp();

        $tipo = TipoVehiculo::create(['descripcion' => 'AUTO', 'estado' => 1]);
        $marca = MarcaVehiculo::create(['descripcion' => 'FORD', 'tipo_vehiculo_id' => $tipo->id]);
        $modelo = ModeloVehiculo::create([
            'descripcion' => 'FOCUS III',
            'marca_vehiculo_id' => $marca->id,
            'tipo_vehiculo_id' => $tipo->id,
            'estado' => '1',
        ]);

        $this->vehiculo = Vehiculo::create([
            'dominio' => 'AF123CD',
            'color' => 'Blanco',
            'version' => 'SE Plus 2.0',
            'año' => 2018,
            'modelo_vehiculo_id' => $modelo->id,
            'estado' => 1,
        ]);

        $persona = Persona::create(['nombre' => 'Carlos', 'apellido' => 'Perez', 'DNI' => '30111222', 'estado' => 1]);
        $perfil = Perfil::create(['persona_id' => $persona->id]);
        $this->cliente = Cliente::create(['perfil_id' => $perfil->id]);

        $this->vehiculo->clientes()->attach($this->cliente->id);

        $roleMecanico = Role::firstOrCreate(['name' => 'mecánico', 'guard_name' => 'web']);
        $this->mecanico = User::factory()->create(['name' => 'Julio Mendez']);
        $this->mecanico->assignRole($roleMecanico);
    }

    public function test_mecanico_puede_acceder_al_perfil_del_vehiculo(): void
    {
        $response = $this->actingAs($this->mecanico, 'web')->get(route('vehiculos.perfil', $this->vehiculo->id));
        $response->assertStatus(200);
        $response->assertSee('AF123CD');
        $response->assertSee('Cargar Ficha de Trabajo');
    }

    public function test_mecanico_calcula_automaticamente_proximo_service_al_ingresar_km(): void
    {
        $comp = Livewire::actingAs($this->mecanico)
            ->test(VehicleProfile::class, ['vehiculo' => $this->vehiculo])
            ->call('openOrdenTrabajoModal')
            ->set('ot_km_entrada', '140000');

        $comp->assertSet('ot_km_proximo_service', '150000');
    }

    public function test_mecanico_puede_guardar_orden_de_trabajo_completa(): void
    {
        $comp = Livewire::actingAs($this->mecanico)
            ->test(VehicleProfile::class, ['vehiculo' => $this->vehiculo])
            ->call('openOrdenTrabajoModal')
            ->set('ot_km_entrada', '125000')
            ->set('ot_km_proximo_service', '135000')
            ->set('ot_tipo_aceite', '10W-40 Semi-sintético')
            ->set('ot_litros_aceite', '4.5')
            ->set('ot_aceite_envasado', true)
            ->set('ot_filtro_aceite', true)
            ->set('ot_filtro_aceite_detalle', 'Wega WO-205')
            ->set('ot_filtro_aire', true)
            ->set('ot_filtro_aire_detalle', 'Wega FAP-2840')
            ->set('ot_controles.frenos', true)
            ->set('ot_controles.escaneo', true)
            ->set('ot_observaciones', 'Pastillas de freno al 60%')
            ->set('ot_control_final', true)
            ->call('saveOrdenTrabajo');

        $comp->assertHasNoErrors();

        $this->assertDatabaseHas('ordenes_trabajo', [
            'vehiculo_id' => $this->vehiculo->id,
            'km_entrada' => '125000',
            'km_proximo_service' => '135000',
            'tipo_aceite' => '10W-40 Semi-sintético',
            'litros_aceite' => '4.5',
            'aceite_envasado' => true,
            'filtro_aceite' => true,
            'filtro_aceite_detalle' => 'Wega WO-205',
            'filtro_aire' => true,
            'filtro_aire_detalle' => 'Wega FAP-2840',
            'control_final' => true,
        ]);
    }

    public function test_generar_pdf_de_orden_de_trabajo(): void
    {
        $ot = OrdenTrabajo::create([
            'vehiculo_id' => $this->vehiculo->id,
            'cliente_id' => $this->cliente->id,
            'user_id' => $this->mecanico->id,
            'fecha' => now(),
            'km_entrada' => '120000',
            'km_proximo_service' => '130000',
            'tipo_aceite' => '5W-30 Sintético',
            'litros_aceite' => '4.2',
            'aceite_envasado' => true,
            'filtro_aceite' => true,
            'filtro_aceite_detalle' => 'Wega WO-150',
            'operador_responsable' => 'Julio Mendez',
            'control_final' => true,
        ]);

        $response = $this->actingAs($this->mecanico, 'web')->get(route('pdf.orden-trabajo', $ot->id));
        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }
}
