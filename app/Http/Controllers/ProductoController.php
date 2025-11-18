<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Stock;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view(
            'Lubricentro.Producto.index'
        );
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
        $user = auth()->user();

        // Validar datos (permitimos 'stock' en request pero NO es columna de productos)
        $validated = $request->validate([
            'descripcion' => 'required|string|max:255',
            'codigo' => 'required|string|unique:productos,codigo',
            'precio_venta' => 'required|numeric|min:0',
            'costo' => 'required|numeric|min:0',
            'stock' => 'sometimes|numeric|min:0',
            'codigo_de_barras' => 'nullable|string|unique:productos,codigo_de_barras',
        ]);

        // Armar payload para productos sin 'stock'
        $productoData = [
            'descripcion' => $validated['descripcion'],
            'codigo' => $validated['codigo'],
            'precio_venta' => $validated['precio_venta'],
            'costo' => $validated['costo'],
            'codigo_de_barras' => $validated['codigo_de_barras'] ?? null,
        ];

        // Provisionalidad por rol
        $productoData['es_provisional'] = $user->hasRole('admin') ? false : true;

        // Crear producto
        $producto = Producto::create($productoData);

        // Crear/Ajustar stock en tabla 'stocks'
        $stockQty = $user->hasRole('admin') ? intval($request->input('stock', 0)) : 0;
        Stock::updateOrCreate(
            [
                'producto_id' => $producto->id,
                'sucursal_id' => 1,
            ],
            [
                'cantidad' => $stockQty,
                'estado' => '1',
            ]
        );

        return response()->json([
            'success' => true,
            'message' => $user->hasRole('admin') ? 'Producto creado correctamente' : 'Producto provisional creado. Será revisado por un administrador.',
            'producto' => $producto
        ]);
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
