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
use App\Models\PagosXCaja;
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
    public $plan;
    public $planesT;

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

    public $cupon;


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
            $this->mediosPago = MedioPago::all();
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


            $this->tiposPago = TipoPago::all();
            $this->tarjetasT = Plan::all();
            $this->tiposFactura = TipoFactura::all();
            $this->mediosPago = MedioPago::all();
            $this->proveedores = Proveedor::all();
        }
    }

    public function closeModal()
    {
        $this->modal = false;
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
        $planE = Plan::find($this->plan);

        if ($this->plan) {


            $this->montoAPagar = $this->orden->items->sum('subtotal');

            $this->interes = $planE->interes;
            $this->descuentoTarjeta = $planE->descuento;

            $this->montoInt = floatval($this->montoAPagar / 100) * floatval($this->interes);
            $this->montoAPagarInteres = $this->montoAPagar + $this->montoInt;
        }
    }

    // public function updatedMedioPago()
    // {
    //     $this->montoAPagar = $this->orden->items->sum('subtotal');
    // }

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
                    'efectivo' => $this->efectivo,
                    'concepto' => 'proveedor',

                    'total' => $this->montoAPagar,
                    'estado' => '200',

                ]);
                PagosXCaja::create([
                    'pago_id' => $p->id,
                    'caja_id' => $this->caja->id,
                    'estado' => '200',

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
                        'estado' => '40'
                    ]);

                    $p = Pago::create([
                        'in_out' => 'in',
                        'factura_id' => $f->id,
                        'cliente_id' => $this->cliente,
                        'medio_pago_id' => '4',
                        'tipo_pago_id' => $this->tipoPago,
                        'efectivo' => 0,
                        'concepto' => 'venta',
                        'total' => $this->montoAPagar,
                        'estado' => '40',

                    ]);

                    PagosXCaja::create([
                        'pago_id' => $p->id,
                        'caja_id' => $this->caja->id,
                        'estado' => '40',

                    ]);
                }


                // ------------------------------------------------------------------------------
                //                                 Pago total Cheque  Estado = 30
                // ------------------------------------------------------------------------------
                if ($this->medioPago == 3) {




                    $f =  Factura::create([

                        'orden_id' => $this->orden->id,

                        'tipo_factura_id' => $this->tipoFactura,
                        'total' => $this->montoAPagar,
                        'estado' => '30'
                    ]);

                    $p = Pago::create([
                        'in_out' => 'in',
                        'factura_id' => $f->id,
                        'cliente_id' => $this->cliente,
                        'medio_pago_id' => $this->medioPago,
                        'tipo_pago_id' => $this->tipoPago,
                        'efectivo' => $this->efectivo,
                        'concepto' => 'venta',

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



                // ------------------------------------------------------------------------------
                //                                 Pago total Efectivo  Estado = 20
                // ------------------------------------------------------------------------------
                if ($this->medioPago == 2) {


                    // dd();
                    $f =  Factura::create([

                        'orden_id' => $this->orden->id,

                        'tipo_factura_id' => $this->tipoFactura,
                        'total' => $this->montoAPagar,
                        'estado' => '20'
                    ]);

                    $p = Pago::create([
                        'in_out' => 'in',
                        'factura_id' => $f->id,
                        'cliente_id' => $this->cliente,
                        'medio_pago_id' => $this->medioPago,
                        'tipo_pago_id' => $this->tipoPago,
                        'efectivo' => $this->efectivo,
                        'concepto' => 'venta',

                        'total' => $this->montoAPagar,
                        'estado' => '20',

                    ]);
                    PagosXCaja::create([
                        'pago_id' => $p->id,
                        'caja_id' => $this->caja->id,
                        'estado' => '20',

                    ]);
                }




                // ______________________________________________________________________________
                // ______________________________________________________________________________
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
                        'estado' => '10'
                    ]);


                    $p = Pago::create([
                        'in_out' => 'in',
                        'factura_id' => $f->id,
                        'cliente_id' => $this->cliente,
                        'medio_pago_id' => $this->medioPago,
                        'tipo_pago_id' => $this->tipoPago,
                        'efectivo' => $this->efectivo,
                        'concepto' => 'venta',
                        'code_op' => $this->cupon,

                        'total' => $this->montoAPagar,
                        'estado' => '10',

                    ]);
                    PagosXCaja::create([
                        'pago_id' => $p->id,
                        'caja_id' => $this->caja->id,
                        'estado' => '10',

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
                        'estado' => '90'
                    ]);


                    $p = Pago::create([
                        'in_out' => 'in',
                        'factura_id' => $f->id,
                        'cliente_id' => $this->cliente,
                        'medio_pago_id' => $this->medioPago,
                        'tipo_pago_id' => $this->tipoPago,
                        'efectivo' => $this->efectivo,
                        'code_op' => $this->cupon,
                        'concepto' => 'venta',

                        'total' => $this->montoAPagar,
                        'estado' => '90',

                    ]);
                    PagosXCaja::create([
                        'pago_id' => $p->id,
                        'caja_id' => $this->caja->id,
                        'estado' => '90',

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





    public function render()
    {
        $this->vuelto = floatval($this->efectivo) - floatval($this->montoAPagar);
        return view('livewire.form-pago');
    }
}
