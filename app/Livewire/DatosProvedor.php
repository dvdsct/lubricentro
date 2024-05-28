<?php

namespace App\Livewire;

use Livewire\Component;

class DatosProvedor extends Component
{
    public $proveedor;
    public $orden;

    public function mount($orden,$proveedor){
        $this->orden = $orden;
        $this->proveedor = $proveedor;

    }
    public function render()
    {
        return view('livewire.datos-provedor');
    }
}
