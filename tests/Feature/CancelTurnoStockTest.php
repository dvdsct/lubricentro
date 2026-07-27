<?php

namespace Tests\Feature;

use App\Livewire\ViewTurnos;
use App\Models\Cliente;
use App\Models\Item;
use App\Models\ItemsXOrden;
use App\Models\Orden;
use App\Models\Perfil;
use App\Models\Persona;
use App\Models\Producto;
use App\Models\Stock;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CancelTurnoStockTest extends TestCase
{
    use RefreshDatabase;

    protected function createSampleOrderWithStock(): array
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
            'nombre' => 'Test',
            'apellido' => 'User',
            'estado' => 1,
        ]);
        $perfil = Perfil::create(['persona_id' => $persona->id]);
        $cliente = Cliente::create(['perfil_id' => $perfil->id]);

        $producto = Producto::create([
            'descripcion' => 'Aceite 10W40',
            'codigo' => 'ACE1040',
            'precio_venta' => 5000,
            'es_provisional' => 0,
            'estado' => 1,
        ]);

        $stock = Stock::create([
            'producto_id' => $producto->id,
            'sucursal_id' => $sucursalId,
            'cantidad' => 10,
            'cantidad_num' => 10,
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

        return [$user, $producto, $stock, $orden];
    }

    public function test_cancel_turno_returns_deducted_stock_for_single_item(): void
    {
        [$user, $producto, $stock, $orden] = $this->createSampleOrderWithStock();

        $item = Item::create([
            'producto_id' => $producto->id,
            'precio' => 5000,
            'cantidad' => 2,
            'subtotal' => 10000,
            'estado' => '2',
        ]);
        ItemsXOrden::create([
            'item_id' => $item->id,
            'orden_id' => $orden->id,
            'estado' => '1',
        ]);

        // Simular descuento de stock al agregar el ítem (como hace AddProducts)
        $stockService = app(StockService::class);
        $stockService->adjustStock(1, $producto->id, -2, [
            'motivo' => 'Modificación de cantidad en orden',
            'operacion' => 'Carga en orden',
            'referencia_type' => 'Item',
            'referencia_id' => $item->id,
        ]);

        // Verificar que el stock bajó a 8
        $this->assertEquals(8, $stock->fresh()->cantidad);

        $this->actingAs($user);

        Livewire::test(ViewTurnos::class)
            ->call('cancelTurn', $orden->id);

        // El stock debe haber vuelto a 10
        $this->assertEquals(10, $stock->fresh()->cantidad);
        $this->assertEquals('700', $orden->fresh()->estado);
    }

    public function test_cancel_turno_with_multiple_items_of_same_product_returns_correct_stock(): void
    {
        [$user, $producto, $stock, $orden] = $this->createSampleOrderWithStock();

        $item1 = Item::create([
            'producto_id' => $producto->id,
            'precio' => 5000,
            'cantidad' => 2,
            'subtotal' => 10000,
            'estado' => '2',
        ]);
        ItemsXOrden::create(['item_id' => $item1->id, 'orden_id' => $orden->id, 'estado' => '1']);

        $item2 = Item::create([
            'producto_id' => $producto->id,
            'precio' => 5000,
            'cantidad' => 3,
            'subtotal' => 15000,
            'estado' => '2',
        ]);
        ItemsXOrden::create(['item_id' => $item2->id, 'orden_id' => $orden->id, 'estado' => '1']);

        $stockService = app(StockService::class);
        $stockService->adjustStock(1, $producto->id, -2, [
            'referencia_type' => 'Item',
            'referencia_id' => $item1->id,
        ]);
        $stockService->adjustStock(1, $producto->id, -3, [
            'referencia_type' => 'Item',
            'referencia_id' => $item2->id,
        ]);

        // Stock bajó 5 unidades -> quedan 5
        $this->assertEquals(5, $stock->fresh()->cantidad);

        $this->actingAs($user);

        Livewire::test(ViewTurnos::class)
            ->call('cancelTurn', $orden->id);

        // Stock debe retornar exactamente a 10 (las 5 unidades restadas), no duplicarse
        $this->assertEquals(10, $stock->fresh()->cantidad);
        $this->assertEquals('700', $orden->fresh()->estado);
    }

    public function test_cancel_turno_fallback_when_no_stock_movements_exist(): void
    {
        [$user, $producto, $stock, $orden] = $this->createSampleOrderWithStock();

        $item = Item::create([
            'producto_id' => $producto->id,
            'precio' => 5000,
            'cantidad' => 3,
            'subtotal' => 15000,
            'estado' => '2',
        ]);
        ItemsXOrden::create(['item_id' => $item->id, 'orden_id' => $orden->id, 'estado' => '1']);

        // No se crea registro en StockMovement (simula ítem antiguo o sin registro)
        $this->assertEquals(10, $stock->fresh()->cantidad);

        $this->actingAs($user);

        Livewire::test(ViewTurnos::class)
            ->call('cancelTurn', $orden->id);

        // Debe usar el fallback de suma de cantidades y sumar 3 al stock (quedando 13)
        $this->assertEquals(13, $stock->fresh()->cantidad);
        $this->assertEquals('700', $orden->fresh()->estado);
    }
}
