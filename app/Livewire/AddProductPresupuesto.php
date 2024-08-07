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

    #[Validate('required', message: 'Ingrese una cantidad')]
    public $cantidad;
    public $precio;
    public $producto;
    public $item;

    public $codigoBarras;
    public $sucrusal;
    public $precioPres;
    public $editPrecio = false;
    public $items;
    private $descontar;
    public $query = '';

    public function search()
    {
        $this->resetPage();
    }


    public function mount($presupuesto)
    {
        $this->presupuesto = $presupuesto;

        // $this->stock = Stock::all();
    }




    #[On('modal-presupuestos')]
    public function modalProdOn()
    {
        if ($this->modalProductos) {
            $this->modalProductos = false;
        } else {
            $this->modalProductos = true;
        }
    }
    // ____________________________________



    // public function editPrecioPres(){
    //     $this->editPrecio = true;

    // }

    // Cargar item de pedido
    public function addCantidad($id)
    {


        $this->validate();
        $item = PresupuestoItem::find($id);
        $p = Producto::find($item->producto_id);

        // dd($stock);

        // $precio = $this->precio;



        if ($this->presupuesto->estado == '1') {

            if ($this->editPrecio) {

                $p->update([
                    'precio_presupuesto' => $this->precioPres
                ]);

                $item->update([
                    'cantidad' => $this->cantidad,
                    'precio_presupuesto' => $this->precioPres,
                    'subtotal' => floatval($this->precioPres) *  floatval($this->cantidad),
                    'estado' => '2',

                ]);
            } else {

                $p->update([
                    'precio_presupuesto' => $p->precio_venta
                ]);

                $item->update([
                    'cantidad' => $this->cantidad,
                    'precio_venta' => $p->precio_venta,
                    'subtotal' => floatval($p->precio_venta) *  floatval($this->cantidad),
                    'estado' => '2',

                ]);
            }
        }
        $this->reset('cantidad', 'precio', 'editPrecio');
        $this->modalProductos = false;
        $this->dispatch('suma-items');
    }
    // _________________________________________

    public function codeBar()
    {


        if (strlen($this->codigoBarras) == 13) {
            $producto = Producto::where('codigo_de_barras', $this->codigoBarras)->get();

            $producto_id = $producto->first()->id;
            $precio_venta = $producto->first()->precio_venta;

            $i = PresupuestoItem::create([
                'producto_id' => $producto_id,
                'precio' => $precio_venta,
                'estado' => '1',
            ]);

            ItemsXPresupuesto::create([
                'presupuesto_id' => $this->presupuesto->id,
                'presupuesto_item_id' => $i->id,
                'estado' => '1',

            ]);
        } else {

            $producto = Producto::find($this->codigoBarras);

            $producto_id = $producto->id;
            $precio_venta = $producto->precio_venta;

            $i = PresupuestoItem::create([
                'producto_id' => $producto_id,
                'precio' => $precio_venta,
                'estado' => '1',
            ]);

            ItemsXPresupuesto::create([
                'presupuesto_id' => $this->presupuesto->id,
                'presupuesto_item_id' => $i->id,
                'estado' => '1',

            ]);
        }
    }

    // Agrega un producto al presupuesto
    public function addedProduct($p)
    {
        $this->producto = Producto::find($p);

        if ($this->producto->categoria_producto_id == '1') {
            $this->descontar = true;

            if ($this->producto->subcategoria_producto_id == '1') {





                $i = PresupuestoItem::create([
                    'producto_id' => $this->producto->id,
                    'precio' => $this->producto->porcentaje,
                    'cantidad' => '1',
                    'subtotal' => $this->producto->monto * (-1),
                    'estado' => '2',
                ]);

                ItemsXPresupuesto::create([
                    'presupuesto_item_id' => $i->id,
                    'presupuesto_id' => $this->presupuesto->id,
                    'estado' => '1',

                ]);
            } else {

                $total = $this->presupuesto->itemspres->sum('subtotal');
                $descuento = floatval($total / 100) * floatval($this->producto->porcentaje) * (-1);

                // $this->modalProdOff();

                $i = PresupuestoItem::create([
                    'producto_id' => $this->producto->id,
                    'precio' => $this->producto->porcentaje,
                    'cantidad' => '1',
                    'subtotal' => $descuento,
                    'estado' => '2',
                ]);

                ItemsXPresupuesto::create([
                    'presupuesto_item_id' => $i->id,
                    'presupuesto_id' => $this->presupuesto->id,
                    'estado' => '1',

                ]);
            }
        } else {

            $i = PresupuestoItem::create([
                'producto_id' => $this->producto->id,
                'precio' => $this->producto->precio_venta,
                'estado' => '1',
            ]);

            ItemsXPresupuesto::create([
                'presupuesto_id' => $this->presupuesto->id,
                'presupuesto_item_id' => $i->id,
                'estado' => '1',

            ]);
        }

        if($this->descontar){
            
        }


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
        $this->reset('editPrecio');
    }
    // _________________________________________________________




    public function render()
    {


        $this->items = $this->presupuesto->itemspres;
        return view('livewire.add-product-presupuesto', [
            'stock' => Stock::select('stocks.*', 'productos.descripcion', 'productos.codigo', 'productos.precio_venta')
                ->leftJoin('productos', 'stocks.producto_id', '=', 'productos.id')
                ->where('productos.descripcion', 'like', '%' . $this->query . '%')
                ->orWhere('productos.codigo', 'like', '%' . $this->query . '%')
                ->paginate(10)
        ]);
    }
}
