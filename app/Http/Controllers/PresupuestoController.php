<?php

namespace App\Http\Controllers;

use App\Models\Presupuesto;
use Illuminate\Http\Request;

class PresupuestoController extends Controller
{
    public function __construct()
    {
        // Permitir acceso al rol admin o a quien tenga el permiso 'presupuestos'
        $this->middleware('role_or_permission:admin|presupuestos');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $presupuestos = Presupuesto::all();
        return view('Lubricentro.Presupuestos.index',[
            'presupuestos' => $presupuestos
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
    public function show(string $presupuesto)
    {
        $presupuesto = Presupuesto::find($presupuesto);
        // dd($presupuesto);
        $cliente = $presupuesto->clientes;

        return view('Lubricentro.Presupuestos.show',[
            'presupuesto' => $presupuesto,
            'cliente' => $cliente
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Presupuesto $presupuesto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Presupuesto $presupuesto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Presupuesto $presupuesto)
    {
        //
    }
}
