<?php

namespace App\Http\Controllers;

use App\Models\Orden;
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

        $pdf = PDF::loadView('pdf.template', [
            'items' => $items,
            'fecha' => $fecha,
            'encargado' => $encargado,
            'vendedor' => $vendedor
        ]);

        return $pdf->stream('orden_' . $orden->id . '.pdf');
    }
}

