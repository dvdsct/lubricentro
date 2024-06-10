<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\Cajero;
use App\Models\Factura;
use App\Models\MedioPago;
use App\Models\Pago;
use App\Models\PagosXCaja;
use App\Models\Perfil;
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

    public $medioPago = '2';
    #[Validate('required', message: 'Debe indicar un monto!')]
    public $montoAPagar;
    #[Validate('required', message: 'Debe indicar el concepto!')]
    public $concepto;


    public $caja;

    // Formulario Compra
    public $pedido;
    public $proveedores;
    public $proveedor;
    public $cajero;
    public $perfil;
    public $tipoMov = false;
    public $in = false;
    public $out = false;
    #[Validate('required', message: 'Debe indicar el tipo de movimiento!')]
    public $inOut;





    public $step = 1;
    public $modalCompra = false;

    public function mount($caja)
    {
        $this->tiposPago = TipoPago::all();
        $this->tarjetas = Tarjeta::all();
        $this->tiposFactura = TipoFactura::all();
        $this->mediosPago = MedioPago::all();
        $this->caja = $caja;
        // $this->perfil = Perfil::where('user_id', Auth::user()->id)->get();
        // $this->cajero = Cajero::where('perfil_id', $this->perfil->first()->id)->get();
        // $this->caja = Caja::where('user_id', $this->cajero->first()->id)
        //     ->where('estado', '200')
        //     ->first();
    }


    // Seleccionar tipo de movimiento
    public function setMov($mov)
    {


        if ($mov == 'in') {
            $this->tipoMov = false;
            $this->in = true;
            $this->out = false;
            $this->inOut = 'in'; // Añade esto
        } else {
            $this->tipoMov = true;
            $this->in = false;
            $this->out = true;
            $this->inOut = 'out'; // Añade esto
        }
    }


    public function pagar()
    {
        $this->validate();

        if ($this->tipoMov) {


            $this->montoAPagar  = $this->montoAPagar * (-1);
            $this->inOut = 'out';
        } else {

            $this->inOut = 'in';
        }


        $f =  Factura::create([

            'pedido_proveedor_id' => '1',
            'tipo_factura_id' => '1',
            'total' => $this->montoAPagar,
            'estado' => '200'
        ]);

        $p = Pago::create([
            'factura_id' => $f->id,
            'proveedor_id' => '1',
            'in_out' => $this->inOut,
            'medio_pago_id' => '2',
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
        $this->reset('montoAPagar', 'concepto', 'in', 'out', 'tipoMov' , 'inOut');

        $this->dispatch('pago-added')->To(ViewCaja::class);
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
