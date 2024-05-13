<?php

namespace App\Livewire;

use App\Models\Orden;
use Livewire\Attributes\On;
use Livewire\Component;

class FacturarPresupuesto extends Component
{
    public $presupuesto;
    public $items;
    public $orden;
    public $cliente;
    public $vehiculo;
    public $horario;
    public $modalOrdenPres;

    public function mount($presupuesto)
    {
        $this->presupuesto = $presupuesto;
        $this->items =  $this->presupuesto->itemspres;
        $this->cliente = $this->presupuesto->clientes;
    }


    #[On('printPresupuesto')]
    public function printPres(){

            redirect('pdfpres/'. $this->presupuesto->id);
    }

    public function render()
    {
        return view('livewire.facturar-presupuesto');
    }
}
