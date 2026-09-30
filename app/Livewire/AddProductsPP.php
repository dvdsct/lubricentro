<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\PedItem;
use App\Models\ItemXPedido;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\Stock;
use App\Models\PedidoProveedor;
use App\Models\PedidoProveedorItem;
use App\Models\Factura;
use App\Models\TipoFactura;
use App\Models\NotaCredito;
use App\Services\ProveedorCtaCteService;
use App\Services\StockService;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AddProductsPP extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';

    // Modelos principales
    public $pedido;
    public $proveedor;

    // Modales y control de UI
    public $modal = false;
    public $modalAutorizacion = false;
    public $modalRecepcion = false;
    public $modalRechazo = false;

    // Item individual al cargar
    public $producto;
    public $cantidad;
    public $precio;
    public $subtotal;
    public $total;

    public $query = '';
    public $perPage = 25;

    // Inputs de recepción por ítem
    public $receiveQty = [];
    public $ppiByProduct = [];

    // FASE 1: Autorización
    public $authTipo = 'digital'; // 'digital', 'fisica'
    public $authNotas = '';

    // FASE 1: Solicitud
    public $solicitudMedio = 'whatsapp'; // 'whatsapp', 'telefono', 'email'

    // FASE 2: Control de Entrega / Recepción
    public $numeroFactura = '';
    public $tipoFacturaId = 1;
    public $fechaFactura = '';
    public $totalFactura = '';
    public $escenarioModal = 'escenario_1'; // 'escenario_1', 'escenario_2', 'escenario_3'
    public $motivoRechazoText = '';
    public $tiposFactura = [];

    public function mount($pedido, $proveedor)
    {
        $this->pedido = $pedido;
        $this->proveedor = $proveedor;
        $this->fechaFactura = Carbon::today()->format('Y-m-d');
        $this->tiposFactura = TipoFactura::where('descripcion', '!=', 'Consumidor final')->get();
        $this->tipoFacturaId = $this->tiposFactura->first()?->id ?? 1;

        $this->refreshPpiMap();
        $this->recalcularTotal();
        $this->prepararCantidadesRecepcion();
    }

    public function recalcularTotal(): void
    {
        $totalNuevo = PedidoProveedorItem::where('pedido_proveedor_id', $this->pedido->id)->sum('subtotal');
        $this->total = $totalNuevo > 0 ? $totalNuevo : floatval($this->pedido->items->sum('subtotal'));
        $this->pedido->update(['total_estimado' => $this->total]);
    }

    public function prepararCantidadesRecepcion(): void
    {
        $ppis = PedidoProveedorItem::where('pedido_proveedor_id', $this->pedido->id)->get();
        foreach ($ppis as $ppi) {
            $pendiente = max(0, intval($ppi->cantidad_pedida) - intval($ppi->cantidad_recibida));
            $this->receiveQty[$ppi->producto_id] = $pendiente;
        }
        if (empty($this->totalFactura) || floatval($this->totalFactura) == 0) {
            $this->totalFactura = $this->total;
        }
    }

    // ==========================================
    // FASE 1: AUTORIZACIÓN Y SOLICITUD AL PROVEEDOR
    // ==========================================

    public function openModalAutorizacion(): void
    {
        $this->modalAutorizacion = true;
    }

    public function closeModalAutorizacion(): void
    {
        $this->modalAutorizacion = false;
    }

    public function autorizarOC(): void
    {
        $this->pedido->update([
            'estado' => 'autorizada',
            'autorizado_por' => Auth::id(),
            'fecha_autorizacion' => Carbon::now(),
            'autorizacion_tipo' => $this->authTipo,
            'autorizacion_notas' => $this->authNotas,
        ]);

        $this->closeModalAutorizacion();
        session()->flash('success', 'Orden de Compra autorizada correctamente.');
    }

    public function solicitarProveedor(): void
    {
        $this->pedido->update([
            'estado' => 'solicitada',
            'fecha_solicitud' => Carbon::now(),
            'solicitado_medio' => $this->solicitudMedio,
        ]);

        session()->flash('success', 'Orden de Compra marcada como solicitada al proveedor vía ' . strtoupper($this->solicitudMedio) . '.');
    }

    // ==========================================
    // FASE 2: CONTROL DE ENTREGA Y RECEPCIÓN
    // ==========================================

    public function openModalRecepcion(): void
    {
        $this->prepararCantidadesRecepcion();
        $this->actualizarEscenarioDetectado();
        $this->modalRecepcion = true;
    }

    public function closeModalRecepcion(): void
    {
        $this->modalRecepcion = false;
    }

    public function updatedReceiveQty(): void
    {
        $this->actualizarEscenarioDetectado();
    }

    public function updatedTotalFactura(): void
    {
        $this->actualizarEscenarioDetectado();
    }

    public function actualizarEscenarioDetectado(): void
    {
        $montoRecibidoCalculado = 0;
        $esCompleto = true;

        $ppis = PedidoProveedorItem::where('pedido_proveedor_id', $this->pedido->id)->get();
        foreach ($ppis as $ppi) {
            $cantRec = intval($this->receiveQty[$ppi->producto_id] ?? 0);
            $cantPed = intval($ppi->cantidad_pedida);
            $montoRecibidoCalculado += ($cantRec * floatval($ppi->costo_unitario));

            if ($cantRec < $cantPed) {
                $esCompleto = false;
            }
        }

        $facturaTotal = floatval($this->totalFactura > 0 ? $this->totalFactura : $this->total);

        if ($esCompleto && abs($facturaTotal - $this->total) < 0.01) {
            $this->escenarioModal = 'escenario_1';
        } elseif (!$esCompleto && abs($facturaTotal - $this->total) < 0.01) {
            $this->escenarioModal = 'escenario_2';
        } else {
            $this->escenarioModal = 'escenario_3';
        }
    }

    public function procesarRecepcion(): void
    {
        $this->validate([
            'numeroFactura' => 'required|string|min:3',
            'tipoFacturaId' => 'required',
            'fechaFactura' => 'required|date',
            'totalFactura' => 'required|numeric|min:0.01',
        ], [
            'numeroFactura.required' => 'Ingrese el número de la factura del proveedor.',
            'tipoFacturaId.required' => 'Seleccione el tipo de factura.',
            'fechaFactura.required' => 'Indique la fecha de la factura.',
            'totalFactura.required' => 'Ingrese el importe total facturado.',
        ]);

        $stockService = app(StockService::class);
        $ctaCteService = app(ProveedorCtaCteService::class);
        $sucursalId = $this->pedido->sucursal_id ?: 1;

        $ppis = PedidoProveedorItem::where('pedido_proveedor_id', $this->pedido->id)->get();
        $montoAceptadoTotal = 0;
        $totalItemsRecibidos = 0;
        $hayPendientes = false;

        // 1. Procesar cada ítem y actualizar stock según mercadería aceptada
        foreach ($ppis as $ppi) {
            $cantAceptada = intval($this->receiveQty[$ppi->producto_id] ?? 0);
            $cantPedida = intval($ppi->cantidad_pedida);
            $yaRecibidoPrevio = intval($ppi->cantidad_recibida);
            $pendiente = max(0, $cantPedida - $yaRecibidoPrevio);
            $aIngresar = min($cantAceptada, $pendiente);

            if ($aIngresar > 0) {
                $stockService->ensureStockRecord($sucursalId, $ppi->producto_id);
                $stockService->adjustStock($sucursalId, $ppi->producto_id, $aIngresar, [
                    'motivo' => 'Ingreso por compra OC #' . $this->pedido->id,
                    'operacion' => 'compra',
                    'referencia_type' => 'PedidoProveedor',
                    'referencia_id' => $this->pedido->id,
                    'precio_unitario' => $ppi->costo_unitario,
                    'user_id' => Auth::id(),
                ]);

                // Actualizar precio costo y precio venta del producto
                $producto = Producto::find($ppi->producto_id);
                if ($producto && floatval($ppi->costo_unitario) > 0) {
                    $nuevoCosto = floatval($ppi->costo_unitario);
                    $nuevoPrecioVenta = $nuevoCosto + (($nuevoCosto / 100) * 60);
                    $producto->update([
                        'costo' => $nuevoCosto,
                        'precio_venta' => $nuevoPrecioVenta,
                        'precio_presupuesto' => $nuevoPrecioVenta,
                    ]);
                }

                $totalItemsRecibidos++;
            }

            $nuevoRecibidoAcumulado = $yaRecibidoPrevio + $aIngresar;
            $estadoItem = ($nuevoRecibidoAcumulado >= $cantPedida) ? 'recibido_total' : ($nuevoRecibidoAcumulado > 0 ? 'recibido_parcial' : 'pendiente');
            
            $ppi->update([
                'cantidad_recibida' => $nuevoRecibidoAcumulado,
                'estado_item' => $estadoItem,
            ]);

            $montoAceptadoTotal += ($aIngresar * floatval($ppi->costo_unitario));

            if ($nuevoRecibidoAcumulado < $cantPedida) {
                $hayPendientes = true;
            }
        }

        $facturaTotal = floatval($this->totalFactura);
        $montoBloqueado = 0;
        $escenario = $this->escenarioModal;

        // 2. Aplicar lógica según Escenario
        if ($escenario === 'escenario_2') {
            // Escenario 2: Entrega incompleta, factura total -> Bloquea la diferencia para pago y genera NC pendiente
            $diferenciaFaltante = max(0, $facturaTotal - $montoAceptadoTotal);
            $montoBloqueado = $diferenciaFaltante;
        } elseif ($escenario === 'escenario_1') {
            $montoBloqueado = 0;
        } else {
            // Escenario 3: Factura coincide con lo parcial entregado
            $montoBloqueado = 0;
        }

        // 3. Registrar Factura en Cuenta Corriente del Proveedor (Fase 3)
        $factura = $ctaCteService->registrarFactura([
            'pedido_proveedor_id' => $this->pedido->id,
            'proveedor_id' => $this->pedido->proveedor_id,
            'tipo_factura_id' => $this->tipoFacturaId,
            'numero_factura' => $this->numeroFactura,
            'fecha_emision' => $this->fechaFactura,
            'total' => $facturaTotal,
            'monto_bloqueado' => $montoBloqueado,
            'escenario_recepcion' => $escenario,
            'observaciones' => "Recepción {$escenario}. Aceptado: $" . number_format($montoAceptadoTotal, 2),
        ]);

        // 4. Actualizar estado de la Orden de Compra
        $estadoOC = 'recibido_total';
        if ($escenario === 'escenario_2') {
            $estadoOC = 'recibido_incompleto_con_factura_total';
        } elseif ($escenario === 'escenario_3' || $hayPendientes) {
            $estadoOC = 'recibido_parcial';
        }

        $this->pedido->update([
            'estado' => $estadoOC,
            'escenario_recepcion' => $escenario,
            'fecha_recepcion' => Carbon::now(),
            'usuario_receptor_id' => Auth::id(),
        ]);

        $this->closeModalRecepcion();
        $this->refreshPpiMap();
        $this->recalcularTotal();

        session()->flash('success', "Recepción registrada exitosamente ({$escenario}). Factura #{$this->numeroFactura} cargada en Cuenta Corriente.");
    }

    // ==========================================
    // RECHAZO DE ENTREGA
    // ==========================================

    public function openModalRechazo(): void
    {
        $this->modalRechazo = true;
    }

    public function closeModalRechazo(): void
    {
        $this->modalRechazo = false;
    }

    public function rechazarEntrega(): void
    {
        $this->validate([
            'motivoRechazoText' => 'required|string|min:5',
        ], [
            'motivoRechazoText.required' => 'Ingrese el motivo del rechazo.',
        ]);

        $this->pedido->update([
            'estado' => 'rechazada',
            'escenario_recepcion' => 'rechazado',
            'motivo_rechazo' => $this->motivoRechazoText,
            'fecha_recepcion' => Carbon::now(),
            'usuario_receptor_id' => Auth::id(),
        ]);

        $this->closeModalRechazo();
        session()->flash('error', 'La entrega de la orden de compra ha sido rechazada. No se ingresó stock ni se habilitó factura para pago.');
    }

    // ==========================================
    // GESTIÓN DE ITEMS EN LA OC
    // ==========================================

    public function addCantidad($id)
    {
        $this->validate([
            'cantidad' => 'required|numeric|min:1',
            'precio' => 'nullable|numeric|min:0',
        ]);

        $item = PedItem::find($id);
        if (!$item) { return; }
        $p = Producto::find($item->producto_id);

        $precioUnit = (!is_null($this->precio) && $this->precio !== '')
            ? floatval($this->precio)
            : floatval($item->precio ?? $p->costo ?? 0);

        $item->update([
            'cantidad' => $this->cantidad,
            'precio' => $precioUnit,
            'subtotal' => $precioUnit * floatval($this->cantidad),
            'estado' => '2',
        ]);

        if ($p && $precioUnit > 0 && (empty($p->costo) || $p->costo == 0)) {
            $p->update(['costo' => $precioUnit]);
        }

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

        $nuevoSubtotal = floatval($precioUnit) * floatval($this->cantidad);
        $ppi->update([
            'cantidad_pedida' => intval($this->cantidad),
            'costo_unitario' => floatval($precioUnit),
            'subtotal' => $nuevoSubtotal,
        ]);

        $this->reset(['cantidad', 'precio']);
        $this->refreshPpiMap();
        $this->recalcularTotal();
        $this->dispatch('suma-items');
    }

    public function modalProdOn()
    {
        $this->modal = true;
    }

    public function modalProdOff()
    {
        $this->modal = false;
    }

    public function addedProduct($p)
    {
        $this->producto = Producto::find($p);
        $this->modalProdOff();

        $costoInicial = floatval($this->producto->costo ?? 0);
        $this->precio = $costoInicial > 0 ? $costoInicial : '';
        $this->cantidad = '';

        $i = PedItem::create([
            'producto_id' => $this->producto->id,
            'precio' => $costoInicial,
            'estado' => '1',
        ]);

        ItemXPedido::create([
            'pedido_proveedor_id' => $this->pedido->id,
            'ped_item_id' => $i->id,
            'estado' => '1',
        ]);

        PedidoProveedorItem::firstOrCreate([
            'pedido_proveedor_id' => $this->pedido->id,
            'producto_id' => $this->producto->id,
        ], [
            'cantidad_pedida' => 0,
            'cantidad_recibida' => 0,
            'costo_unitario' => $costoInicial,
            'subtotal' => 0,
            'estado_item' => 'pendiente',
        ]);

        $this->refreshPpiMap();
        $this->recalcularTotal();
    }

    public function editProd($id)
    {
        $item = PedItem::find($id);
        if (!$item) { return; }
        $this->cantidad = $item->cantidad;
        $this->precio = $item->precio;
        $item->update(['estado' => '1']);
    }

    #[On('delete')]
    public function delProd(string $id)
    {
        $item = PedItem::find($id);
        if (!$item) { return; }

        $ppi = PedidoProveedorItem::where('pedido_proveedor_id', $this->pedido->id)
            ->where('producto_id', $item->producto_id)
            ->first();

        if ($ppi && intval($ppi->cantidad_recibida) > 0) {
            session()->flash('error', 'No se puede eliminar: el ítem ya tiene recepción registrada.');
            return;
        }

        if ($ppi) {
            $ppi->delete();
        }

        $item->delete();
        $this->refreshPpiMap();
        $this->recalcularTotal();
        session()->flash('success', 'Ítem eliminado del pedido.');
    }

    protected function refreshPpiMap(): void
    {
        $ppis = PedidoProveedorItem::where('pedido_proveedor_id', $this->pedido->id)->get();
        $this->ppiByProduct = $ppis->keyBy('producto_id');
    }

    public function render()
    {
        $this->refreshPpiMap();

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

        // Obtener facturas asociadas a esta OC
        $facturasOC = Factura::where('pedido_proveedor_id', $this->pedido->id)->get();
        $notasCreditoOC = NotaCredito::where('pedido_proveedor_id', $this->pedido->id)->get();

        return view('livewire.add-products-p-p', [
            'stock' => $stockQuery,
            'facturasOC' => $facturasOC,
            'notasCreditoOC' => $notasCreditoOC,
        ]);
    }
}
