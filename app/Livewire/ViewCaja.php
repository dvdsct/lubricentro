<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\Factura;
use App\Models\Pago;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ViewCaja extends Component
{

    public $facturas;
    public $totalv;
    public $caja;

    public $pagosEfectivo;
    public $pagosTrans;
    public $pagosTarjeta;
    public $pagosCheques;
    public $pagosCtaCte;

    public $gastosEfectivo;
    public $gastosTrans;
    public $gastosTarjeta;
    public $gastosCheques;
    public $gastosCtaCte;
    public $pagos;




    public function mount($caja)
    {

        // Caja Estado 200 es una caja abierta
        $this->caja = $caja;

        // Tarjeta de Credito
        $this->pagosTarjeta = $this->caja->pagos->filter(function ($pago) {
            return $pago->medio_pago_id == 1;
        });
        // Efectivo
        $this->pagosEfectivo = $this->caja->pagos->filter(function ($pago) {
            return $pago->medio_pago_id == 2;
        });
        // Cheque
        $this->pagosCheques = $this->caja->pagos->filter(function ($pago) {
            return $pago->medio_pago_id == 3;
        });
        // Cuenta Corriente
        $this->pagosCtaCte = $this->caja->pagos->filter(function ($pago) {
            return $pago->medio_pago_id == 1;
        });
        // Transferencias
        $this->pagosTrans = $this->caja->pagos->filter(function ($pago) {
            return $pago->medio_pago_id == 1;
        });

        $this->totalv = $this->caja->pagos->sum('total');
    }




    #[On('pago-added')]
    public function pagos()
    {
        $this->pagos = $this->caja->pagos;
    }

    public function render()
    {
        $this->pagos = $this->caja->pagos;
        $this->pagosEfectivo = $this->caja->pagos->filter(function ($pago) {
            return $pago->medio_pago_id == 2;
        });
        $this->pagosTrans = $this->caja->pagos->filter(function ($pago) {
            return $pago->medio_pago_id == 5;
        });
        $this->pagosTarjeta = $this->caja->pagos->filter(function ($pago) {
            return $pago->medio_pago_id == 1;
        });

        $this->totalv = $this->caja->pagos->sum('total');

        return view('livewire.view-caja');
    }
}
