<?php

namespace App\Livewire;

use App\Models\Banco;
use App\Models\Caja;
use App\Models\Cheque;
use App\Models\Cliente;
use App\Models\Factura;
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
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

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
    public $banco;
    public $bancos;
    public $caja;

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
            }



            $this->tiposPago = TipoPago::all();
            $this->tarjetasT = Plan::all();
            $this->tiposFactura = TipoFactura::all();
            $this->mediosPago = MedioPago::where('descripcion', 'Efectivo')
            ->orWhere('descripcion', 'Cuenta Corriente')
            ->get();
            $this->clientes = Cliente::where('lista_precios', '3')->get();
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


            $this->mediosPago = MedioPago::all();
            $this->tiposPago = TipoPago::all();
            $this->tarjetasT = Plan::all();
            $this->tiposFactura = TipoFactura::all();
            $this->proveedores = Proveedor::all();
            $this->cliente = $this->orden->clientes->id;
            

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
    }

    #[On('formPago')]
    public function genPago($tipo)
    {

        if ($tipo == 'orden') {
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


            $this->montoAPagar = $this->orden->items->sum('subtotal');

            $this->interes = $this->plan->interes;
            $this->descuentoTarjeta = $this->plan->descuento;

            $this->montoInt = floatval($this->montoAPagar / 100) * floatval($this->interes);
            $this->montoAPagar = $this->montoAPagar + $this->montoInt;

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

                // ------------------------------------------------------------------------------
                // ------------------------------------------------------------------------------
                //                          Pago Total Cuenta Corriente Estado = 40
                // ------------------------------------------------------------------------------
                // ------------------------------------------------------------------------------
                if ($this->medioPago == 4) {

                    $f =  Factura::create([

                        'orden_id' => $this->orden->id,

                        'tipo_factura_id' => $this->tipoFactura,
                        'total' => $this->montoAPagar,
                        'iva' => $this->iva,

                        'estado' => '40'
                    ]);

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

                    PagosXCaja::create([
                        'pago_id' => $p->id,
                        'caja_id' => $this->caja->id,
                        'estado' => '40',

                    ]);

                    PagoCtacte::create([
                        'cliente_id' => $this->cliente,
                        'pago_id' => $p->id,
                        'total' =>  $this->montoAPagar * (-1),
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
                        'total' => $this->montoAPagar,
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

                        'total' => $this->montoAPagar,
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
                        'total' => $this->montoAPagar,
                        'iva' => $this->iva,

                        'estado' => '20'
                    ]);

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
                //                                 Pago Total Tarjeta  Estado = 10
                // ------------------------------------------------------------------------------
                if ($this->medioPago == 1) {

                    // dd();


                    $f =  Factura::create([

                        'orden_id' => $this->orden->id,

                        'tipo_factura_id' => $this->tipoFactura,
                        'total' => $this->montoAPagar,
                        'subtotal' => $this->montoAPagar - $this->montoConInt,
                        'intereses' => $this->montoInt,
                        'descuentos' => '',
                        'iva' => $this->iva,
                        'estado' => '10'
                    ]);


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
                        'total' => $this->montoAPagar,
                        'iva' => $this->iva,

                        'estado' => '90'
                    ]);


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



            $this->orden->update([
                'estado' => '100'
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


        return redirect('ordenes/' . $this->orden->id);
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
        $this->total = $this->montoAPagar + $this->iva;

        $this->vuelto = floatval($this->efectivo) - floatval($this->total);

        if ($this->vuelto < 0) {
            $this->vuelto = 0;
        } else {
            $this->vuelto = floatval($this->efectivo) - floatval($this->total);
        }
        return view('livewire.form-pago');
    }
}
