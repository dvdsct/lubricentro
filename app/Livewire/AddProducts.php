<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\ItemsXOrden;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\Stock;
use Livewire\Attributes\On;
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
    // public $stock;

    // De la Orden
    public $producto;
    public $codigoBarras;
    public $servicio;
    public $item;
    public $total;

    // Item
    public $cantidad;
    public $precio;
    public $subtotal;
    private $descontar;
    private $descuento;

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
        $precio = $p->precio_venta;
        // dd($precio);


        if (
            $stock->cantidad >=
            $this->cantidad and  $this->cantidad != 0
        ) {
            $item->update([
                'cantidad' => $this->cantidad,
                'subtotal' => floatval($precio) *  $this->cantidad,
                'estado' => '2',

            ]);

            $stock->update([
                'cantidad' => $stock->cantidad - $this->cantidad
            ]);

            $this->reset('cantidad');
        } else {

            // dd('no hay stock');
            return  $this->dispatch('nonstock');
        }

        $this->dispatch('suma-items');
    }

    // Manejo del Modal

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
        $stock = Stock::where('producto_id', $this->producto->id)->first();

        if ($stock->cantidad == 0) {


            return  $this->dispatch('nonstock');
        } else {

            if ($this->producto->categoria_producto_id == '1') {
                $this->descontar = true;

                if ($this->producto->subcategoria_producto_id == '1') {


                    $this->modalProdOff();

                    $i = Item::create([
                        'producto_id' => $this->producto->id,
                        'precio' => $this->producto->porcentaje,
                        'cantidad' => '1',
                        'subtotal' => $this->producto->monto * (-1),
                        'estado' => '2',
                    ]);

                    ItemsXOrden::create([
                        'item_id' => $i->id,
                        'orden_id' => $this->orden->id,
                        'estado' => '1',

                    ]);

                    $stock->update([
                        'cantidad' => $stock->cantidad - 1
                    ]);
                } else {

                    $total = $this->orden->items->sum('subtotal');
                    $this->descuento = floatval($total / 100) * floatval($this->producto->porcentaje) * (-1);

                    $this->modalProdOff();

                    $i = Item::create([
                        'producto_id' => $this->producto->id,
                        'precio' => $this->producto->porcentaje,
                        'cantidad' => '1',
                        'subtotal' => $this->descuento,
                        'estado' => '2',
                    ]);

                    ItemsXOrden::create([
                        'item_id' => $i->id,
                        'orden_id' => $this->orden->id,
                        'estado' => '1',

                    ]);

                    $stock->update([
                        'cantidad' => $stock->cantidad - 1
                    ]);
                }
            } else {





                $this->modalProdOff();

                $i = Item::create([
                    'producto_id' => $this->producto->id,
                    'precio' => $this->producto->precio_venta,
                    'estado' => '1',
                ]);

                ItemsXOrden::create([
                    'item_id' => $i->id,
                    'orden_id' => $this->orden->id,
                    'estado' => '1',

                ]);
            }
        }


        // dd($this->producto);
    }


    #[On('delete')]
    public function delProd(string $id)
    {

        $item = Item::find($id);
        $stock = Stock::where('producto_id', $item->producto_id)->first();

        $cantidad = $stock->cantidad + $item->cantidad;
        $stock->update([
            'cantidad' => $cantidad
        ]);

        $item->delete();
    }



    public function codeBar()
    {


        if (strlen($this->codigoBarras) == 13) {
            $producto = Producto::where('codigo_de_barras', $this->codigoBarras)->get();

            $producto_id = $producto->first()->id;
            $precio_venta = $producto->first()->precio_venta;

            $i = Item::create([
                'producto_id' => $producto_id,
                'precio' => $precio_venta,
                'estado' => '1',
            ]);

            ItemsXOrden::create([
                'item_id' => $i->id,
                'orden_id' => $this->orden->id,
                'estado' => '1',

            ]);
        } else {
            $producto = Producto::find($this->codigoBarras);
            $stock = Stock::where('producto_id', $producto->id)->first();

            if ($stock->cantidad == 0) {


                return  $this->dispatch('nonstock');
            }

            $producto_id = $producto->first()->id;
            $precio_venta = $producto->first()->precio_venta;
            $i = Item::create([
                'producto_id' => $producto->id,
                'precio' => $producto->precio_venta,
                'estado' => '1',
            ]);

            ItemsXOrden::create([
                'item_id' => $i->id,
                'orden_id' => $this->orden->id,
                'estado' => '1',

            ]);
            $this->reset('codigoBarras');
        }
    }

    public function render()
    {
        $this->productos = Stock::all();
        $this->servicios = Servicio::all();


        $this->total = $this->orden->items->sum('subtotal') - $this->descuento;

        return view('livewire.add-products', [
            'stock' => Stock::select('stocks.*', 'productos.descripcion as descripcion', 'productos.codigo', 'productos.precio_venta')
                ->leftJoin('productos', 'stocks.producto_id', '=', 'productos.id')
                ->where('descripcion', 'like', '%' . $this->query . '%')
                ->orWhere('productos.codigo', 'like', '%' . $this->query . '%')
                ->paginate(10)
        ]);
    }
}
