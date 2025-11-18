<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\ItemsXOrden;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\Stock;
use App\Models\Factura;
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

    public $showHistory = false;
    public $historyMovements = [];
    public $historyProductoDesc;

    public function search()
    {
        $this->resetPage();
    }




    public function addCantidad($id)
    {
        $item = Item::find($id);
        $p = Producto::find($item->producto_id);
        $stock = Stock::where('producto_id', $p->id)->first();

        $precio = $p->precio_venta;

        // Validación básica
        if ($this->cantidad === null || $this->cantidad <= 0) {
            return $this->dispatch('nonstock');
        }

        // Calcular delta si el item ya tenía cantidad cargada
        $prevCantidad = intval($item->cantidad ?? 0);
        $newCantidad = intval($this->cantidad);
        $delta = $newCantidad - $prevCantidad; // puede ser +, 0, o negativo

        // Chequear stock solo cuando el delta requiere más unidades y el producto NO es provisional
        if ($delta > 0 && !$p->es_provisional && $stock->cantidad < $delta) {
            return $this->dispatch('nonstock');
        }

        // Actualizar de forma atómica
        \DB::transaction(function () use ($item, $precio, $newCantidad, $delta, $stock, $p) {
            $item->update([
                'cantidad' => $newCantidad,
                'subtotal' => floatval($precio) *  floatval($newCantidad),
                'estado' => '2',
            ]);

            // Actualizar stock solo si el producto NO es provisional
            if ($delta !== 0 && !$p->es_provisional) {
                $stock->update([
                    'cantidad' => $stock->cantidad - $delta,
                ]);
            }
        });

        $this->reset('cantidad');
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

        // Si no hay stock, permitir si el producto es provisional
        if ($stock->cantidad == 0 && !$this->producto->es_provisional) {
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

                    // Descontar stock solo si NO es provisional
                    if (!$this->producto->es_provisional) {
                        $stock->update([
                            'cantidad' => $stock->cantidad - 1
                        ]);
                    }
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

                    if (!$this->producto->es_provisional) {
                        $stock->update([
                            'cantidad' => $stock->cantidad - 1
                        ]);
                    }
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
        if (strlen((string)$this->codigoBarras) == 13) {
            $producto = Producto::where('codigo_de_barras', $this->codigoBarras)->first();
            if (!$producto) return $this->dispatch('nonstock');
            $stock = Stock::where('producto_id', $producto->id)->first();
            // Permitir si es provisional aunque no tenga stock
            if (!$stock || ($stock->cantidad <= 0 && !$producto->es_provisional)) return $this->dispatch('nonstock');

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
        } else {
            $producto = Producto::find($this->codigoBarras);
            if (!$producto) return $this->dispatch('nonstock');
            $stock = Stock::where('producto_id', $producto->id)->first();
            if (!$stock || ($stock->cantidad <= 0 && !$producto->es_provisional)) return  $this->dispatch('nonstock');

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

        // Mostrar total de factura si la orden está pagada
        if ($this->orden && $this->orden->estado == 100) {
            $factura = Factura::where('orden_id', $this->orden->id)->latest()->first();
            $this->total = $factura?->total ?? $this->orden->items->sum('subtotal');
        } else {
            $this->total = $this->orden->items->sum('subtotal') - $this->descuento;
        }

        return view('livewire.add-products', [
            'stock' => Stock::select([
                    'stocks.id', 'stocks.cantidad', 'stocks.estado', 'stocks.sucursal_id',
                    'stocks.producto_id', 'stocks.unidad', 'stocks.created_at', 'stocks.updated_at',
                    'productos.descripcion', 'productos.codigo', 'productos.precio_venta', 'productos.costo'
                ])
                ->leftJoin('productos', 'stocks.producto_id', '=', 'productos.id')
                ->where(function($q) {
                    $query = '%' . $this->query . '%';
                    $q->where('productos.descripcion', 'like', $query)
                      ->orWhere('productos.codigo', 'like', $query);
                })
                ->groupBy([
                    'stocks.id', 'stocks.cantidad', 'stocks.estado', 'stocks.sucursal_id',
                    'stocks.producto_id', 'stocks.unidad', 'stocks.created_at', 'stocks.updated_at',
                    'productos.descripcion', 'productos.codigo', 'productos.precio_venta', 'productos.costo'
                ])
                ->get()
        ]);
    }

    public function openHistory($productoId)
    {
        $p = Producto::find($productoId);
        $this->historyProductoDesc = $p?->descripcion;
        $this->historyMovements = \App\Models\StockMovement::where('producto_id', $productoId)
            ->orderByDesc('created_at')
            ->limit(100)
            ->get()
            ->toArray();
        $this->showHistory = true;
    }

    public function closeHistory()
    {
        $this->showHistory = false;
        $this->historyMovements = [];
        $this->historyProductoDesc = null;
    }
}
