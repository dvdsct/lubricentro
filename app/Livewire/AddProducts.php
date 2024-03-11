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
    public $total;

    // Item
    public $cantidad;
    public $precio;
    public $subtotal;


    public $query = '';

    public function search()
    {
        $this->resetPage();
    }


    public function addCantidad($id)
    {
        $item = Item::find($id);
        $p = Producto::find($item->producto_id);
        $stock = Stock::where('producto_id', $p->id)->first();

        // dd($stock);
        $precio = $p->costo;


        if (
            $stock->cantidad >
            $this->cantidad and  $this->cantidad != 0
        ) {
            $item->update([
                'cantidad' => $this->cantidad,
                'subtotal' => $precio *  $this->cantidad,
                'estado' => '2',

            ]);

            $stock->update([
                'cantidad' => $stock->cantidad - $this->cantidad
            ]);

            $this->reset('cantidad');
        } else {

            dd('no hay stock');
        }
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

    public function addedProduct($p)
    {

        $this->producto = Producto::find($p);

        $i = Item::create([
            'producto_id' => $this->producto->id,
            'precio' => $this->producto->costo,
            'estado' => '1',
        ]);

        ItemsXOrden::create([
            'item_id' => $i->id,
            'orden_id' => $this->orden->id,
            'estado' => '1',

        ]);

        $this->modalProdOff();

        // dd($this->producto);
    }





    public function render()
    {
        $this->productos = Stock::all();
        $this->servicios = Servicio::all();
        $this->stock = Stock::select('stocks.*', 'productos.descripcion as descripcion', 'productos.codigo', 'productos.costo')
            ->leftJoin('productos', 'stocks.producto_id', '=', 'productos.id')
            ->where('descripcion', 'like', '%' . $this->query . '%')
            ->get();
        $this->total = $this->orden->items->sum('subtotal');
        return view('livewire.add-products');
    }
}
