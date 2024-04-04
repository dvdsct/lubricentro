<?php

namespace App\Http\Controllers;

use App\Models\PedidoProveedor;
use Illuminate\Http\Request;

class PedidoProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        {
            return view('Lubricentro.PedidosProveedores.index');
        }
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
    public function show($id)
    {
        // dd($id);
        $pedido = PedidoProveedor::find($id);
        $proveedor = $pedido->proveedores;
        return view('Lubricentro.PedidosProveedores.show',[
            'pedido' => $pedido,
            'proveedor' => $proveedor
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {   
        $pedido = PedidoProveedor::find($id);
        $proveedor = $pedido->proveedores;

        return view('Lubricentro.PedidosProveedores.edit',[
            'pedido' => $pedido,
            'proveedor' => $proveedor

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PedidoProveedor $pedidoProveedor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PedidoProveedor $pedidoProveedor)
    {
        //
    }
}
