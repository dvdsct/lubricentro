<?php

namespace Tests\Feature;

use App\Livewire\AddProducts;
use App\Livewire\PreviewStock;
use App\Livewire\ViewTurnos;
use App\Models\Cliente;
use App\Models\Item;
use App\Models\ItemsXOrden;
use App\Models\Orden;
use App\Models\Perfil;
use App\Models\Persona;
use App\Models\Producto;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GranelAndFractionStockTest extends TestCase
{
    use RefreshDatabase;

    protected function setupEnvironment(): array
    {
        $user = User::factory()->create();

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

        $persona = Persona::create([
            'nombre' => 'Juan',
            'apellido' => 'Perez',
            'estado' => 1,
        ]);
        $perfil = Perfil::create(['persona_id' => $persona->id]);
        $cliente = Cliente::create(['perfil_id' => $perfil->id]);

        $cat = \App\Models\CategoriaProducto::create(['descripcion' => 'Lubricentro', 'estado' => 1]);
        $subcat = \App\Models\SubcategoriaProducto::create(['descripcion' => 'Fluidos', 'estado' => 1]);

        $granel = Producto::create([
            'descripcion' => '10W40 TOTAL GRANEL',
            'codigo' => 'GRANEL-10W40',
            'precio_venta' => 10000,
            'costo' => 5000,
            'categoria_producto_id' => $cat->id,
            'subcategoria_producto_id' => $subcat->id,
            'es_provisional' => 0,
        ]);

        $stockGranel = Stock::create([
            'producto_id' => $granel->id,
            'sucursal_id' => $sucursalId,
            'cantidad' => 50.0,
            'cantidad_num' => 50.0,
            'unidad' => 'Litros',
            'estado' => 1,
        ]);

        $tambor = Producto::create([
            'descripcion' => 'TAMBOR 10W40 TOTAL 205L',
            'codigo' => 'TAMB-10W40',
            'precio_venta' => 1500000,
            'costo' => 800000,
            'categoria_producto_id' => $cat->id,
            'subcategoria_producto_id' => $subcat->id,
            'es_provisional' => 0,
        ]);

        $stockTambor = Stock::create([
            'producto_id' => $tambor->id,
            'sucursal_id' => $sucursalId,
            'cantidad' => 3.0,
            'cantidad_num' => 3.0,
            'unidad' => 'Unidad',
            'estado' => 1,
        ]);

        $orden = Orden::create([
            'cliente_id' => $cliente->id,
            'sucursal_id' => $sucursalId,
            'motivo' => '2',
            'fecha_turno' => now()->format('Y-m-d'),
            'horario' => '10:00',
            'estado' => '1',
        ]);

        return [$user, $cliente, $granel, $stockGranel, $tambor, $stockTambor, $orden];
    }

    public function test_can_add_fractional_quantity_of_oil_with_dot_and_comma(): void
    {
        [$user, $cliente, $granel, $stockGranel, $tambor, $stockTambor, $orden] = $this->setupEnvironment();
        $this->actingAs($user);

        // 1. Agregar item inicial a la orden
        $item = Item::create([
            'producto_id' => $granel->id,
            'precio' => $granel->precio_venta,
            'estado' => '1',
        ]);
        ItemsXOrden::create([
            'item_id' => $item->id,
            'orden_id' => $orden->id,
            'estado' => '1',
        ]);

        // 2. Cargar 3.5 litros mediante Livewire AddProducts
        Livewire::test(AddProducts::class, ['orden' => $orden])
            ->set('cantidad', '3.5')
            ->call('addCantidad', $item->id)
            ->assertDispatched('suma-items');

        $item->refresh();
        $this->assertEquals(3.5, floatval($item->cantidad));
        $this->assertEquals(35000.0, floatval($item->subtotal)); // 3.5 * 10000

        // Stock original 50 - 3.5 = 46.5
        $this->assertEquals(46.5, floatval($stockGranel->fresh()->cantidad));

        // 3. Probar ahora ingresar con coma (ej. 4,25 L)
        $item2 = Item::create([
            'producto_id' => $granel->id,
            'precio' => $granel->precio_venta,
            'estado' => '1',
        ]);
        ItemsXOrden::create([
            'item_id' => $item2->id,
            'orden_id' => $orden->id,
            'estado' => '1',
        ]);

        Livewire::test(AddProducts::class, ['orden' => $orden])
            ->set('cantidad', '4,25')
            ->call('addCantidad', $item2->id)
            ->assertDispatched('suma-items');

        $item2->refresh();
        $this->assertEquals(4.25, floatval($item2->cantidad));
        $this->assertEquals(42500.0, floatval($item2->subtotal));

        // Stock 46.5 - 4.25 = 42.25
        $this->assertEquals(42.25, floatval($stockGranel->fresh()->cantidad));
    }

    public function test_can_fraction_tambor_to_granel_via_stock_service(): void
    {
        [$user, $cliente, $granel, $stockGranel, $tambor, $stockTambor, $orden] = $this->setupEnvironment();
        $this->actingAs($user);

        $stockService = app(StockService::class);

        // Fraccionar 1 tambor de 205 L al granel
        $result = $stockService->fractionStock(1, $tambor->id, 1, $granel->id, 205, [
            'motivo' => 'Apertura de tambor de 205L',
            'user_id' => $user->id,
        ]);

        $this->assertTrue($result);

        // Tambores: 3 - 1 = 2
        $this->assertEquals(2.0, floatval($stockTambor->fresh()->cantidad));

        // Granel: 50 + 205 = 255
        $this->assertEquals(255.0, floatval($stockGranel->fresh()->cantidad));

        // Verificar trazabilidad de movimientos
        $movSalida = StockMovement::where('producto_id', $tambor->id)->latest()->first();
        $this->assertNotNull($movSalida);
        $this->assertEquals(-1.0, floatval($movSalida->delta));
        $this->assertEquals('Fraccionamiento (Salida)', $movSalida->operacion);

        $movIngreso = StockMovement::where('producto_id', $granel->id)->latest()->first();
        $this->assertNotNull($movIngreso);
        $this->assertEquals(205.0, floatval($movIngreso->delta));
        $this->assertEquals('Fraccionamiento (Ingreso)', $movIngreso->operacion);
    }

    public function test_preview_stock_fraction_modal_execution(): void
    {
        [$user, $cliente, $granel, $stockGranel, $tambor, $stockTambor, $orden] = $this->setupEnvironment();
        $this->actingAs($user);

        Livewire::test(PreviewStock::class)
            ->call('openFractionModal')
            ->set('sourceProductoId', $tambor->id)
            ->set('sourceQty', '1')
            ->set('destProductoId', $granel->id)
            ->set('litrosPorEnvase', '205')
            ->set('fractionMotivo', 'Desarme para surtidor')
            ->call('confirmFraction')
            ->assertHasNoErrors()
            ->assertDispatched('stock-fractioned');

        // Stock verificado
        $this->assertEquals(2.0, floatval($stockTambor->fresh()->cantidad));
        $this->assertEquals(255.0, floatval($stockGranel->fresh()->cantidad));
    }
}
