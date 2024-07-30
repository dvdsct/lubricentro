<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class PagoCtaCte extends Component
{
    public $orden;
    public $pago;

    public function mount($pago){
        $this->orden = $pago->pagos->facturas->ordenes;
        $this->pago = $pago;
    }


    #[On("pagar")]
    public function pagar() {

        dd($this->orden);

    }


    public function render()
    {
        return view('livewire.pago-cta-cte');
    }
}
