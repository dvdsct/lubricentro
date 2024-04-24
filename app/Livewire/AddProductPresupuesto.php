<?php

namespace App\Livewire;

use App\Models\ItemsXPresupuesto;
use App\Models\PresupuestoItem;
use App\Models\Producto;
use App\Models\Stock;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AddProductPresupuesto extends Component
{

    public $presupuesto;
    public $cliente;
    public $modalProductos;

    #[Validate('required', message: 'Debe ingresar una cantidad')]
    public $cantidad;
    public $precio;
    public $producto;
    public $item;
    public $stock;


    public function mount($presupuesto)
    {
        $this->presupuesto = $presupuesto;

        $this->stock = Stock::all();
    }
    public $query = '';

    public function search()
    {
        $this->resetPage();
    }
    // Control del modal
    public function modalProdOn()
    {
        if ($this->modalProductos == true) {
            $this->modalProductos = false;
        } else {
            $this->modalProductos = true;
        }
    }
    // ____________________________________


    // Cargar item de pedido
    public function addCantidad($id)
    {


        $this->validate();
        $item = PresupuestoItem::find($id);
        $p = Producto::find($item->producto_id);

        // dd($stock);
        // $p->update([
        //     'costo' => $this->precio
        // ]);
        // $precio = $this->precio;

        if ($this->presupuesto->estado == '1') {

            $item->update([
                'cantidad' => $this->cantidad,
                'precio' => $p->costo,
                'subtotal' => $p->costo *  $this->cantidad,
                'estado' => '2',

            ]);





            $this->reset('cantidad', 'precio');
        }
    }
    // _________________________________________



    // Agrega un producto al presupuesto
    public function addedProduct($p)
    {
        $this->producto = Producto::find($p);
        $this->modalProdOn();

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

    public function render()
    {
        return view('livewire.add-product-presupuesto', [
            'stock' => Stock::select('stocks.*', 'productos.descripcion as descripcion', 'productos.codigo', 'productos.costo')
                ->leftJoin('productos', 'stocks.producto_id', '=', 'productos.id')
                ->where('descripcion', 'like', '%' . $this->query . '%')
                ->paginate(10)
        ]);
    }
}
