<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Lubricentro.Stock.index');
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
        //
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
    public function update(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);
        
        // Verificar si el producto es provisional
        if ($stock->productos && $stock->productos->es_provisional) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede actualizar el stock de un producto provisional.'
            ], 403);
        }
        
        $request->validate([
            'cantidad' => 'required|numeric|min:0',
            'motivo' => 'nullable|string|max:255',
        ]);

        $newCantidad = floatval($request->input('cantidad'));
        $oldCantidad = floatval($stock->cantidad);
        $delta = $newCantidad - $oldCantidad;

        if ($delta !== 0.0) {
            $stockService = app(\App\Services\StockService::class);
            $stockService->adjustStock($stock->sucursal_id ?: 1, $stock->producto_id, $delta, [
                'motivo' => $request->input('motivo') ?: 'Actualización vía API/Controller',
                'operacion' => 'Ajuste manual',
                'referencia_type' => 'StockController',
                'referencia_id' => $stock->id,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Stock actualizado correctamente',
            'cantidad' => $stock->fresh()->cantidad
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
