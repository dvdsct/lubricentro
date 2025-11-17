<?php

namespace App\Http\Controllers;

use App\Models\Producto;
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
        
        // Validar los datos
        $data = $request->validate([
            'descripcion' => 'required|string|max:255',
            'codigo' => 'required|string|unique:productos,codigo',
            'precio_venta' => 'required|numeric|min:0',
            'costo' => 'required|numeric|min:0',
            'stock' => 'sometimes|numeric|min:0',
            'codigo_de_barras' => 'nullable|string|unique:productos,codigo_de_barras',
        ]);

        // Si el usuario no es administrador, marcar como producto provisional
        if (!$user->hasRole('admin')) {
            $data['es_provisional'] = true;
            $data['stock'] = 0; // No permitir stock inicial para productos provisionales
        }

        $producto = Producto::create($data);

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
