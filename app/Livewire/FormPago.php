<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\Cliente;
use App\Models\Factura;
use App\Models\MedioPago;
use App\Models\Orden;
use App\Models\Pago;
use App\Models\PagosXCaja;
use App\Models\Tarjeta;
use App\Models\TipoFactura;
use App\Models\TipoPago;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class FormPago extends Component
{
    public $modal = false;
    public $tipoPago = '2';
    public $tiposPago;
    public $medioPago;
    public $mediosPago;
    public $efectivo;
    public $orden;
    public $vuelto;
    public $montoAPagar;
    public $montoPagado;
    public $tipoFactura = '4';
    public $tiposFactura;
    public $codeOp;
    public $tarjetas;
    public $tarjeta;
    public $montoConInt;


    public $clientes;
    public $cliente;
    public $caja;


    public function mount()
    {

        $this->tiposPago = TipoPago::all();
        $this->tarjetas = Tarjeta::all();
        $this->tiposFactura = TipoFactura::all();
        $this->mediosPago = MedioPago::all();
        $this->clientes = Cliente::where('lista_precios', '3')->get();
        $this->caja = Caja::where('user_id', Auth::user()->id)->first();
        $this->montoAPagar = $this->orden->items->sum('subtotal');


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
    }
    // Cargar intereses de tarjeta de Credito
    public function cargaInteres()
    {
        $this->montoAPagar = $this->orden->items->sum('subtotal');
        // dd($this->montoAPagar);

        $tarjeta = Tarjeta::find($this->tarjeta);
        $montoInt = floatval($this->montoAPagar / 100) * floatval($tarjeta->interes);
        $this->montoAPagar = $this->montoAPagar + $montoInt;
    //    return  $this->montoAPagar;
    }
    


    public function pagar()
    {


        if ($this->orden->estado != 100) {



            // Estados
            // Cuenta corriente 10
            // Efectivo 20
            // Parcial 30

            // ------------------------------------------------------------------------------
            // ------------------------------------------------------------------------------
            //                          Pago Cuenta Corriente
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
            //                                 Pago total
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
                        'estado' => '20',

                    ]);
                    PagosXCaja::create([
                        'pago_id' => $p->id,
                        'caja_id' => $this->caja->id,
                        'estado' => '10',

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
