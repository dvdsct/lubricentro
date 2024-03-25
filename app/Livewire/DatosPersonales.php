<?php

namespace App\Livewire;

use Livewire\Component;

class DatosPersonales extends Component
{
    public $cliente;
    public $orden;

    public function mount($orden){
        $this->orden = $orden;
        $this->cliente = $this->orden->clientes;
    }

    public function render()
    {

        // dd($this->orden);

        return view('livewire.datos-personales');
    }
}
