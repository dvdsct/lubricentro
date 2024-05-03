<?php

namespace App\Livewire;

use App\Models\ItemsXPresupuesto;
use App\Models\PresupuestoItem;
use App\Models\Producto;
use App\Models\Stock;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class AddProductPresupuesto extends Component
{
    use WithPagination;


    public $presupuesto;
    public $cliente;
    public $modalProductos;

    #[Validate('required', message: 'Debe ingresar una cantidad')]
    public $cantidad;

    public $precio;
    public $producto;
    public $item;
    // public $stock;
    public $total;
    public $query = '';




    public function search()
    {
        $this->resetPage();
    }


    // Control del modal

    #[On('modal-presupuestos')]
    public function modalProdOn()
    {
        if ($this->modalProductos) {
            // dd('false');
            $this->modalProductos = false;
        } else {
            $this->modalProductos = true;
        }
    }

    //     public function modalProdOff()
    // {
    //     $this->modalProductos = false;
    // }
    // ____________________________________


    // Cargar item de pedido
    public function addCantidad($id)
    {
        $item = PresupuestoItem::find($id);
        $p = Producto::find($item->producto_id);
        // $stock = Stock::where('producto_id', $p->id)->first();

        // dd($stock);
        $precio = $p->costo;
        $c = $precio *  $this->cantidad;



        $item->update([
            'cantidad' => $this->cantidad,
            'subtotal' => $c,
            'estado' => '2',

        ]);



        $this->reset('cantidad');
    }
    // _________________________________________



    // Agrega un producto al presupuesto
    public function addedProduct($p)
    {
        $this->producto = Producto::find($p);

        $i = PresupuestoItem::create([
            'producto_id' => $this->producto->id,
            'precio' => $this->producto->costo,
            'estado' => '1',
        ]);

        ItemsXPresupuesto::create([
            'presupuesto_id' => $this->presupuesto->id,
            'presupuesto_item_id' => $i->id,
            'estado' => '1',

        ]);
        $this->dispatch('itempre-added');
        $this->modalProdOn();
    }
    // ______________________________________________________



    // Edita cantidad en el Item, requiere el ID del Item a editar
    public function editProd($id)
    {
        $item = PresupuestoItem::find($id);
        $item->update([
            'estado' => '1'
        ]);
    }
    // -______________________________________________________


    // Elimina el producto, require el ID del producto a eliminar
    #[On('delete')]
    public function delProd(string $id)
    {

        $item = PresupuestoItem::find($id);
        $item->delete();
    }
    // _________________________________________________________

    #[On('itempre-added')]
    public function render()
    {


        return view('livewire.add-product-presupuesto', [
            'stock' => Stock::select('stocks.*', 'productos.descripcion', 'productos.codigo', 'productos.costo')
                ->leftJoin('productos', 'stocks.producto_id', '=', 'productos.id')
                ->where('productos.descripcion', 'like', '%' . $this->query . '%')
                ->orWhere('productos.codigo', 'like', '%' . $this->query . '%')
                ->paginate(10)
        ]);
    }
}
