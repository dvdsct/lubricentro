<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PDFController extends Controller
{
    public function generatePDF(string $id)
    {
        $orden = Orden::find($id);

        $items = $orden->items;
        $fecha = $orden->horario;
        $encargado = $orden->clientes->perfiles->personas;
        $vendedor = Auth::user();


        $data = ['name' => 'John Doe'];
        $pdf = PDF::loadView('pdf.template', [
            'items' => $items,
            'fecha' => $fecha,
            'encargado' => $encargado,
            'vendedor' => $vendedor
        ]);
        return $pdf->download('example.pdf');
    }
}
