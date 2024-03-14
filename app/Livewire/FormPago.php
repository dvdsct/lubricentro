<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\Factura;
use App\Models\MedioPago;
use App\Models\Orden;
use App\Models\Pago;
use App\Models\TipoFactura;
use App\Models\TipoPago;
use Livewire\Attributes\On;
use Livewire\Component;

class FormPago extends Component
{
    public $modal = false;
    public $tipoPago;
    public $tiposPago;
    public $medioPago;
    public $mediosPago;
    public $efectivo;
    public $orden;
    public $vuelto;
    public $montoAPagar;
    public $montoPagado;
    public $tipoFactura;
    public $tiposFactura;
    public $codeOp;

    public $clientes;
    public $cliente;


    public function mount()
    {

        $this->tiposPago = TipoPago::all();
        $this->tiposFactura = TipoFactura::all();
        $this->mediosPago = MedioPago::all();
        $this->clientes = Cliente::where('lista_precios', '3')->get();
    }

    public function closeModal()
    {
        $this->modal = false;
    }


    #[On('formPago')]
    public function genPago()
    {

        // $o = Orden::find($this->orden->id);
        if ($this->orden->estado != 100) {
            $this->modal = true;
        }
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

                Pago::create([
                    'factura_id' => $f->id,
                    'cliente_id' => $this->cliente,
                    'medio_pago_id' => '4',
                    'tipo_pago_id' => $this->tipoPago,
                    'efectivo' => 0,
                    'total' => $this->montoAPagar,
                    'estado' => '10',

                ]);
            }

            // ------------------------------------------------------------------------------
            // ------------------------------------------------------------------------------
            //                                 Pago total
            // ------------------------------------------------------------------------------
            // ------------------------------------------------------------------------------
            if ($this->tipoPago == 2) {

                // Medio Efectivo
                if ($this->medioPago == 2) {

                    // dd();
                    $f =  Factura::create([

                        'orden_id' => $this->orden->id,

                        'tipo_factura_id' => $this->tipoFactura,
                        'total' => $this->montoAPagar,
                        'estado' => '2'
                    ]);

                    Pago::create([
                        'factura_id' => $f->id,
                        'cliente_id' => $this->cliente,
                        'medio_pago_id' => $this->medioPago,
                        'tipo_pago_id' => $this->tipoPago,
                        'efectivo' => $this->efectivo,
                        'total' => $this->montoAPagar,
                        'estado' => '20',

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

                Pago::create([
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
        $this->vuelto = $this->montoAPagar - $this->efectivo;
        $this->montoAPagar = $this->orden->items->sum('subtotal');
        return view('livewire.form-pago');
    }
}
