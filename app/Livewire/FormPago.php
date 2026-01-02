<?php

namespace App\Livewire;

use App\Models\Banco;
use App\Models\Caja;
use App\Models\Cheque;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Factura;
use App\Models\Descuentos;
use App\Models\DescuentoXFactura;
use App\Models\MedioPago;
use App\Models\Orden;
use App\Models\Pago;
use App\Models\PagoCtacte;
use App\Models\PagosXCaja;
use App\Models\PagoTarjeta;
use App\Models\PagoTransferencia;
use App\Models\PedidoProveedor;
use App\Models\Perfil;
use App\Models\Plan;
use App\Models\PlanXTarjeta;
use App\Models\Proveedor;
use App\Models\Tarjeta;
use App\Models\TipoFactura;
use App\Models\TipoPago;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Services\StockService;

class FormPago extends Component
{
    public $modal = false;

    public $tiposPago;


    public $mediosPago;
    public $efectivo;
    public $vuelto;
    public $total;
    public $montoAPagar;
    public $montoPagado;
    public $tipoPago = '2';
    public $tipoFactura = '4';
    public $tiposFactura;
    public $codeOp;
    // Tarjetas
    public $montoAPagarInteres;
    public $montoInt;
    public $tarjetasT;
    public $tarjeta;
    public $interes;
    public $descuentoTarjeta;
    #[Validate('required')]
    public $planSelected;
    public $planesT;
    public $plan;

    public $montoConInt;
    public $medioPago;
    public $debitoId; // MedioPago id for Tarjeta Debito
    public $debitoCupon;
    public $debitoLote;
    public $debitoAutorizacion;
    public $banco;
    public $bancos;
    public $caja;
    // Descuentos administrados
    public $descuentos;
    public $descuentoId = '';
    public $discountAmount = 0;

    // Formulario Compra
    public $pedido;
    public $proveedores;
    public $proveedor;

    // Formulario Venta
    public $orden;
    public $clientes;
    public $cliente;
    public $pagoDe;
    public $perfil;
    public $cajero;
    public $fechaCheque;
    public $nroCheque;
    public $concepto;
    public $iva = 0;
    public $checkIva = false;

    public $cupon;

    public $colorBoton = 'btn-secondary'; // Por defecto, el color del botón es secundario

    // Pago dividido (dos métodos)
    public $splitSecond = false;
    public $medioPago2;
    public $monto2;
    public $codeOp2; // para transferencia 2

    // Evitar dobles envíos/duplicados
    public $processing = false;


    public function mount($orden)
    {

        if (get_class($orden->getModel()) == "App\Models\PedidoProveedor") {
            $this->pedido = $orden;
            $this->montoAPagar = $this->pedido->items->sum('subtotal');
            $this->pagoDe = 'pedido';
            $this->perfil = Perfil::where('user_id', Auth::user()->id)->get();
            $this->cajero = $this->perfil->first()->cajeros->first();
            $this->bancos = Banco::all();


            if (Auth::user()->hasRole(['cajero'])) {

                // Verificar si existe caja

                if (Caja::where('cajero_id', $this->cajero->id)
                    ->where('estado', '200')
                    ->get()->isEmpty()
                ) {

                    $this->dispatch('setModalCaja')->To(ListaCajas::class);
                } else {

                    $this->caja = Caja::where('cajero_id', $this->cajero->id)
                        ->where('estado', '200')
                        ->first();
                }

                // ______________________________________________________________________________
                // ------------------------------------------------------------------------------
                //                                 Pago Total Tarjeta Débito  Estado = 12
                // ------------------------------------------------------------------------------
                if ($this->debitoId && (string)$this->medioPago === (string)$this->debitoId) {
                    // Validación de campos de débito
                    $this->validate([
                        'debitoCupon' => 'required',
                        'debitoLote' => 'required',
                        'debitoAutorizacion' => 'required',
                    ]);

                    // Calcular subtotal con descuento y recargo débito para registrar en factura
                    $itemsSubtotal = $this->orden->items->sum('subtotal');
                    $baseAfterDiscount = max(0, floatval($itemsSubtotal) - floatval($this->discountAmount));
                    $debitSurcharge = ($baseAfterDiscount * 5) / 100.0;

                    $f =  Factura::create([
                        'orden_id' => $this->orden->id,
                        'tipo_factura_id' => $this->tipoFactura,
                        'total' => $this->total,
                        'subtotal' => $baseAfterDiscount,
                        'intereses' => $debitSurcharge,
                        'iva' => $this->iva,
                        'estado' => '12'
                    ]);
                    if ($this->descuentoId && $this->discountAmount > 0) {
                        DescuentoXFactura::create([
                            'factura_id' => $f->id,
                            'user_id' => Auth::id(),
                            'monto' => $this->discountAmount,
                            'estado' => '1',
                        ]);
                    }
                    if ($this->descuentoId && $this->discountAmount > 0) {
                        DescuentoXFactura::create([
                            'factura_id' => $f->id,
                            'user_id' => Auth::id(),
                            'monto' => $this->discountAmount,
                            'estado' => '1',
                        ]);
                    }

                    $p = Pago::create([
                        'in_out' => 'in',
                        'factura_id' => $f->id,
                        'cliente_id' => $this->cliente,
                        'medio_pago_id' => $this->medioPago,
                        'tipo_pago_id' => $this->tipoPago,
                        'efectivo' => 0,
                        'concepto' =>  $this->concepto,
                        'code_op' => 'CUPON:' . $this->debitoCupon . '; LOTE:' . $this->debitoLote . '; AUT:' . $this->debitoAutorizacion,
                        'iva' => $this->iva,
                        'total' => $this->total,
                        'estado' => '12',
                    ]);
                    PagosXCaja::create([
                        'pago_id' => $p->id,
                        'caja_id' => $this->caja->id,
                        'estado' => '12',
                    ]);
                }
            }



            $this->tiposPago = TipoPago::all();
            $this->tarjetasT = Plan::all();
            $this->tiposFactura = TipoFactura::all();
            $this->mediosPago = MedioPago::where('descripcion', 'Efectivo')
            ->orWhere('descripcion', 'Cuenta Corriente')
            ->get();
            $this->clientes = Cliente::where('lista_precios', '3')->get();
            // Cargar descuentos habilitados (porcentaje)
            $this->descuentos = Descuentos::whereNotNull('porcentaje')->where('estado','1')->get();
        }
        if (get_class($orden->getModel()) == "App\Models\Orden") {
            $this->orden = $orden;
            $this->montoAPagar = $this->orden->items->sum('subtotal');
            $this->pagoDe = 'orden';
            $this->perfil = Perfil::where('user_id', Auth::user()->id)->get();
            $this->cajero = $this->perfil->first()->cajeros->first();
            $this->bancos = Banco::all();


            // Verificar si existe caja
            if (Caja::where('cajero_id', $this->cajero->id)
                ->where('estado', '200')
                ->get()->isEmpty()
            ) {

                $this->dispatch('setModalCaja')->To(ListaCajas::class);
            } else {

                $this->caja = Caja::where('cajero_id', $this->cajero->id)
                    ->where('estado', '200')
                    ->first();
            }


            // Asegurar que exista el medio 'Tarjeta Debito'
            $debitoMedio = MedioPago::firstOrCreate([
                'descripcion' => 'Tarjeta Debito'
            ], [
                'estado' => '1'
            ]);
            $this->mediosPago = MedioPago::all();
            $this->debitoId = $debitoMedio->id;
            $this->tiposPago = TipoPago::all();
            $this->tarjetasT = Plan::all();
            $this->tiposFactura = TipoFactura::all();
            $this->proveedores = Proveedor::all();
            $this->cliente = $this->orden->clientes->id;
            $this->clientes = Cliente::where('lista_precios', '3')->get();
            // Cargar descuentos habilitados (porcentaje)
            $this->descuentos = Descuentos::whereNotNull('porcentaje')->where('estado','1')->get();
            

        }
    }

    public function closeModal()
    {
        $this->modal = false;
    }

    public function updatedMedioPago()
    {
        $this->reset(
            'iva',
            'total',
            'checkIva'
        );
        if ($this->debitoId && (string)$this->medioPago === (string)$this->debitoId) {
            $this->reset('debitoCupon','debitoLote','debitoAutorizacion');
        }
    }

    public function updatedSplitSecond()
    {
        // Limpiar campos secundarios al desactivar
        if (!$this->splitSecond) {
            $this->reset('medioPago2','monto2','codeOp2');
        }
    }

    #[On('formPago')]
    public function genPago($tipo)
    {

        if ($tipo == 'orden') {
            // Validaciones previas: orden válida, no pagada, y con items
        if (!$this->orden) {
            $this->addError('orden', 'No hay una orden cargada para cobrar.');
            $this->processing = false;
            return;
        }
        if ($this->orden->estado == 100) {
            $this->addError('orden', 'Esta orden ya fue pagada.');
            $this->processing = false;
            return;
        }
        if ($this->orden->items->isEmpty()) {
            $this->addError('orden', 'La orden no tiene ítems para cobrar.');
            $this->processing = false;
            return;
        }

        if ($this->orden->estado != 100) {
                $this->modal = true;
            }
        }
        if ($tipo == 'proveedor') {
            if ($this->pedido->estado != 100) {
                $this->modal = true;
            }
        }
    }


    // Cargar intereses de tarjeta de Credito
    public function cargaInteres()
    {
        $this->validate();
        $this->plan = Plan::find($this->planSelected);

        if ($this->plan) {


            // Base de cálculo permanece en render(); aquí solo seteamos el plan y el interés
            $this->interes = $this->plan->interes;
            $this->descuentoTarjeta = $this->plan->descuento;

            $this->reset('checkIva', 'iva');
        }
    }



    // ______________________________________________________________________________________________________________________
    // ______________________________________________________________________________________________________________________
    //                                                   PAGAR
    // ______________________________________________________________________________________________________________________
    // ______________________________________________________________________________________________________________________

    public function pagar()
    {
        if ($this->pagoDe == 'orden') {
            $this->pagarOrden();
        }
        if ($this->pagoDe == 'pedido') {
            $this->pagarProvedor();
        }

        $this->dispatch('pago-added')->To(ViewCaja::class);
    }


    #[On('suma-items')]
    public function sumaItems()
    {

        $this->montoAPagar =  $this->orden->items->sum('subtotal');
        if ($this->pagoDe == 'orden') {
        }
    }


    // ______________________________________________________________________________________________________________________
    // ________________________________________________PAGO PROVEEDOR________________________________________________________
    // ______________________________________________________________________________________________________________________

    public function pagarProvedor()
    {
        $this->validate([
            'medioPago' => 'required'
        ]);
        // Requerir plan cuando el medio es Tarjeta Crédito
        if (intval($this->medioPago) === 1) {
            $this->validate([
                'planSelected' => 'required'
            ]);
            if (!$this->plan) {
                $this->plan = Plan::find($this->planSelected);
            }
            $this->interes = optional($this->plan)->interes;
        }

        $this->orden->update([
            'estado' => '555'
        ]);
        $this->montoAPagar  = $this->montoAPagar * (-1);
        // Pago Parcial
        if ($this->tipoPago == 1) {
            // Mixto
            // Transferencia
            // Efectivo
            // Tarjeta Credito/Debito
            // Cheque

        }




        // Pago Total
        if ($this->tipoPago == 2) {



            // Cuenta Corriente  Estado = 400
            if ($this->medioPago == 4) {

                $f =  Factura::create([

                    'pedido_proveedor_id' => $this->pedido->id,

                    'tipo_factura_id' => $this->tipoFactura,
                    'total' => $this->montoAPagar,
                    'estado' => '400'
                ]);

                $p = Pago::create([
                    'in_out' => 'out',
                    'factura_id' => $f->id,
                    'proveedor_id' => $this->proveedor,
                    'medio_pago_id' => '4',
                    'tipo_pago_id' => $this->tipoPago,
                    'efectivo' => 0,
                    'total' => $this->montoAPagar,
                    'concepto' => 'proveedor',
                    'estado' => '400',

                ]);

                PagosXCaja::create([
                    'pago_id' => $p->id,
                    'caja_id' => $this->caja->id,
                    'estado' => '400',

                ]);
                $this->pedido->update([
                    'estado' => '3'
                ]);
            }


            // Efectivo  Estado = 200
            if ($this->medioPago == 2) {


                $f =  Factura::create([

                    'pedido_proveedor_id' => $this->pedido->id,

                    'tipo_factura_id' => $this->tipoFactura,
                    'total' => $this->montoAPagar,
                    'estado' => '200'
                ]);

                $p = Pago::create([
                    'in_out' => 'out',
                    'factura_id' => $f->id,
                    'proveedor_id' => $this->proveedor,
                    'medio_pago_id' => $this->medioPago,
                    'tipo_pago_id' => $this->tipoPago,
                    'efectivo' =>$this->montoAPagar ,
                    'concepto' => 'proveedor',

                    'total' => $this->montoAPagar,
                    'estado' => '200',

                ]);
                PagosXCaja::create([
                    'pago_id' => $p->id,
                    'caja_id' => $this->caja->id,
                    'estado' => '200',

                ]);

                $this->pedido->update([
                    'estado' => '4'
                ]);
            }


            // Tarjeta Estado = 101
            if ($this->medioPago == 1) {

                // dd();


                $f =  Factura::create([

                    'orden_id' => $this->orden->id,

                    'tipo_factura_id' => $this->tipoFactura,
                    'total' => $this->montoAPagar,
                    'estado' => '101'
                ]);


                $p = Pago::create([
                    'in_out' => 'out',
                    'factura_id' => $f->id,
                    'cliente_id' => $this->cliente,
                    'concepto' => 'proveedor',

                    'medio_pago_id' => $this->medioPago,
                    'tipo_pago_id' => $this->tipoPago,
                    'efectivo' => $this->efectivo,
                    'total' => $this->montoAPagar,
                    'estado' => '101',

                ]);
                PagosXCaja::create([
                    'pago_id' => $p->id,
                    'caja_id' => $this->caja->id,
                    'estado' => '101',

                ]);
            }
        }

        // Mixto
        // Transferencia

        // Tarjeta
        // Cheque
        $this->dispatch('pedido-recibido')->to(AddProductsPP::class);
    }


    // ______________________________________________________________________________________________________________________
    // ________________________________________________FIN PAGO PROVEEDOR________________________________________________________
    // ______________________________________________________________________________________________________________________


    public function pagarOrden()
    {
        // Evitar doble ejecución en concurrencia o doble click
        if ($this->processing) {
            return;
        }
        $this->processing = true;

        $this->validate([
            'medioPago' => 'required'
        ]);
        if ($this->orden->motivo == '1') {

            $this->concepto = 'Lavadero';
        } else {
            $this->concepto = 'Lubricentro';
        }

        if ($this->orden->estado != 100) {



            // Estados
            // Cuenta corriente 10
            // Efectivo 20
            // Parcial 30



            // ------------------------------------------------------------------------------
            // ------------------------------------------------------------------------------
            //                                 Pago total  Estado = 2
            // ------------------------------------------------------------------------------
            // ------------------------------------------------------------------------------
            if ($this->tipoPago == 2) {
                $this->montoAPagar = $this->orden->items->sum('subtotal');

                // Pago total con dos métodos (split)
                if ($this->splitSecond) {
                    $this->validate([
                        'medioPago' => 'required',
                        'medioPago2' => 'required',
                        'monto2' => 'required|numeric|min:0',
                    ]);

                    // Calcular monto del primer medio como total - monto2
                    $itemsSubtotal = $this->orden->items->sum('subtotal');
                    $baseAfterDiscount = max(0, floatval($itemsSubtotal) - floatval($this->discountAmount));
                    $interesPct = floatval($this->interes ?: 0);
                    $debitSurcharge = ($this->debitoId && (string)$this->medioPago === (string)$this->debitoId) ? ($baseAfterDiscount * 5) / 100.0 : 0;
                    $montoIntCalc = ($this->medioPago == 1 && $interesPct > 0) ? ($baseAfterDiscount * $interesPct) / 100.0 : 0;
                    $totalCalc = $baseAfterDiscount + $montoIntCalc + $debitSurcharge + floatval($this->iva);

                    $m1 = max(0, floatval($totalCalc) - floatval($this->monto2));
                    if (abs(($m1 + floatval($this->monto2)) - floatval($totalCalc)) > 0.01) {
                        $this->addError('monto2', 'La suma de los montos no coincide con el total.');
                        return;
                    }

                    // Crear factura (estado pagado genérico 100)
                    $f =  Factura::create([
                        'orden_id' => $this->orden->id,
                        'tipo_factura_id' => $this->tipoFactura,
                        'total' => $totalCalc,
                        'iva' => $this->iva,
                        'estado' => '100'
                    ]);
                    if ($this->descuentoId && $this->discountAmount > 0) {
                        DescuentoXFactura::create([
                            'factura_id' => $f->id,
                            'user_id' => Auth::id(),
                            'monto' => $this->discountAmount,
                            'estado' => '1',
                        ]);
                    }

                    // Determinar estados por medio de pago
                    $estadoPorMedio = function($medioId) {
                        // Mapeo conocido por IDs usados en el sistema
                        if ($this->debitoId && (string)$medioId === (string)$this->debitoId) return '12'; // Débito
                        switch (intval($medioId)) {
                            case 1: return '10'; // Tarjeta crédito
                            case 2: return '20'; // Efectivo
                            case 3: return '30'; // Cheque
                            case 4: return '40'; // Cuenta Corriente
                            case 5: return '90'; // Transferencia
                            default: return '100'; // Genérico
                        }
                    };

                    $estado1 = $estadoPorMedio($this->medioPago);
                    $estado2 = $estadoPorMedio($this->medioPago2);

                    // Registrar primer pago
                    $p1 = Pago::create([
                        'in_out' => 'in',
                        'factura_id' => $f->id,
                        'cliente_id' => $this->cliente,
                        'medio_pago_id' => $this->medioPago,
                        'tipo_pago_id' => $this->tipoPago,
                        'efectivo' => ($this->medioPago == 2 ? $m1 : 0),
                        'concepto' =>  $this->concepto,
                        'iva' => $this->iva,
                        'total' => $m1,
                        'estado' => $estado1,
                    ]);
                    PagosXCaja::create([
                        'pago_id' => $p1->id,
                        'caja_id' => $this->caja->id,
                        'estado' => $estado1,
                    ]);

                    // Validaciones simples para transferencias en segundo medio
                    if (intval($this->medioPago2) === 5 && empty($this->codeOp2)) {
                        $this->addError('codeOp2', 'Ingrese el número de operación para la transferencia.');
                        return;
                    }

                    // Registrar segundo pago
                    $p2 = Pago::create([
                        'in_out' => 'in',
                        'factura_id' => $f->id,
                        'cliente_id' => $this->cliente,
                        'medio_pago_id' => $this->medioPago2,
                        'tipo_pago_id' => $this->tipoPago,
                        'efectivo' => (intval($this->medioPago2) === 2 ? floatval($this->monto2) : 0),
                        'concepto' =>  $this->concepto,
                        'code_op' => (intval($this->medioPago2) === 5 ? $this->codeOp2 : null),
                        'iva' => $this->iva,
                        'total' => floatval($this->monto2),
                        'estado' => $estado2,
                    ]);
                    PagosXCaja::create([
                        'pago_id' => $p2->id,
                        'caja_id' => $this->caja->id,
                        'estado' => $estado2,
                    ]);

                    // Cerrar orden como pagada
                    $this->orden->update(['estado' => '100']);
                    $this->closeModal();
                    return redirect('ordenes/' . $this->orden->id);
                }

                // ------------------------------------------------------------------------------
                // ------------------------------------------------------------------------------
                //                          Pago Total Cuenta Corriente Estado = 40
                // ------------------------------------------------------------------------------
                // ------------------------------------------------------------------------------
                if ($this->medioPago == 4) {

                    $f =  Factura::create([

                        'orden_id' => $this->orden->id,

                        'tipo_factura_id' => $this->tipoFactura,
                        'total' => $this->total,
                        'iva' => $this->iva,

                        'estado' => '40'
                    ]);
                    if ($this->descuentoId && $this->discountAmount > 0) {
                        DescuentoXFactura::create([
                            'factura_id' => $f->id,
                            'user_id' => Auth::id(),
                            'monto' => $this->discountAmount,
                            'estado' => '1',
                        ]);
                    }

                    // Crear un Pago de Cuenta Corriente sin asociarlo a Caja
                    $p = Pago::create([
                        'in_out' => 'in',
                        'factura_id' => $f->id,
                        'cliente_id' => $this->cliente,
                        'medio_pago_id' => '4',
                        'tipo_pago_id' => $this->tipoPago,
                        'efectivo' => 0,
                        'concepto' =>  $this->concepto,
                        'iva' => $this->iva,
                        'total' => $this->montoAPagar,
                        'estado' => '40',
                    ]);

                    // NO crear PagosXCaja aquí para no impactar Caja

                    // Registrar la deuda en Cuenta Corriente apuntando al Pago creado
                    PagoCtacte::create([
                        'cliente_id' => $this->cliente,
                        'pago_id' => $p->id,
                        'total' =>  $this->total * (-1),
                        'estado' => 'debe',
                    ]);
                }


                // ______________________________________________________________________________
                // ------------------------------------------------------------------------------
                //                                 Pago total Cheque  Estado = 30
                // ------------------------------------------------------------------------------
                if ($this->medioPago == 3) {
                    $f =  Factura::create([

                        'orden_id' => $this->orden->id,

                        'tipo_factura_id' => $this->tipoFactura,
                        'total' => $this->total,
                        'iva' => $this->iva,

                        'estado' => '30'
                    ]);

                    $p = Pago::create([
                        'in_out' => 'in',
                        'factura_id' => $f->id,
                        'cliente_id' => $this->cliente,
                        'medio_pago_id' => $this->medioPago,
                        'tipo_pago_id' => $this->tipoPago,
                        'efectivo' => $this->efectivo,
                        'iva' => $this->iva,

                        'concepto' =>  $this->concepto,

                        'total' => $this->total,
                        'estado' => '30',

                    ]);
                    PagosXCaja::create([
                        'pago_id' => $p->id,
                        'caja_id' => $this->caja->id,
                        'estado' => '30',

                    ]);

                    $cheque = Cheque::create([

                        'banco_id' => $this->banco,
                        // 'cliente_id' => $this->cliente,
                        'pago_id' => $p->id,
                        'vencimiento' => $this->fechaCheque,
                        'monto' => $this->montoAPagar,
                        'nro_cheque' => $this->nroCheque,
                        'estado' => '30',


                    ]);
                }



                // ______________________________________________________________________________
                // ------------------------------------------------------------------------------
                //                                 Pago total Efectivo  Estado = 20
                // ------------------------------------------------------------------------------
                if ($this->medioPago == 2) {


                    // dd();
                    $f =  Factura::create([

                        'orden_id' => $this->orden->id,

                        'tipo_factura_id' => $this->tipoFactura,
                        'total' => $this->total,
                        'iva' => $this->iva,

                        'estado' => '20'
                    ]);
                    if ($this->descuentoId && $this->discountAmount > 0) {
                        DescuentoXFactura::create([
                            'factura_id' => $f->id,
                            'user_id' => Auth::id(),
                            'monto' => $this->discountAmount,
                            'estado' => '1',
                        ]);
                    }

                    $p = Pago::create([
                        'in_out' => 'in',
                        'factura_id' => $f->id,
                        'cliente_id' => $this->cliente,
                        'medio_pago_id' => $this->medioPago,
                        'tipo_pago_id' => $this->tipoPago,
                        'efectivo' => $this->total,
                        'concepto' =>  $this->concepto,
                        'iva' => $this->iva,

                        'total' => $this->total,
                        'estado' => '20',

                    ]);
                    PagosXCaja::create([
                        'pago_id' => $p->id,
                        'caja_id' => $this->caja->id,
                        'estado' => '20',

                    ]);
                }




                // ______________________________________________________________________________
                // ------------------------------------------------------------------------------
                //                                 Pago Total Tarjeta Débito  Estado = 12
                // ------------------------------------------------------------------------------
                if ($this->debitoId && (string)$this->medioPago === (string)$this->debitoId) {

                    $this->validate([
                        'debitoCupon' => 'required',
                        'debitoLote' => 'required',
                        'debitoAutorizacion' => 'required',
                    ]);

                    $f =  Factura::create([
                        'orden_id' => $this->orden->id,
                        'tipo_factura_id' => $this->tipoFactura,
                        'total' => $this->total,
                        'iva' => $this->iva,
                        'estado' => '12'
                    ]);

                    $p = Pago::create([
                        'in_out' => 'in',
                        'factura_id' => $f->id,
                        'cliente_id' => $this->cliente,
                        'medio_pago_id' => $this->medioPago,
                        'tipo_pago_id' => $this->tipoPago,
                        'efectivo' => 0,
                        'concepto' =>  $this->concepto,
                        'code_op' => 'CUPON:' . $this->debitoCupon . '; LOTE:' . $this->debitoLote . '; AUT:' . $this->debitoAutorizacion,
                        'iva' => $this->iva,
                        'total' => $this->total,
                        'estado' => '12',
                    ]);
                    PagosXCaja::create([
                        'pago_id' => $p->id,
                        'caja_id' => $this->caja->id,
                        'estado' => '12',
                    ]);
                }

                // ______________________________________________________________________________
                // ------------------------------------------------------------------------------
                //                                 Pago Total Tarjeta Crédito  Estado = 10
                // ------------------------------------------------------------------------------
                if ($this->medioPago == 1) {

                    // dd();

                    // Calcular base y subtotal sin mutar estados previos
                    $itemsSubtotal = $this->orden->items->sum('subtotal');
                    $baseAfterDiscount = max(0, floatval($itemsSubtotal) - floatval($this->discountAmount));

                    $f =  Factura::create([

                        'orden_id' => $this->orden->id,

                        'tipo_factura_id' => $this->tipoFactura,
                        'total' => $this->total,
                        'subtotal' => $baseAfterDiscount,
                        'intereses' => $this->montoInt,
                        'descuentos' => '',
                        'iva' => $this->iva,
                        'estado' => '10'
                    ]);
                    if ($this->descuentoId && $this->discountAmount > 0) {
                        DescuentoXFactura::create([
                            'factura_id' => $f->id,
                            'user_id' => Auth::id(),
                            'monto' => $this->discountAmount,
                            'estado' => '1',
                        ]);
                    }


                    $p = Pago::create([
                        'in_out' => 'in',
                        'factura_id' => $f->id,
                        'cliente_id' => $this->cliente,
                        'medio_pago_id' => $this->medioPago,
                        'tipo_pago_id' => $this->tipoPago,
                        'efectivo' => 0,
                        'concepto' =>  $this->concepto,
                        'code_op' => $this->cupon,
                        'iva' => $this->iva,


                        'total' => $this->total,
                        'estado' => '10',

                    ]);
                    PagosXCaja::create([
                        'pago_id' => $p->id,
                        'caja_id' => $this->caja->id,
                        'estado' => '10',

                    ]);

                    PagoTarjeta::create([

                        'plan_id' => $this->plan->id,
                        'cliente_id' => $this->cliente,
                        'pago_id' => $p->id,
                        'caja_id' => $this->caja->id,
                        'subtotal' => $this->montoAPagar,
                        'total' => $this->total,
                        'nro_cupon' => $this->codeOp,
                        'estado' => '1',


                    ]);
                }


                // ______________________________________________________________________________
                // ______________________________________________________________________________
                // ______________________________________________________________________________

                // ------------------------------------------------------------------------------
                //                                 Pago Total Transferencia  Estado = 90
                // ------------------------------------------------------------------------------
                if ($this->medioPago == 5) {
                    $f =  Factura::create([

                        'orden_id' => $this->orden->id,

                        'tipo_factura_id' => $this->tipoFactura,
                        'total' => $this->total,
                        'iva' => $this->iva,

                        'estado' => '90'
                    ]);
                    if ($this->descuentoId && $this->discountAmount > 0) {
                        DescuentoXFactura::create([
                            'factura_id' => $f->id,
                            'user_id' => Auth::id(),
                            'monto' => $this->discountAmount,
                            'estado' => '1',
                        ]);
                    }


                    $p = Pago::create([
                        'in_out' => 'in',
                        'factura_id' => $f->id,
                        'cliente_id' => $this->cliente,
                        'medio_pago_id' => $this->medioPago,
                        'tipo_pago_id' => $this->tipoPago,
                        'efectivo' =>0,
                        'code_op' => $this->cupon,
                        'iva' => $this->iva,

                        'concepto' =>  $this->concepto,

                        'total' => $this->total,
                        'estado' => '90',

                    ]);
                    PagosXCaja::create([
                        'pago_id' => $p->id,
                        'caja_id' => $this->caja->id,
                        'estado' => '90',

                    ]);

                    PagoTransferencia::create([

                        'cliente_id' => $this->cliente,
                        'pago_id' => $p->id,
                        'caja_id' => $this->caja->id,
                        'subtotal' => $this->montoAPagar - $this->montoConInt,
                        'total' => $this->montoAPagar,
                        'nro_cupon' => $this->codeOp,
                        'estado' => '1',


                    ]);
                }
            }



            // Si el pago es total (no cuenta corriente), descontar stock ahora
            if ($this->medioPago != 4) {
                $this->deductStockIfNeeded();
            }

            // Estado de la orden: 10 = Cuenta Corriente pendiente, 100 = pagado
            $this->orden->update([
                'estado' => $this->medioPago == 4 ? '10' : '100'
            ]);

            $this->closeModal();
            $this->reset(
                'tipoPago',
                'medioPago',
                'efectivo',
                'vuelto',
                'montoAPagar',
                'montoPagado',
                'tipoFactura',
                'codeOp',
                'cliente',
            );
        }


        $this->processing = false;
        return redirect('ordenes/' . $this->orden->id);
    }

    /**
     * Descuenta stock por los ítems de la orden sólo una vez al cobrarse totalmente.
     */
    protected function deductStockIfNeeded(): void
    {
        if (!$this->orden) return;
        // Si ya existen movimientos de stock para esta orden con delta negativo, no repetir
        $exists = StockMovement::where('referencia_type', 'Orden')
            ->where('referencia_id', $this->orden->id)
            ->where('delta', '<', 0)
            ->exists();
        if ($exists) return;

        $stockService = app(StockService::class);
        $sucursalId = ($this->orden->sucursal_id ?? 1);

        foreach ($this->orden->items as $i) {
            // Cargar producto y evitar descontar si es provisional
            $producto = Producto::find($i->producto_id);
            if (!$producto) continue;
            if ($producto->es_provisional) continue;

            $stockService->adjustStock($sucursalId, $producto->id, -abs(intval($i->cantidad)), [
                'motivo' => 'Pago de orden',
                'operacion' => 'Pago de orden',
                'referencia_type' => 'Orden',
                'referencia_id' => $this->orden->id,
                'user_id' => Auth::id(),
                'precio_unitario' => $i->precio ?? null,
            ]);
        }
    }


    public function montoExacto()
    {
        $this->efectivo = $this->total;
        $this->colorBoton = 'btn-success';
    }

    public function setIva()
    {

        if ($this->medioPago == 1) {
            $this->validate([
                'planSelected' => 'required'
            ]);
        }
        if ($this->checkIva) {

            $this->iva = (($this->montoAPagar / 100) * 21);
            $this->total = $this->montoAPagar + $this->iva;
        } else {
            $this->iva = 0;
            $this->total = $this->montoAPagar + $this->iva;
        }
    }












    public function render()
    {
        // Subtotal original de items
        $itemsSubtotal = 0;
        if ($this->pagoDe === 'orden' && $this->orden) {
            $itemsSubtotal = $this->orden->items->sum('subtotal');
        } elseif ($this->pagoDe === 'pedido' && $this->pedido) {
            $itemsSubtotal = $this->pedido->items->sum('subtotal');
        }
        // Mostrar siempre el subtotal base
        $this->montoAPagar = $itemsSubtotal;

        // Calcular descuento administrado (porcentaje) sobre subtotal base
        $this->discountAmount = 0;
        if ($this->descuentoId) {
            $d = Descuentos::find($this->descuentoId);
            if ($d && $d->porcentaje !== null && $d->porcentaje !== '') {
                $this->discountAmount = (floatval($itemsSubtotal) * floatval($d->porcentaje)) / 100.0;
            }
        }

        $baseAfterDiscount = max(0, floatval($itemsSubtotal) - floatval($this->discountAmount));

        // Interés de tarjeta crédito calculado sobre base descontada
        $interesPct = floatval($this->interes ?: 0);
        $this->montoInt = 0;
        if ($this->medioPago == 1 && $interesPct > 0) {
            $this->montoInt = ($baseAfterDiscount * $interesPct) / 100.0;
        }

        // Recargo para Tarjeta Débito: 5% sobre base descontada
        $debitSurcharge = 0;
        if ($this->debitoId && (string)$this->medioPago === (string)$this->debitoId) {
            $debitSurcharge = ($baseAfterDiscount * 5) / 100.0;
        }

        $this->total = $baseAfterDiscount + $this->montoInt + $debitSurcharge + floatval($this->iva);

        $this->vuelto = max(0, floatval($this->efectivo) - floatval($this->total));

        return view('livewire.form-pago');
    }
}
