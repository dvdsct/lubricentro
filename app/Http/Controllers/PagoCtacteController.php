<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\PagoCtacte;
use Illuminate\Http\Request;

class PagoCtacteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::whereHas('pagosctas')
        // ->distinct()
        // ->select('id', 'nombre', 'perfil_id') // Agrega aquí los campos que necesitas
        ->get();

        return view('Lubricentro.Ventas.CtaCte.index',[
            'clientes' => $clientes
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
        $cliente = Cliente::find($id);
        $pagos = PagoCtacte::where('cliente_id',$cliente->id)->get();
        


        return view('Lubricentro.Ventas.CtaCte.show',[
            'cliente' => $cliente,
            'pagos' => $pagos
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PagoCtacte $pagoCtacte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PagoCtacte $pagoCtacte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PagoCtacte $pagoCtacte)
    {
        //
    }
}
