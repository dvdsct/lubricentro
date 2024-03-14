<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\View;

class PDFController extends Controller
{

    

    public function generatePdf(string $id)
{
    $orden = Orden::find($id);

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
    return $pdf->stream('archivo.pdf');

}
}
