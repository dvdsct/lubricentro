<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\ItemsXOrden;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\Stock;
use Livewire\Component;
use Livewire\WithPagination;

class AddProducts extends Component
{

    use WithPagination;
    // Vista
    public $productos;
    public $servicios;
    public $items;
    public $orden;
    public $modal;
    public $stock;

    // De la Orden
    public $producto;
    public $servicio;
    public $item;

    // Item
    public $cantidad;
    public $precio;
    public $subtotal;


    public $query = '';

    public function search()
    {
        $this->resetPage();
    }


    public function addProduct($id)
    {
        $producto = Producto::find($id);

        Item::create([
            'producto_id' => $producto->id,

            'estado' => '1',
        ]);
    }

    public function modalProdOn()
    {

        $this->modal = true;
    }
    public function modalProdOff()
    {
        $this->modal = false;

        //
    }





    public function render()
    {
        $this->productos = Stock::all();
        $this->servicios = Servicio::all();
        $this->stock = Stock::select('stocks.*', 'productos.descripcion as descripcion', 'productos.codigo', 'productos.costo')
            ->leftJoin('productos', 'stocks.producto_id', '=', 'productos.id')
            ->where('descripcion', 'like', '%' . $this->query . '%')
            ->get();
        $this->items = ItemsXOrden::where('orden_id', $this->orden->id)->get();
        return view('livewire.add-products');
    }
}
