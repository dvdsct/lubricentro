<?php

namespace App\Livewire;

use Livewire\Component;

class DatosPersonales extends Component
{
    public $persona;
    public $orden;

    public function mount($orden,$persona){
        $this->orden = $orden;
        $this->persona = $persona;
        
    }

    public function render()
    {

        // dd($this->orden);

        return view('livewire.datos-personales');
    }
}
