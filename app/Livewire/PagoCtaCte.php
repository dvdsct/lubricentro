<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class PagoCtaCte extends Component
{
    public $orden;
    public $pago;
    public $modal = false;
    public $medioPago; // 2: efectivo, 5: transferencia, 3: tarjeta, 4: cheque (por si luego se expande)
    public $code_op;   // para transferencia
    public $monto;

    public function mount($pago){
        $this->orden = $pago->pagos->facturas->ordenes;
        $this->pago = $pago;
        $this->monto = abs($pago->total ?? 0);
    }


    #[On("pagar")]
    public function pagar() {
        $this->modal = true;
    }

    public function confirmarCobro()
    {
        $this->validate([
            'medioPago' => 'required'
        ]);

        dd([
            'orden_id' => optional($this->orden)->id,
            'pago_cta_cte_id' => $this->pago->id ?? null,
            'cliente_id' => $this->pago->cliente_id ?? null,
            'monto' => $this->monto,
            'medio_pago_id' => $this->medioPago,
            'code_op' => $this->code_op ?? null,
        ]);
    }


    public function render()
    {
        return view('livewire.pago-cta-cte');
    }
}
