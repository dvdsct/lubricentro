<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Producto;
use Livewire\Attributes\On;

class LwProductos extends Component
{
    public $head = ['descripcion', 'costo'];
    public $list;
    public $producto;

    // public function mount(){

    //     $this->list = Producto::all();

    // }

    #[On('added')]
    public function oo(){

        $this->list = Producto::all();
        // dd('here');
    }

    public function render()
    {
        $this->list = Producto::all();
        return view('livewire.lw-productos');
    }
}
