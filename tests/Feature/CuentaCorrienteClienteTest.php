<?php

namespace Tests\Feature;

use App\Models\Cajero;
use App\Models\Cliente;
use App\Models\Factura;
use App\Models\Item;
use App\Models\ItemsXOrden;
use App\Models\ModeloVehiculo;
use App\Models\Orden;
use App\Models\Pago;
use App\Models\PagoCtacte;
use App\Models\Perfil;
use App\Models\Persona;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\TipoVehiculo;
use App\Models\User;
use App\Models\Vehiculo;
use Database\Seeders\LorenaCajeroSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CuentaCorrienteClienteTest extends TestCase
{
    public function test_lorena_seeder_crea_usuario_cajero_con_permisos_correctos(): void
    {
        $this->seed(LorenaCajeroSeeder::class);

        $user = User::where('email', 'lorena@test.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('cajero'));

        // Permisos requeridos
        $this->assertTrue($user->can('hacerPedido'), 'Debe tener permiso para Órdenes de compra');
        $this->assertTrue($user->can('recibirPedido'), 'Debe tener permiso para Recibir órdenes de compra');
        $this->assertTrue($user->can('crearProveedores'), 'Debe tener permiso para Crear proveedores');
    }

    public function test_form_pago_lista_todos_los_clientes_y_no_solo_consumidor_final(): void
    {
        $persona = Persona::create([
            'nombre' => 'Marcos',
            'apellido' => 'Test',
            'DNI' => '44556677',
            'estado' => 1
        ]);
        $perfil = Perfil::create(['persona_id' => $persona->id]);
        $clienteNuevo = Cliente::create(['perfil_id' => $perfil->id]); // lista_precios es NULL

        $component = Livewire::test(\App\Livewire\FormPago::class);
        $clientes = $component->get('clientes');

        $this->assertTrue(
            $clientes->contains('id', $clienteNuevo->id),
            'El nuevo cliente debe estar en el listado de clientes de FormPago'
        );
    }

    public function test_pago_con_cuenta_corriente_se_imputa_al_cliente_correcto(): void
    {
        $this->seed(\Database\Seeders\TipoFacturaSeed::class);
        $this->seed(\Database\Seeders\PagosSeeder::class);
        $persona = Persona::create([
            'nombre' => 'Esteban',
            'apellido' => 'Quito',
            'DNI' => '99887766',
            'estado' => 1
        ]);
        $perfil = Perfil::create(['persona_id' => $persona->id]);
        $cliente = Cliente::create(['perfil_id' => $perfil->id]);

        $tipoV = new TipoVehiculo();
        $tipoV->descripcion = 'Auto';
        $tipoV->estado = 1;
        $tipoV->save();

        $marcaV = new \App\Models\MarcaVehiculo();
        $marcaV->descripcion = 'Toyota';
        $marcaV->tipo_vehiculo_id = $tipoV->id;
        $marcaV->save();

        $modeloV = new ModeloVehiculo();
        $modeloV->descripcion = 'Corolla';
        $modeloV->marca_vehiculo_id = $marcaV->id;
        $modeloV->tipo_vehiculo_id = $tipoV->id;
        $modeloV->save();

        $vehiculo = new Vehiculo();
        $vehiculo->modelo_vehiculo_id = $modeloV->id;
        $vehiculo->dominio = 'AB123CD';
        $vehiculo->estado = 1;
        $vehiculo->save();

        $orden = Orden::create([
            'cliente_id' => $cliente->id,
            'vehiculo_id' => $vehiculo->id,
            'sucursal_id' => 1,
            'motivo' => 2,
            'estado' => 1,
        ]);

        $cat = new \App\Models\CategoriaProducto();
        $cat->descripcion = 'Aceites';
        $cat->estado = 1;
        $cat->save();

        $subcat = new \App\Models\SubcategoriaProducto();
        $subcat->descripcion = 'Sintéticos';
        $subcat->estado = 1;
        $subcat->save();

        $prod = new Producto();
        $prod->categoria_producto_id = $cat->id;
        $prod->subcategoria_producto_id = $subcat->id;
        $prod->descripcion = 'Aceite 5W30';
        $prod->costo = 1000;
        $prod->precio_venta = 1500;
        $prod->estado = 1;
        $prod->save();

        $item = new Item();
        $item->producto_id = $prod->id;
        $item->precio = 1500;
        $item->cantidad = 2;
        $item->subtotal = 3000;
        $item->estado = 1;
        $item->save();
        ItemsXOrden::create([
            'item_id' => $item->id,
            'orden_id' => $orden->id,
            'estado' => 1,
        ]);

        $cajeroUser = User::where('email', 'lorena@test.com')->first();
        $this->actingAs($cajeroUser);

        Livewire::test(\App\Livewire\FormPago::class, ['orden' => $orden])
            ->set('medioPago', 4) // Cuenta corriente
            ->set('cliente', $cliente->id)
            ->call('pagar');

        $pagoCta = PagoCtacte::where('cliente_id', $cliente->id)->first();
        $this->assertNotNull($pagoCta, 'Debe existir un registro en PagoCtacte para Esteban Quito');
        $this->assertEquals(-3000, floatval($pagoCta->total));
        $this->assertEquals('debe', $pagoCta->estado);
    }
}
