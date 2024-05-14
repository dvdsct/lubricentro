<?php

namespace App\Livewire;

use Livewire\Component;

class ProductoView extends Component
{
    public $producto;
    public function mount($producto){

        $this->producto = $producto;
    }
    public function render()
    {

        return view('livewire.producto-view');
    }
}
