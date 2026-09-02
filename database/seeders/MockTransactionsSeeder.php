<?php

namespace Database\Seeders;

use App\Models\Caja;
use App\Models\Cajero;
use App\Models\Cliente;
use App\Models\Factura;
use App\Models\Orden;
use App\Models\Pago;
use App\Models\PagoCtacte;
use App\Models\PagoTarjeta;
use App\Models\PagoTransferencia;
use App\Models\PagosXCaja;
use App\Models\Persona;
use App\Models\Perfil;
use App\Models\Sucursal;
use App\Models\Vehiculo;
use App\Models\MedioPago;
use App\Models\TipoPago;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MockTransactionsSeeder extends Seeder
{
    public function run()
    {
        // Desactivar constraints de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Asegurarse de tener un Cajero y Sucursal
        $sucursal = Sucursal::firstOrCreate(['id' => 1], [
            'nombre_sucursal' => 'Av. Lugones',
            'estado' => '1'
        ]);

        $cajero = Cajero::first();
        if (!$cajero) {
            $persona = Persona::create([
                'nombre' => 'Hugo',
                'apellido' => 'Larcher',
                'DNI' => '33520739',
                'fecha_nac' => '1985-10-27',
                'estado' => '1',
            ]);
            $perfil = Perfil::create([
                'persona_id' => $persona->id,
            ]);
            $cajero = Cajero::create([
                'perfil_id' => $perfil->id,
                'sucursal_id' => $sucursal->id,
            ]);
        }

        // Cliente y Vehículo de prueba
        $cliente = Cliente::first();
        if (!$cliente) {
            $personaCl = Persona::create([
                'nombre' => 'Juan',
                'apellido' => 'Perez',
                'DNI' => '22444666',
                'fecha_nac' => '1990-05-15',
                'estado' => '1',
            ]);
            $perfilCl = Perfil::create([
                'persona_id' => $personaCl->id,
            ]);
            $cliente = Cliente::create([
                'perfil_id' => $perfilCl->id,
                'categoria' => '2',
                'lista_precios' => '3',
            ]);
        }

        $vehiculo = Vehiculo::firstOrCreate(['id' => 1], [
            'dominio' => 'AB123CD',
            'modelo_vehiculo_id' => 1,
            'color' => 'Rojo',
            'estado' => '1',
        ]);

        // Asegurarnos de tener los medios de pago cargados
        $medios = [
            1 => 'Tarjeta Credito',
            2 => 'Efectivo',
            3 => 'Cheque',
            4 => 'Cuenta Corriente',
            5 => 'Transferencia',
            6 => 'Tarjeta Debito'
        ];
        foreach ($medios as $id => $desc) {
            MedioPago::firstOrCreate(['id' => $id], [
                'descripcion' => $desc,
                'estado' => '1'
            ]);
        }

        // TipoPago
        TipoPago::firstOrCreate(['id' => 2], [
            'descripcion' => 'Total',
            'estado' => '1'
        ]);

        // Limpiar tablas para evitar duplicación masiva
        DB::table('pago_tarjetas')->truncate();
        DB::table('pago_transferencias')->truncate();
        DB::table('pago_ctactes')->truncate();
        DB::table('pagos_x_cajas')->truncate();
        DB::table('pagos')->truncate();
        DB::table('facturas')->truncate();
        DB::table('ordens')->truncate();
        DB::table('cajas')->truncate();

        $cajeroId = $cajero->id;
        $sucursalId = $sucursal->id;
        $clienteId = $cliente->id;
        $vehiculoId = $vehiculo->id;

        // Generar datos para los últimos 45 días
        $now = Carbon::now();
        
        // Vamos a crear cajas simuladas para varios días
        for ($i = 0; $i < 20; $i++) {
            // Cajas esparcidas en los últimos 45 días
            $date = (clone $now)->subDays($i * 2 + 1)->setHour(18)->setMinute(0);
            
            $montoInicial = rand(5000, 15000);
            
            // Crear caja
            $caja = Caja::create([
                'cajero_id' => $cajeroId,
                'sucursal_id' => $sucursalId,
                'monto_inicial' => $montoInicial,
                'estado' => '500', // cerrada
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            // Variables para acumular en esta caja
            $totalEfectivo = 0;
            $totalTarjeta = 0;
            $totalTransferencia = 0;
            $totalCtaCte = 0;
            $totalCheques = 0;
            $totalGastos = 0;
            $totalVenta = 0;

            // Generar entre 2 y 6 ventas por caja
            $numVentas = rand(2, 6);
            for ($v = 0; $v < $numVentas; $v++) {
                $orderDate = (clone $date)->subHours(rand(1, 8));
                
                // Lubricentro (motivo = '2') o Lavadero (motivo = '1')
                $motivo = (rand(0, 100) > 40) ? '2' : '1';
                $concepto = ($motivo == '2') ? 'Lubricentro' : 'Lavadero';
                
                // Importe de la venta
                $totalVentaItem = ($motivo == '2') ? rand(15000, 60000) : rand(3000, 10000);

                // Crear orden
                $orden = Orden::create([
                    'cliente_id' => $clienteId,
                    'vehiculo_id' => $vehiculoId,
                    'motivo' => $motivo,
                    'estado' => '100', // Terminado/Cobrado
                    'sucursal_id' => $sucursalId,
                    'fecha_turno' => $orderDate->toDateString(),
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);

                // Crear factura
                $factura = Factura::create([
                    'orden_id' => $orden->id,
                    'tipo_factura_id' => 1,
                    'total' => $totalVentaItem,
                    'estado' => 'cobrada',
                    'subtotal' => $totalVentaItem,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);

                // Medio de pago: 1=Tarjeta, 2=Efectivo, 4=Cta Cte, 5=Transferencia
                $r = rand(1, 100);
                if ($r <= 35) {
                    $medioPagoId = 2; // Efectivo
                } elseif ($r <= 70) {
                    $medioPagoId = 1; // Tarjeta
                } elseif ($r <= 85) {
                    $medioPagoId = 5; // Transferencia
                } else {
                    $medioPagoId = 4; // Cuenta Corriente
                }

                // Crear pago
                $pago = Pago::create([
                    'factura_id' => $factura->id,
                    'cliente_id' => $clienteId,
                    'tipo_pago_id' => 2, // Total
                    'medio_pago_id' => $medioPagoId,
                    'efectivo' => ($medioPagoId == 2) ? $totalVentaItem : 0,
                    'total' => $totalVentaItem,
                    'concepto' => $concepto,
                    'in_out' => 'in',
                    'estado' => 'cobrado',
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);

                // Vincular pago a caja
                PagosXCaja::create([
                    'pago_id' => $pago->id,
                    'caja_id' => $caja->id,
                    'estado' => '1',
                ]);

                // Guardar desglose específico
                if ($medioPagoId == 1) {
                    $planId = rand(1, 9); // Planes de Visa, Master o Amex
                    PagoTarjeta::create([
                        'plan_id' => $planId,
                        'cliente_id' => $clienteId,
                        'pago_id' => $pago->id,
                        'subtotal' => $totalVentaItem,
                        'total' => $totalVentaItem,
                        'estado' => '1',
                        'caja_id' => $caja->id,
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate,
                    ]);
                    $totalTarjeta += $totalVentaItem;
                } elseif ($medioPagoId == 5) {
                    PagoTransferencia::create([
                        'cliente_id' => $clienteId,
                        'pago_id' => $pago->id,
                        'caja_id' => $caja->id,
                        'subtotal' => $totalVentaItem,
                        'total' => $totalVentaItem,
                        'estado' => '1',
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate,
                    ]);
                    $totalTransferencia += $totalVentaItem;
                } elseif ($medioPagoId == 4) {
                    PagoCtacte::create([
                        'cliente_id' => $clienteId,
                        'pago_id' => $pago->id,
                        'subtotal' => $totalVentaItem,
                        'total' => $totalVentaItem,
                        'estado' => 'debe',
                        'vencimiento' => (clone $orderDate)->addDays(30)->toDateString(),
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate,
                    ]);
                    $totalCtaCte += $totalVentaItem;
                } elseif ($medioPagoId == 2) {
                    $totalEfectivo += $totalVentaItem;
                }

                $totalVenta += $totalVentaItem;
            }

            // Generar 1 gasto por caja
            if (rand(0, 100) > 30) {
                $gastoDate = (clone $date)->subHours(rand(1, 4));
                $gastoMonto = rand(2000, 10000);

                // Factura ficticia para proveedor
                $facturaProv = Factura::create([
                    'tipo_factura_id' => 1,
                    'total' => $gastoMonto,
                    'estado' => 'pagada',
                    'subtotal' => $gastoMonto,
                    'created_at' => $gastoDate,
                    'updated_at' => $gastoDate,
                ]);

                $gasto = Pago::create([
                    'factura_id' => $facturaProv->id,
                    'tipo_pago_id' => 2,
                    'medio_pago_id' => 2, // pagado en efectivo
                    'efectivo' => $gastoMonto,
                    'total' => $gastoMonto,
                    'concepto' => 'proveedor',
                    'in_out' => 'out', // egreso
                    'estado' => 'pagado',
                    'created_at' => $gastoDate,
                    'updated_at' => $gastoDate,
                ]);

                PagosXCaja::create([
                    'pago_id' => $gasto->id,
                    'caja_id' => $caja->id,
                    'estado' => '1',
                ]);

                $totalGastos += $gastoMonto;
            }

            // Actualizar totales de la caja
            $caja->update([
                'efectivo' => $totalEfectivo,
                'tarjetas' => $totalTarjeta,
                'transferencias' => $totalTransferencia,
                'cuenta_corriente' => $totalCtaCte,
                'cheques' => $totalCheques,
                'gastos' => $totalGastos,
                'venta' => $totalVenta,
                'rendicion' => ($montoInicial + $totalEfectivo - $totalGastos), // Efectivo final
            ]);
        }

        // Crear una caja abierta hoy para el cajero Hugo
        Caja::create([
            'cajero_id' => $cajeroId,
            'sucursal_id' => $sucursalId,
            'monto_inicial' => 10000,
            'estado' => '200', // abierta
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command?->info("Mock transactions seeded successfully!");
    }
}
