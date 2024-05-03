<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class CerrarCaja extends Component
{
    public $efectivo;
    public $faltante;
    public $sobrante;
    public $modalCierreCaja;

    
    public function cerrarCaja()
    {
    }


    #[On('cerrar-caja-modal')]
    public function modalCerrarCaja()
    {
        if ($this->modalCierreCaja) {

            $this->modalCierreCaja = false;
        } else {
            $this->modalCierreCaja = true;
        }
    }

    public function render()
    {
        return view('livewire.cerrar-caja');
    }
}
