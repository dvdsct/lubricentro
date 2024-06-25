<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Orden;
use App\Models\PedidoProveedor;
use App\Models\Presupuesto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;




class PDFController extends Controller
{
    public function generatePdf(string $id)
    {
        $orden = Orden::find($id);

        if (!$orden) {
            abort(404); // Orden no encontrada
        }

        if($orden->motivo == '1'){
            $sector = 'Lavadero';
        }else{
            $sector = 'Lubricentro';

        }
        $contador = 0;
        $items = $orden->items;
        $fecha = $orden->horario;
        $vehiculo = $orden->vehiculos->modelos->descripcion . ' ' . $orden->vehiculos->descripcion . ' ' . $orden->vehiculos->año;

        $encargado = $orden->clientes->perfiles->personas;
        $vendedor = Auth::user();
        $total = $items->sum('subtotal');

        $pdf = PDF::loadView('pdf.template', [
            'items' => $items,
            'sector' => $sector,
            'vehiculo' => $vehiculo,
            'orden' => $orden,
            'fecha' => $fecha,
            'encargado' => $encargado,
            'vendedor' => $vendedor,
            'total' => $total
        ]);

        return $pdf->stream('orden_' . $orden->id . '.pdf');
    }


    // Caja
    public function cierreCaja(string $id){

        $caja = Caja::find($id);
        $fechaApertura = $caja->created_at;
        $fechaCierre = $caja->updated_at;
        $turno = $caja->turno;
        $montoInicial = $caja->monto_inicial;
        $rendicion = $caja->rendicion;

        // Efectivo
        $pagosEfectivo = $caja->pagos->filter(function ($pago) {
            return $pago->medio_pago_id == 2 && $pago->in_out != 'out';
        })->sum('total');
        // Transferencias
        $pagosTrans = $caja->pagos->filter(function ($pago) {
            return $pago->medio_pago_id == 5 && $pago->in_out != 'out';
        })->sum('total');
        // Tarjetas
        $pagosTarjeta = $caja->pagos->filter(function ($pago) {
            return $pago->medio_pago_id == 1 && $pago->in_out != 'out';
        })->sum('total');
        // Cuenta Corriente
        $pagosCtaCte = $caja->pagos->filter(function ($pago) {
            return $pago->medio_pago_id == 4 && $pago->in_out != 'out';
        })->sum('total');
        // Cuenta Cheques
        $pagosCheques = $caja->pagos->filter(function ($pago) {
            return $pago->medio_pago_id == 3 && $pago->in_out != 'out';
        })->sum('total');

        // Gatos
        $gastosEfectivo = $caja->pagos->filter(function ($pago) {
            return $pago->estado == 200;
        })->sum('total');


        $gastos = $caja->pagos->filter(function ($pago) {
            return $pago->in_out != 'in';
        })->sum('total') * (-1);
        $ingresos = $caja->pagos->filter(function ($pago) {
            return $pago->in_out != 'out';
        })->sum('total') + $rendicion;




        // Balance
        $totalv = $caja->pagos->sum('total') + $montoInicial;
        

        $totalEfectivo = $totalv - $pagosCheques - $pagosCtaCte - $pagosTarjeta - $pagosTrans ;

        
        $cajero = $caja->cajeros->perfiles->personas->nombre;


        $pdf = PDF::loadView('pdf.pdf-cierre-caja', [
            'turno' => $turno,
            'pagosEfectivo' => $pagosEfectivo,
            'gastosEfectivo' => $gastosEfectivo,
            'pagosTrans' => $pagosTrans,
            'pagosTarjeta' => $pagosTarjeta,
            'pagosCtaCte' => $pagosCtaCte,
            'pagosCheques' => $pagosCheques,
            'totalv' => $totalv,
            'cajero' => $cajero,
            'fechaCierre' => $fechaCierre,
            'montoInicial' => $montoInicial,
            'gastos' => $gastos,
            'ingresos' => $ingresos,
            'totalEfectivo' => $totalEfectivo,
            'rendicion' => $rendicion,
            'fechaApertura' => $fechaApertura
           
        ]);

        return $pdf->stream('caja_' . $caja->id . '.pdf');


    }














    // PEDIDO
    public function generatePedido(string $id)
    {
        $orden = PedidoProveedor::find($id);

        if (!$orden) {
            abort(404); // Pedido no encontrada
        }

        $items = $orden->items;
        $total = $orden->items->sum('subtotal');
        $fecha = $orden->created_at;
        $encargado = $orden->proveedores->perfiles->personas;
        $vendedor = Auth::user();
        $categoria = $orden->tipos->descripcion;

        $pdf = PDF::loadView('pdf.template_prov', [
            'orden' => $orden,
            'items' => $items,
            'fecha' => $fecha,
            'categoria' => $categoria,
            'total' => $total,
            'encargado' => $encargado,
            'vendedor' => $vendedor
        ]);

        return $pdf->stream('pedido_' . $orden->id . '.pdf');
    }







    public function presupuesto(string $id)
    {
        $orden = Presupuesto::find($id);

        if (!$orden) {
            abort(404); // Orden no encontrada
        }

        $items = $orden->itemspres;
        $total = $orden->itemspres->sum('subtotal');
        // dd($items);
        $fecha = $orden->created_at;
        $cliente = $orden->clientes->perfiles->personas->apellido .' '. $orden->clientes->perfiles->personas->nombre;
        $vendedor = Auth::user()->name;

        $pdf = PDF::loadView('pdf.template_presupuesto', [
            'orden' => $orden,
            'items' => $items,
            'fecha' => $fecha,
            'total' => $total,
            'cliente' => $cliente,
            'vendedor' => $vendedor
        ]);

        return $pdf->stream('presupuesto_' . $orden->id . '.pdf');
    }
}

