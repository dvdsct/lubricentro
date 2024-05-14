<?php

namespace App\Livewire;

use Livewire\Component;

class DatosProvedor extends Component
{
    public $persona;
    public $orden;

    public function mount($orden,$persona){
        $this->orden = $orden;
        $this->persona = $persona;
        
    }
    public function render()
    {
        return view('livewire.datos-provedor');
    }
}
