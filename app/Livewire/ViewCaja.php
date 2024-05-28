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
    public $montoInicial;
    public $totalEfectivo;
    public $venta;
    public $balance;
    public $gastos;

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
    public $gastosTotal;
    public $pagos;
    public $fecha;

    public $ventaTotal;




    public function mount($caja)
    {

        // Caja Estado 200 es una caja abierta
        $this->caja = $caja;
        $this->montoInicial = $this->caja->monto_inicial;
        $this->ventaTotal = $this->caja->sucursales->ordenes;

        // ____________________________________________________________________
        // ___________________PAGOS____________________________________________
        // ____________________________________________________________________

        // Efectivo
        $this->pagosEfectivo = $this->caja->pagos->filter(function ($pago) {
            return $pago->medio_pago_id == 2 && $pago->in_out != 'out';
        });
        // Transferencias
        $this->pagosTrans = $this->caja->pagos->filter(function ($pago) {
            return $pago->medio_pago_id == 5 && $pago->in_out != 'out';
        });
        // Tarjetas
        $this->pagosTarjeta = $this->caja->pagos->filter(function ($pago) {
            return $pago->medio_pago_id == 1 && $pago->in_out != 'out';
        });
        // Cuenta Corriente
        $this->pagosCtaCte = $this->caja->pagos->filter(function ($pago) {
            return $pago->medio_pago_id == 4 && $pago->in_out != 'out';
        });
        // Cuenta Cheques
        $this->pagosCheques = $this->caja->pagos->filter(function ($pago) {
            return $pago->medio_pago_id == 3 && $pago->in_out != 'out';
        });




        // Gatos
        $this->gastosEfectivo = $this->caja->pagos->filter(function ($pago) {
            return $pago->estado == 200;
        });



        $this->totalv = $this->caja->pagos->sum('total');
    }




    #[On('pago-added')]
    public function pagos()
    {
        $this->pagos = $this->caja->pagos;
    }

    // _____________________________________________________________
    // _________________CIERRE DE CAJA______________________________
    // _____________________________________________________________
    #[On('cierre-caja')]
    public function cerrarCaja($rendicion,$observaciones)
    {
        // dd($observaciones);
        $this->caja->update([

            'monto_inicial' => $this->montoInicial,

            'gastos' => $this->gastos,
            'venta' => $this->venta,

            'transferencias' => $this->pagosTrans,
            'tarjetas' => $this->pagosTarjeta,
            'efectivo' => $this->pagosEfectivo,
            'rendicion' => $rendicion,
            'cheques' => $this->pagosCheques,
            'cuenta_corriente' => $this->pagosCtaCte,

            'observaciones' => $observaciones,
            'estado' => '500',
        ]);
        redirect('venta');
    }

    public function render()
    {
        $this->pagos = $this->caja->pagos;
        $this->venta = $this->caja->pagos->filter(function ($pago) {
            return $pago->in_out != 'out';
        })->sum('total');

        // ____________________________________________________________________
        // ___________________PAGOS____________________________________________
        // ____________________________________________________________________

        // Efectivo
        $this->pagosEfectivo = $this->caja->pagos->filter(function ($pago) {
            return $pago->medio_pago_id == 2 && $pago->in_out != 'out';
        })->sum('total');
        // Transferencias
        $this->pagosTrans = $this->caja->pagos->filter(function ($pago) {
            return $pago->medio_pago_id == 5 && $pago->in_out != 'out';
        })->sum('total');
        // Tarjetas
        $this->pagosTarjeta = $this->caja->pagos->filter(function ($pago) {
            return $pago->medio_pago_id == 1 && $pago->in_out != 'out';
        })->sum('total');
        // Cuenta Corriente
        $this->pagosCtaCte = $this->caja->pagos->filter(function ($pago) {
            return $pago->medio_pago_id == 4 && $pago->in_out != 'out';
        })->sum('total');
        // Cuenta Cheques
        $this->pagosCheques = $this->caja->pagos->filter(function ($pago) {
            return $pago->medio_pago_id == 3 && $pago->in_out != 'out';
        })->sum('total');





        // Gatos

        $this->gastos = $this->caja->pagos->filter(function ($pago) {
            return $pago->in_out != 'in';
        })->sum('total') * (-1);




        // Balance
        $this->totalv = $this->caja->pagos->sum('total') + $this->montoInicial;

        $this->totalEfectivo = $this->totalv - $this->pagosCheques - $this->pagosCtaCte - $this->pagosTarjeta - $this->pagosTrans ;

        $this->balance = '';

        return view('livewire.view-caja');
    }
}
