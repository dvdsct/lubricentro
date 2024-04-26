<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\Cliente;
use App\Models\Factura;
use App\Models\MedioPago;
use App\Models\Orden;
use App\Models\Pago;
use App\Models\PagosXCaja;
use App\Models\Proveedor;
use App\Models\Tarjeta;
use App\Models\TipoFactura;
use App\Models\TipoPago;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
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
    public $tarjetas;
    public $tarjeta;
    public $montoConInt;
    public $medioPago;
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


    public function mount($orden)
    {

        if (get_class($orden->getModel()) == "App\Models\PedidoProveedor") {
            $this->pedido = $orden;
            $this->pagoDe = 'pedido';

            $this->tiposPago = TipoPago::all();
            $this->tarjetas = Tarjeta::all();
            $this->tiposFactura = TipoFactura::all();
            $this->mediosPago = MedioPago::all();
            $this->clientes = Cliente::where('lista_precios', '3')->get();
            $this->caja = Caja::where('user_id', Auth::user()->id)
                ->where('estado', '200')
                ->first();
            $this->montoAPagar = $this->orden->items->sum('subtotal');
        }
        if (get_class($orden->getModel()) == "App\Models\Orden") {
            $this->orden = $orden;
            $this->pagoDe = 'orden';

            $this->tiposPago = TipoPago::all();
            $this->tarjetas = Tarjeta::all();
            $this->tiposFactura = TipoFactura::all();
            $this->mediosPago = MedioPago::all();
            $this->proveedores = Proveedor::all();
            $this->caja = Caja::where('user_id', Auth::user()->id)
                ->where('estado', '200')
                ->first();
                $this->montoAPagar = $this->orden->items->sum('subtotal');
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
        $this->montoAPagar = $this->orden->items->sum('subtotal');

        $tarjeta = Tarjeta::find($this->tarjeta);
        $montoInt = floatval($this->montoAPagar / 100) * floatval($tarjeta->interes);
        $this->montoAPagar = $this->montoAPagar + $montoInt;
    }

    public function updatedMedioPago()
    {
        $this->montoAPagar = $this->orden->items->sum('subtotal');
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
    }





    // ______________________________________________________________________________________________________________________
    // ________________________________________________PAGO PROVEEDOR________________________________________________________
    // ______________________________________________________________________________________________________________________

    public function pagarProvedor()
    {

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

            // Cuenta Corriente  Estado = 200
            if ($this->medioPago == 4) {

                $f =  Factura::create([

                    'pedido_proveedor_id' => $this->pedido->id,

                    'tipo_factura_id' => $this->tipoFactura,
                    'total' => $this->montoAPagar,
                    'estado' => '200'
                ]);

                $p = Pago::create([
                    'factura_id' => $f->id,
                    'proveedor_id' => $this->proveedor,
                    'medio_pago_id' => '4',
                    'tipo_pago_id' => $this->tipoPago,
                    'efectivo' => 0,
                    'total' => $this->montoAPagar,
                    'estado' => '200',

                ]);

                PagosXCaja::create([
                    'pago_id' => $p->id,
                    'caja_id' => $this->caja->id,
                    'estado' => '200',

                ]);
            }


            // Efectivo  Estado = 300
            if ($this->medioPago == 2) {


                // dd();
                $f =  Factura::create([

                    'pedido_proveedor_id' => $this->pedido->id,

                    'tipo_factura_id' => $this->tipoFactura,
                    'total' => $this->montoAPagar,
                    'estado' => '300'
                ]);

                $p = Pago::create([
                    'factura_id' => $f->id,
                    'proveedor_id' => $this->proveedor,
                    'medio_pago_id' => $this->medioPago,
                    'tipo_pago_id' => $this->tipoPago,
                    'efectivo' => $this->efectivo,
                    'total' => $this->montoAPagar,
                    'estado' => '300',

                ]);
                PagosXCaja::create([
                    'pago_id' => $p->id,
                    'caja_id' => $this->caja->id,
                    'estado' => '300',

                ]);
            }
            // Tarjeta
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
            //                          Pago Cuenta Corriente Estado = 10
            // ------------------------------------------------------------------------------
            // ------------------------------------------------------------------------------
            if ($this->tipoPago == 1) {

                $f =  Factura::create([

                    'orden_id' => $this->orden->id,

                    'tipo_factura_id' => $this->tipoFactura,
                    'total' => $this->montoAPagar,
                    'estado' => '10'
                ]);

                $p = Pago::create([
                    'factura_id' => $f->id,
                    'cliente_id' => $this->cliente,
                    'medio_pago_id' => '4',
                    'tipo_pago_id' => $this->tipoPago,
                    'efectivo' => 0,
                    'total' => $this->montoAPagar,
                    'estado' => '10',

                ]);

                PagosXCaja::create([
                    'pago_id' => $p->id,
                    'caja_id' => $this->caja->id,
                    'estado' => '10',

                ]);
            }

            // ------------------------------------------------------------------------------
            // ------------------------------------------------------------------------------
            //                                 Pago total  Estado = 2
            // ------------------------------------------------------------------------------
            // ------------------------------------------------------------------------------
            if ($this->tipoPago == 2) {
                $this->montoAPagar = $this->orden->items->sum('subtotal');


                // Medio Efectivo
                if ($this->medioPago == 2) {


                    // dd();
                    $f =  Factura::create([

                        'orden_id' => $this->orden->id,

                        'tipo_factura_id' => $this->tipoFactura,
                        'total' => $this->montoAPagar,
                        'estado' => '2'
                    ]);

                    $p = Pago::create([
                        'factura_id' => $f->id,
                        'cliente_id' => $this->cliente,
                        'medio_pago_id' => $this->medioPago,
                        'tipo_pago_id' => $this->tipoPago,
                        'efectivo' => $this->efectivo,
                        'total' => $this->montoAPagar,
                        'estado' => '2',

                    ]);
                    PagosXCaja::create([
                        'pago_id' => $p->id,
                        'caja_id' => $this->caja->id,
                        'estado' => '2',

                    ]);
                }
                // _____________________________________________________________________
                // _____________________________________________________________________
                // _____________________________________________________________________

                // Tarjeta

                if ($this->medioPago == 2) {

                    // dd();   


                    $f =  Factura::create([

                        'orden_id' => $this->orden->id,

                        'tipo_factura_id' => $this->tipoFactura,
                        'total' => $this->montoAPagar,
                        'estado' => '2'
                    ]);


                    $p = Pago::create([
                        'factura_id' => $f->id,
                        'cliente_id' => $this->cliente,
                        'medio_pago_id' => $this->medioPago,
                        'tipo_pago_id' => $this->tipoPago,
                        'efectivo' => $this->efectivo,
                        'total' => $this->montoAPagar,
                        'estado' => '20',

                    ]);
                    PagosXCaja::create([
                        'pago_id' => $p->id,
                        'caja_id' => $this->caja->id,
                        'estado' => '10',

                    ]);
                }
            }
            // ------------------------------------------------------------------------------
            // ------------------------------------------------------------------------------
            //                           Medio parcial
            // ------------------------------------------------------------------------------
            // ------------------------------------------------------------------------------

            if ($this->medioPago == 3) {

                $f =  Factura::create([

                    'orden_id' => $this->orden->id,

                    'tipo_factura_id' => $this->tipoFactura,
                    'total' => $this->montoAPagar,
                    'estado' => '2'
                ]);

                $p  = Pago::create([
                    'factura_id' => $f->id,
                    'cliente_id' => $this->cliente,
                    'medio_pago_id' => $this->medioPago,
                    'tipo_pago_id' => $this->tipoPago,
                    'efectivo' => $this->efectivo,
                    'total' => $this->montoAPagar,
                    'parcial' => $this->montoPagado,
                    'code_op' => $this->codeOp,
                    'estado' => '20',

                ]);

                PagosXCaja::create([
                    'pago_id' => $p->id,
                    'caja_id' => $this->caja->id,
                    'estado' => '10',

                ]);
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
