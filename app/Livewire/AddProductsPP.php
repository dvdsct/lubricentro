<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\PedItem;
use App\Models\ItemXPedido;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\Stock;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class AddProductsPP extends Component
{

    use WithPagination;
    // Vista
    public $productos;
    public $servicios;
    public $items;
    public $pedido;
    public $modal;
    public $proveedor;
    // public $stock;

    // De la pedido
    public $producto;
    public $servicio;
    public $item;
    public $total;

    // Item
    #[Validate('required', message: 'ingrese una cantidad')]
    public $cantidad;
    #[Validate('required', message: 'ingrese el precio de costo')]
    public $precio;
    public $subtotal;


    public $query = '';


    public function mount($pedido, $proveedor)
    {
        $this->pedido = $pedido;
        $this->proveedor = $proveedor;
    }

    public function search()
    {
        $this->resetPage();
    }



    // Recibir pedido
    public function recibirPedido()
    {
        foreach ($this->pedido->items as $i) {

            $p = Producto::find($i->producto_id);
            $stock = Stock::where('producto_id', $p->id)->first();
            $stock->update([
                'cantidad' => $stock->cantidad + $i->cantidad
            ]);
        }

        redirect('stock');
    }



    // Cargar item de pedido
    public function addCantidad($id)
    {


        $this->validate();
        $item = PedItem::find($id);
        $p = Producto::find($item->producto_id);

        // dd($stock);
        $p->update([
            'costo' => $this->precio
        ]);
        // $precio = $this->precio;

        if ($this->pedido->estado == '2') {

            $item->update([
                'cantidad' => $this->cantidad,
                'precio' => $this->precio,
                'subtotal' => $this->precio *  $this->cantidad,
                'estado' => '2',

            ]);





            $this->reset('cantidad','precio');
        }
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


        $this->modalProdOff();




        $i = PedItem::create([
            'producto_id' => $this->producto->id,
            'precio' => $this->producto->costo,
            'estado' => '1',
        ]);

        ItemXPedido::create([
            'pedido_proveedor_id' => $this->pedido->id,
            'ped_item_id' => $i->id,
            'estado' => '1',

        ]);
    }

    public function editProd($id){
        $item = PedItem::find($id);
        $item->update([
            'estado' =>'1'
        ]);
    }

    #[On('delete')]
    public function delProd(string $id)
    {

        $item = PedItem::find($id);
        $item->delete();
    }




    public function render()
    {
        $this->productos = Stock::all();
        $this->servicios = Servicio::all();


        // $this->total = $this->pedido->items->sum('subtotal');

        return view('livewire.add-products-p-p', [
            'stock' => Stock::select('stocks.*', 'productos.descripcion as descripcion', 'productos.codigo', 'productos.costo')
            ->leftJoin('productos', 'stocks.producto_id', '=', 'productos.id')
            ->where('descripcion', 'like', '%' . $this->query . '%')
            ->orWhere('productos.codigo', 'like', '%' . $this->query . '%')
            ->paginate(10)
        ]);
    }
}
