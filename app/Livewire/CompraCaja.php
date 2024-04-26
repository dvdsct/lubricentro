<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\Factura;
use App\Models\MedioPago;
use App\Models\Pago;
use App\Models\PagosXCaja;
use App\Models\Tarjeta;
use App\Models\TipoFactura;
use App\Models\TipoPago;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CompraCaja extends Component
{


    public $tiposPago;
    public $mediosPago;
    public $vuelto;
    public $montoPagado;
    public $tipoPago = '2';
    public $tipoFactura = '4';
    public $tiposFactura;
    public $codeOp;
    public $tarjetas;
    public $tarjeta;
    public $montoConInt;
    public $efectivo;

    #[Validate('required',message:'*')]
    public $medioPago;
    #[Validate('required',message:'*')]
    public $montoAPagar;
    #[Validate('required',message:'*')]
    public $concepto;

    public $caja;

    // Formulario Compra
    public $pedido;
    public $proveedores;
    public $proveedor;

    public $step = 1;
    public $modalCompra = false;

    public function mount(){
        $this->tiposPago = TipoPago::all();
        $this->tarjetas = Tarjeta::all();
        $this->tiposFactura = TipoFactura::all();
        $this->mediosPago = MedioPago::all();
        $this->caja = Caja::where('user_id', Auth::user()->id)
            ->where('estado', '200')
            ->first();
    }


    public function pagar(){
        $this->validate();

        $f =  Factura::create([

            'pedido_proveedor_id' => '1',

            'tipo_factura_id' => '1',
            'total' => $this->montoAPagar,
            'estado' => '200'
        ]);

        $p = Pago::create([
            'factura_id' => $f->id,
            'proveedor_id' => '1',
            'medio_pago_id' => '4',
            'tipo_pago_id' => $this->tipoPago,
            'concepto' => $this->concepto,
            'efectivo' => 0,
            'total' => $this->montoAPagar,
            'estado' => '200',

        ]);

        PagosXCaja::create([
            'pago_id' => $p->id,
            'caja_id' => $this->caja->id,
            'estado' => '200',

        ]);

        $this->modalCompraOn();
    }




    #[On('modal-compra')]
    public function modalCompraOn()
    {
        if ($this->modalCompra) {
            $this->modalCompra = false;
        } else {
            $this->modalCompra = true;
        }
    }



    public function render()
    {
        return view('livewire.compra-caja');
    }
}
