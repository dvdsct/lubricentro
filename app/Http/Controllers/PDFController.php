<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Orden;
use App\Models\PedidoProveedor;
use App\Models\Presupuesto;
use App\Models\Stock;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;




class PDFController extends Controller
{
    /**
     * Genera una orden de trabajo en formato estándar
     */
    public function generatePdf(string $id)
    {
        // Aumentar el tiempo de ejecución y memoria
        set_time_limit(300); // 5 minutos
        ini_set('memory_limit', '256M');
        
        // Cargar la orden con relaciones necesarias usando eager loading
        $orden = Orden::with([
            'items.productos',
            'clientes.perfiles.personas',
            'vehiculos.modelos.marcas'
        ])->find($id);

        if (!$orden) {
            abort(404); // Orden no encontrada
        }

        if($orden->motivo == '1'){
            $sector = 'Lavadero';
        }else{
            $sector = 'Lubricentro';

        }
        $contador = 0;
        // Obtener datos optimizados
        $items = $orden->items;
        $horario = $orden->horario;
        $fecha = $orden->fecha_turno;
        
        // Manejo seguro de relaciones
        $vehiculo = '';
        if ($orden->vehiculos && $orden->vehiculos->modelos) {
            $marca = $orden->vehiculos->modelos->marcas->descripcion ?? '';
            $modelo = $orden->vehiculos->modelos->descripcion ?? '';
            $anio = $orden->vehiculos->año ?? '';
            $vehiculo = trim("$marca $modelo $anio");
        }

        // Obtener datos del encargado de forma segura
        $encargado = optional(optional($orden->clientes)->perfiles)->personas;
        $vendedor = Auth::user();
        $total = $items->sum('subtotal');

        // Cargar el logo como base64 para evitar problemas de carga
        $logoPath = public_path('img/logo.png');
        $logo = null;
        if (file_exists($logoPath)) {
            $logo = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Pasar solo los datos necesarios a la vista
        $data = [
            'items' => $items,
            'sector' => $sector,
            'vehiculo' => $vehiculo,
            'orden' => $orden,
            'fecha' => $fecha,
            'horario' => $horario,
            'encargado' => $encargado,
            'vendedor' => $vendedor,
            'total' => $total,
            'logo' => $logo // Pasar el logo como base64
        ];

        // Configurar opciones de DomPDF como array
        $options = [
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'Arial',
            'fontDir' => storage_path('fonts/'),
            'fontCache' => storage_path('fonts/'),
            'tempDir' => storage_path('app/temp/'),
            'chroot' => realpath(base_path()),
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true
        ];
        
        $pdf = PDF::setOptions($options)
                 ->loadView('pdf.template', $data);

        return $pdf->stream('orden_' . $orden->id . '.pdf');
    }

    /**
     * Genera una orden de trabajo limpia (formato para imprimir y rellenar a mano)
     */
    public function generateOrdenLimpia()
    {
        // Configuración básica
        set_time_limit(300);
        ini_set('memory_limit', '256M');
        
        // Obtener la fecha actual
        $fecha = now()->format('d/m/Y');
        
        // Cargar el logo como base64
        $logoPath = public_path('img/logo.png');
        $logo = null;
        if (file_exists($logoPath)) {
            $logo = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Configuración de opciones para DomPDF
        $options = [
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'Arial',
            'fontDir' => storage_path('fonts/'),
            'fontCache' => storage_path('fonts/'),
            'tempDir' => storage_path('app/temp/'),
            'chroot' => realpath(base_path()),
        ];
        
        // Generar el PDF
        $pdf = PDF::setOptions($options)
                 ->loadView('pdf.orden_limpia', [
                     'fecha' => $fecha,
                     'logo' => $logo
                 ]);

        return $pdf->stream('orden_trabajo_limpia_' . date('Y-m-d') . '.pdf');
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
        $orden = Presupuesto::with([
            'itemspres.productos',
            'clientes.perfiles.personas',
            'vehiculos.modelos.marcas'
        ])->find($id);

        if (!$orden) {
            abort(404); // Orden no encontrada
        }

        $items = $orden->itemspres;
        $total = $orden->itemspres->sum('subtotal');
        $fecha = $orden->created_at;
        $cliente = $orden->clientes->perfiles->personas->apellido .' '. $orden->clientes->perfiles->personas->nombre;
        $telefono = $orden->clientes->perfiles->personas->numero_telefono ?? '';
        $vendedor = Auth::user()->name;

        // Armar descripción de vehículo si existe
        $vehiculo = '';
        if ($orden->vehiculos) {
            $modelo = optional($orden->vehiculos->modelos)->descripcion;
            $marca = optional(optional($orden->vehiculos->modelos)->marcas)->descripcion;
            $anio = $orden->vehiculos->año ?? '';
            $dominio = $orden->vehiculos->dominio ?? '';
            $colorVal = $orden->vehiculos->color ?? '';
            $colorName = $colorVal;
            if ($colorVal) {
                if (is_numeric($colorVal)) {
                    $c = \App\Models\Colores::find($colorVal);
                    $colorName = $c->descripcion ?? $colorVal;
                } elseif (substr($colorVal, 0, 1) === '#') {
                    $c = \App\Models\Colores::where('hexadecimal', $colorVal)->first();
                    $colorName = $c->descripcion ?? $colorVal;
                }
            }
            $vehiculo = trim(($marca ? ($marca.' ') : '').($modelo ?? ''));
            if ($anio) { $vehiculo .= ' '.$anio; }
            if ($dominio) { $vehiculo .= ' - '.$dominio; }
            if ($colorName) { $vehiculo .= ' - '.$colorName; }
        }

        // Logo como base64 para evitar accesos remotos en DomPDF
        $logo = null;
        $logoPath = public_path('img/logo.png');
        if (is_file($logoPath)) {
            $logo = base64_encode(file_get_contents($logoPath));
        }

        $pdf = PDF::loadView('pdf.template_presupuesto', [
            'orden' => $orden,
            'items' => $items,
            'fecha' => $fecha,
            'total' => $total,
            'cliente' => $cliente,
            'telefono' => $telefono,
            'vendedor' => $vendedor,
            'vehiculo' => $vehiculo,
            'logo' => $logo
        ]);

        return $pdf->stream('presupuesto_' . $orden->id . '.pdf');
    }






    // PDF PLANILLA ACTUAL STOCK

    public function pdfStock(){
        $fecha = Carbon::now();
        $stockActual = Stock::all();



        $pdf = PDF::loadView('pdf.stock', [
            'stockActual' => $stockActual,
            'fecha' => $fecha
        ]);

        return $pdf->stream('stock'. $fecha);

    }
}

