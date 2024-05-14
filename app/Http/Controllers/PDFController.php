<?php

namespace App\Http\Controllers;

use App\Models\Orden;
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







    
    public function presupuesto(string $id)
    {
        $orden = Presupuesto::find($id);

        if (!$orden) {
            abort(404); // Orden no encontrada
        }

        $items = $orden->itemspres;
        $total = $orden->itemspres->sum('subtotal');
        // dd($items);
        $fecha = $orden->horario;
        $encargado = $orden->clientes->perfiles->personas;
        $vendedor = Auth::user();

        $pdf = PDF::loadView('pdf.template', [
            'items' => $items,
            'fecha' => $fecha,
            'total' => $total,
            'encargado' => $encargado,
            'vendedor' => $vendedor
        ]);

        return $pdf->stream('presupuesto_' . $orden->id . '.pdf');
    }
}

