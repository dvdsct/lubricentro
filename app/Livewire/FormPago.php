<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\Factura;
use App\Models\MedioPago;
use App\Models\Orden;
use App\Models\Pago;
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
    public $codeCard;

    public $clientes;
    public $cliente;


    public function mount()
    {

        $this->tiposPago = TipoPago::all();
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
        // dd('here');
        $this->modal = true;

        $o = Orden::find($this->orden->id);
    }

    public function pagar()
    {

        if($this->tipoPago == 1){

            $f =  Factura::create([

                'orden_id' => $this->orden->id,

                'tipo_factura_id' => $this->tipoFactura,
                'total' => $this->montoAPagar,
                'estado' => '2'
            ]);

            Pago::create([
                'factura_id' => $f->id,
                'tipo_pago_id' => $this->tipoPago,
                'efectivo' => 0,
                'total' => $this->montoAPagar,
                'estado' => '10',

            ]);

        }













    }





    public function render()
    {
        $this->vuelto = $this->montoAPagar - $this->efectivo;
        $this->montoAPagar = $this->orden->items->sum('subtotal');
        return view('livewire.form-pago');
    }
}
