<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\PedItem;
use App\Models\ItemXPedido;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\Stock;
use App\Models\PedidoProveedorItem;
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
    public $perPage = 25;

    public $showHistory = false;
    public $historyMovements = [];
    public $historyProductoDesc;

    // Recepción parcial por ítem (inputs por producto_id)
    public $receiveQty = [];
    // Cache de ítems del nuevo esquema, indexado por producto_id
    public $ppiByProduct = [];


    public function mount($pedido, $proveedor)
    {
        $this->pedido = $pedido;
        $this->proveedor = $proveedor;
        // Calcular total desde el nuevo esquema; fallback al legacy si aún no migró
        $totalNuevo = PedidoProveedorItem::where('pedido_proveedor_id', $this->pedido->id)->sum('subtotal');
        $this->total = $totalNuevo > 0 ? $totalNuevo : $this->pedido->items->sum('subtotal');

    }

    // Recepción parcial de un ítem del pedido (por producto)
    public function recibirItem($productoId)
    {
        if ($this->pedido->estado === 'cerrado') {
            session()->flash('error', 'El pedido está cerrado. No se pueden recibir más ítems.');
            return;
        }
        $cantidad = intval($this->receiveQty[$productoId] ?? 0);
        if ($cantidad <= 0) {
            return;
        }

        $ppi = PedidoProveedorItem::where('pedido_proveedor_id', $this->pedido->id)
            ->where('producto_id', $productoId)
            ->first();
        if (!$ppi) {
            return;
        }

        $pendiente = max(0, intval($ppi->cantidad_pedida) - intval($ppi->cantidad_recibida));
        if ($pendiente <= 0) {
            $this->receiveQty[$productoId] = null;
            return;
        }

        $toReceive = min($cantidad, $pendiente);
        if ($toReceive <= 0) {
            return;
        }

        $service = app(\App\Services\StockService::class);
        $sucursalId = 1;

        // Impactar stock y actualizar item
        $service->ensureStockRecord($sucursalId, $productoId);
        $service->adjustStock($sucursalId, $productoId, $toReceive, [
            'motivo' => 'Ingreso por compra',
            'referencia_type' => 'PedidoProveedor',
            'referencia_id' => $this->pedido->id,
            'user_id' => auth()->id(),
        ]);

        $nuevoRecibido = intval($ppi->cantidad_recibida) + $toReceive;
        $estadoItem = ($nuevoRecibido >= intval($ppi->cantidad_pedida)) ? 'recibido_total' : 'recibido_parcial';
        $ppi->update([
            'cantidad_recibida' => $nuevoRecibido,
            'estado_item' => $estadoItem,
        ]);

        // limpiar campo de input para ese producto
        $this->receiveQty[$productoId] = null;

        // refrescar mapping en memoria para la vista
        $this->refreshPpiMap();

        // Actualizar estado general del pedido según pendientes
        $this->updatePedidoEstado();

        // Feedback de UI
        session()->flash('success', 'Se recibieron ' . $toReceive . ' unidad(es) del producto.');
    }

    public function openHistory($productoId)
    {
        $p = \App\Models\Producto::find($productoId);
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

    public function search()
    {
        $this->resetPage();
    }



    // Recibir pedido

    #[On('pedido-recibido')]
    public function recibirPedido()
    {
        if ($this->pedido->estado === 'cerrado') {
            session()->flash('error', 'El pedido ya está cerrado.');
            return;
        }
        $sucursalId = 1;
        $service = app(\App\Services\StockService::class);

        foreach ($this->pedido->items as $i) {
            $p = Producto::find($i->producto_id);
            if (!$p) { continue; }

            // Recalcular precio de venta (mantengo lógica existente)
            $n_costo = $p->costo + (($p->costo/100) * 60);
            $p->update([
                'precio_venta' => $n_costo,
                'precio_presupuesto' => $n_costo,
            ]);

            // Asegurar fila de stock y ajustar de forma atómica
            $service->ensureStockRecord($sucursalId, $p->id);
            $service->adjustStock($sucursalId, $p->id, intval($i->cantidad), [
                'motivo' => 'Ingreso por compra',
                'referencia_type' => 'PedidoProveedor',
                'referencia_id' => $this->pedido->id,
                'user_id' => auth()->id(),
            ]);

            // Sincronizar nuevo esquema: marcar recibido_total para el item correspondiente
            $ppi = PedidoProveedorItem::where('pedido_proveedor_id', $this->pedido->id)
                ->where('producto_id', $p->id)
                ->first();
            if ($ppi) {
                $cantPedida = intval($ppi->cantidad_pedida ?? 0);
                $ppi->update([
                    'cantidad_recibida' => $cantPedida,
                    'estado_item' => 'recibido_total',
                ]);
            }
        }

        // Actualizar estado general del pedido
        $this->updatePedidoEstado();

        redirect('pedidos');
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

            // Sincronizar nuevo esquema de ítems del pedido
            $ppi = PedidoProveedorItem::firstOrCreate([
                'pedido_proveedor_id' => $this->pedido->id,
                'producto_id' => $p->id,
            ], [
                'cantidad_pedida' => 0,
                'cantidad_recibida' => 0,
                'costo_unitario' => 0,
                'subtotal' => 0,
                'estado_item' => 'pendiente',
            ]);

            $nuevoSubtotal = floatval($this->precio) * floatval($this->cantidad);
            $ppi->update([
                'cantidad_pedida' => intval($this->cantidad),
                'costo_unitario' => floatval($this->precio),
                'subtotal' => $nuevoSubtotal,
            ]);





            $this->reset('cantidad', 'precio');
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

        // Crear también el registro en el nuevo esquema (inicialmente sin cantidad)
        PedidoProveedorItem::firstOrCreate([
            'pedido_proveedor_id' => $this->pedido->id,
            'producto_id' => $this->producto->id,
        ], [
            'cantidad_pedida' => 0,
            'cantidad_recibida' => 0,
            'costo_unitario' => $this->producto->costo ?? 0,
            'subtotal' => 0,
            'estado_item' => 'pendiente',
        ]);
    }

    public function editProd($id)
    {
        $item = PedItem::find($id);
        $item->update([
            'estado' => '1'
        ]);
    }

    #[On('delete')]
    public function delProd(string $id)
    {
        $item = PedItem::find($id);
        if (!$item) { return; }

        // Encontrar el registro del nuevo esquema para este producto en este pedido
        $ppi = PedidoProveedorItem::where('pedido_proveedor_id', $this->pedido->id)
            ->where('producto_id', $item->producto_id)
            ->first();

        if ($ppi && intval($ppi->cantidad_recibida) > 0) {
            session()->flash('error', 'No se puede eliminar: el ítem ya tiene recepción registrada.');
            return;
        }

        // Eliminar nuevo esquema si existe (solo si no hubo recepción)
        if ($ppi) {
            $ppi->delete();
        }

        // Eliminar legacy
        $item->delete();

        session()->flash('success', 'Ítem eliminado del pedido.');
    }




    protected function updatePedidoEstado(): void
    {
        // Calcula estado en base a los ítems del nuevo esquema
        $ppis = PedidoProveedorItem::where('pedido_proveedor_id', $this->pedido->id)->get();
        if ($ppis->isEmpty()) {
            // sin ítems: mantener estado actual
            return;
        }

        $total = $ppis->count();
        $completos = $ppis->where('estado_item', 'recibido_total')->count();
        $recibidos = $ppis->whereIn('estado_item', ['recibido_total', 'recibido_parcial'])->count();

        $nuevoEstado = 'enviado';
        if ($completos === $total) {
            $nuevoEstado = 'recibido_total';
        } elseif ($recibidos > 0) {
            $nuevoEstado = 'recibido_parcial';
        }

        $this->pedido->update([
            'estado' => $nuevoEstado,
        ]);
    }

    public function closePedido(): void
    {
        if ($this->pedido->estado === 'cerrado') {
            session()->flash('error', 'El pedido ya está cerrado.');
            return;
        }
        // Cerrar solo si no hay pendientes
        $ppis = PedidoProveedorItem::where('pedido_proveedor_id', $this->pedido->id)->get();
        $pendientes = $ppis->filter(function ($r) {
            return intval($r->cantidad_pedida) > intval($r->cantidad_recibida);
        })->count();

        if ($pendientes > 0) {
            session()->flash('error', 'No se puede cerrar: aún quedan ítems pendientes por recibir.');
            return;
        }

        $this->pedido->update([
            'estado' => 'cerrado',
            'fecha_recepcion' => \Carbon\Carbon::now(),
            'usuario_receptor_id' => auth()->id(),
        ]);

        session()->flash('success', 'Pedido cerrado correctamente.');
    }

    protected function refreshPpiMap(): void
    {
        $ppis = PedidoProveedorItem::where('pedido_proveedor_id', $this->pedido->id)->get();
        $this->ppiByProduct = $ppis->keyBy('producto_id')->toArray();
    }

    public function render()
    {
        $this->total = $this->pedido->items->sum('subtotal');

        // refrescar mapping para usar en la vista
        $this->refreshPpiMap();

        // Construir la consulta de stock con todas las columnas necesarias en el GROUP BY
        $stockQuery = Stock::select([
                'stocks.id', 'stocks.cantidad', 'stocks.estado', 'stocks.sucursal_id', 
                'stocks.producto_id', 'stocks.unidad', 'stocks.created_at', 'stocks.updated_at',
                'productos.descripcion', 'productos.codigo', 'productos.costo', 'productos.precio_venta'
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
                'productos.descripcion', 'productos.codigo', 'productos.costo', 'productos.precio_venta'
            ])
            ->orderBy('productos.descripcion')
            ->paginate($this->perPage);

        return view('livewire.add-products-p-p', [
            'stock' => $stockQuery,
            'ppiByProduct' => \App\Models\PedidoProveedorItem::where('pedido_proveedor_id', $this->pedido->id)->get()->keyBy('producto_id'),
        ]);
    }
}
