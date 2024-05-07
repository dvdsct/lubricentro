<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

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
        $orden = Orden::find($id);

        if (!$orden) {
            abort(404); // Orden no encontrada
        }

        $items = $orden->items;
        $fecha = $orden->horario;
        $encargado = $orden->clientes->perfiles->personas;
        $vendedor = Auth::user();
        return view('Lubricentro.dashboard', [
            'items' => $items,
            'fecha' => $fecha,
            'encargado' => $encargado,
            'vendedor' => $vendedor
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
