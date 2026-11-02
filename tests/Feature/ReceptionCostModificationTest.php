<?php

namespace Tests\Feature;

use App\Livewire\AddProductsPP;
use App\Models\CategoriaProducto;
use App\Models\ItemXPedido;
use App\Models\PedItem;
use App\Models\PedidoProveedor;
use App\Models\PedidoProveedorItem;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\TipoFactura;
use App\Models\TipoPedido;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class ReceptionCostModificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_modificar_costo_unitario_al_recepcionar_mercaderia_actualiza_producto_y_stock()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Sucursal 1
        DB::table('sucursals')->insertOrIgnore([
            'id' => 1,
            'nombre_sucursal' => 'Central',
            'estado' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $cat = CategoriaProducto::create(['descripcion' => 'Filtros', 'estado' => 1]);
        $producto = Producto::create([
            'descripcion' => 'Filtro de Aceite WEGA',
            'codigo' => 'W-712',
            'categoria_producto_id' => $cat->id,
            'costo' => 4840.00,
            'precio_venta' => 7744.00,
            'estado' => 1,
        ]);

        $tipoFacId = DB::table('tipo_facturas')->insertGetId(['descripcion' => 'Factura A', 'estado' => 1]);
        $tipoPedId = DB::table('tipo_pedidos')->insertGetId(['descripcion' => 'Compra General', 'estado' => 1]);

        $persona = \App\Models\Persona::create([
            'nombre' => 'Proveedor',
            'apellido' => 'SRL',
            'estado' => 1,
        ]);
        $perfil = \App\Models\Perfil::create(['persona_id' => $persona->id]);

        $proveedor = Proveedor::create([
            'perfil_id' => $perfil->id,
            'tipo' => 'empresa',
            'nombre_fantasia' => 'Proveedor Repuestos SRL',
            'cuit' => '30112233449',
            'estado' => 1,
        ]);

        $pedido = PedidoProveedor::create([
            'proveedor_id' => $proveedor->id,
            'tipo_pedido_id' => $tipoPedId,
            'sucursal_id' => 1,
            'estado' => 'autorizada',
            'total_estimado' => 48400.00,
            'usuario_creador_id' => $user->id,
        ]);

        $pedItem = PedItem::create([
            'producto_id' => $producto->id,
            'cantidad' => 10,
            'precio' => 4840.00,
            'subtotal' => 48400.00,
            'estado' => '1',
        ]);

        ItemXPedido::create([
            'pedido_proveedor_id' => $pedido->id,
            'ped_item_id' => $pedItem->id,
            'estado' => '1',
        ]);

        $ppi = PedidoProveedorItem::create([
            'pedido_proveedor_id' => $pedido->id,
            'producto_id' => $producto->id,
            'cantidad_pedida' => 10,
            'cantidad_recibida' => 0,
            'costo_unitario' => 4840.00,
            'subtotal' => 48400.00,
            'estado_item' => 'pendiente',
        ]);

        $nuevoCosto = 5500.00;
        $totalNuevoCalculado = 10 * 5500.00; // 55000.00

        Livewire::test(AddProductsPP::class, ['pedido' => $pedido, 'proveedor' => $proveedor])
            ->call('openModalRecepcion')
            ->assertSet("receiveCost.{$producto->id}", 4840.0)
            ->set("receiveCost.{$producto->id}", $nuevoCosto)
            ->set("receiveQty.{$producto->id}", 10)
            ->call('copiarTotalAceptado')
            ->assertSet('totalFactura', '55000.00')
            ->set('numeroFactura', '0001-00045231')
            ->set('tipoFacturaId', $tipoFacId)
            ->set('fechaFactura', now()->format('Y-m-d'))
            ->call('procesarRecepcion')
            ->assertHasNoErrors();

        // 1. Producto actualizado con nuevo costo y precio con 60% margen
        $producto->refresh();
        $this->assertEquals(5500.00, floatval($producto->costo));
        $precioEsperado = 5500.00 + (5500.00 * 0.60); // 8800.00
        $this->assertEquals($precioEsperado, floatval($producto->precio_venta));

        // 2. PedidoProveedorItem actualizado con nuevo costo
        $ppi->refresh();
        $this->assertEquals(5500.00, floatval($ppi->costo_unitario));
        $this->assertEquals(55000.00, floatval($ppi->subtotal));
        $this->assertEquals(10, intval($ppi->cantidad_recibida));
        $this->assertEquals('recibido_total', $ppi->estado_item);

        // 3. Stock ingresado con nuevo precio unitario
        $stock = Stock::where('producto_id', $producto->id)->where('sucursal_id', 1)->first();
        $this->assertNotNull($stock);
        $this->assertEquals(10, intval($stock->cantidad));

        $movement = StockMovement::where('producto_id', $producto->id)->latest('id')->first();
        $this->assertNotNull($movement);
        $this->assertEquals(5500.00, floatval($movement->precio_unitario));
        $this->assertEquals(10, intval($movement->delta));
        $this->assertEquals(55000.00, floatval($movement->monto_total));
    }

    public function test_buscador_en_modal_agregar_item_no_falla_con_metodo_search()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        DB::table('sucursals')->insertOrIgnore([
            'id' => 1,
            'nombre_sucursal' => 'Central',
            'estado' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $tipoPedId = DB::table('tipo_pedidos')->insertGetId(['descripcion' => 'Compra General', 'estado' => 1]);

        $persona = \App\Models\Persona::create([
            'nombre' => 'Proveedor',
            'apellido' => 'SRL',
            'estado' => 1,
        ]);
        $perfil = \App\Models\Perfil::create(['persona_id' => $persona->id]);

        $proveedor = Proveedor::create([
            'perfil_id' => $perfil->id,
            'tipo' => 'empresa',
            'nombre_fantasia' => 'Proveedor Repuestos SRL',
            'cuit' => '30112233449',
            'estado' => 1,
        ]);

        $pedido = PedidoProveedor::create([
            'proveedor_id' => $proveedor->id,
            'tipo_pedido_id' => $tipoPedId,
            'sucursal_id' => 1,
            'estado' => 'autorizada',
            'total_estimado' => 0,
            'usuario_creador_id' => $user->id,
        ]);

        Livewire::test(AddProductsPP::class, ['pedido' => $pedido, 'proveedor' => $proveedor])
            ->call('modalProdOn')
            ->assertSet('modal', true)
            ->set('query', 'Filtro')
            ->call('search')
            ->assertHasNoErrors();
    }
}
