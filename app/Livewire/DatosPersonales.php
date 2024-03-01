<?php

namespace App\Livewire;

use Livewire\Component;

class DatosPersonales extends Component
{
    public $cliente;
    public $orden;

    public function render()
    {

        $this->cliente = $this->orden->clientes;

        return view('livewire.datos-personales');
    }
}
