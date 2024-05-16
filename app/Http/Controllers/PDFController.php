<?php

namespace App\Http\Controllers;

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

        $items = $orden->items;
        $fecha = $orden->horario;
        $encargado = $orden->clientes->perfiles->personas;
        $vendedor = Auth::user();
        $total = $items->sum('subtotal');

        $pdf = PDF::loadView('pdf.template', [
            'items' => $items,
            'fecha' => $fecha,
            'encargado' => $encargado,
            'vendedor' => $vendedor,
            'total' => $total
        ]);

        return $pdf->stream('orden_' . $orden->id . '.pdf');
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

