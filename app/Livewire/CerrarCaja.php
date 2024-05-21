<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CerrarCaja extends Component
{

    
    public $rendicion;
    public $faltante;
    public $observaciones;
    public $modalCierreCaja;

    
    public function cerrarCaja()
    {
        // $this->dispatch("cierre-caja,{rendicion : {{$this->rendicion}}, observaciones :{{ $this->observaciones}}}")->To(ViewCaja::class);
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
