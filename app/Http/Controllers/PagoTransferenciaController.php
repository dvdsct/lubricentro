<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\PagoTransferencia;
use Illuminate\Http\Request;

class PagoTransferenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pagos = Caja::where('caja_id', $id)->get();

        return view(
            'Lubricentro.Ventas.Transferencias.index',
            [
                'pagos' => $pagos
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PagoTransferencia $pagoTransferencia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PagoTransferencia $pagoTransferencia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PagoTransferencia $pagoTransferencia)
    {
        //
    }
}
