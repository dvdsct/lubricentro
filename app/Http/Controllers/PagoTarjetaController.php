<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\PagoTarjeta;
use Illuminate\Http\Request;

class PagoTarjetaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pagos= PagoTarjeta::all();

        return view('Lubricentro.Ventas.Tarjetas.index',[
            'pagos' => $pagos
        ]);
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
        $caja = Caja::find($id);
        $pagos = PagoTarjeta::where('caja_id', $id)->get();

        return view('Lubricentro.Ventas.Tarjetas.show',[
            'pagos' => $pagos,
            'caja' => $caja
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PagoTarjeta $pagoTarjeta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PagoTarjeta $pagoTarjeta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PagoTarjeta $pagoTarjeta)
    {
        //
    }
}
