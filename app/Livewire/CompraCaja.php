<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\MedioPago;
use App\Models\Tarjeta;
use App\Models\TipoFactura;
use App\Models\TipoPago;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class CompraCaja extends Component
{

    
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
