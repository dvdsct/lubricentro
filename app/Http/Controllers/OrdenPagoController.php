<?php

namespace App\Http\Controllers;

use App\Models\OrdenPago;
use Illuminate\Http\Request;

class OrdenPagoController extends Controller
{
    public function index()
    {
        return view('Lubricentro.OrdenesPago.index');
    }

    public function create(Request $request)
    {
        return view('Lubricentro.OrdenesPago.create', [
            'proveedor_id' => $request->query('proveedor_id'),
            'factura_id' => $request->query('factura_id'),
        ]);
    }

    public function show($id)
    {
        $ordenPago = OrdenPago::findOrFail($id);
        return view('Lubricentro.OrdenesPago.show', [
            'ordenPago' => $ordenPago
        ]);
    }
}
