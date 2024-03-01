<?php

namespace App\Livewire;

use App\Models\Producto;
use App\Models\Servicio;
use Livewire\Component;

class AddProducts extends Component
{
    // Vista
    public $productos;
    public $servicios;
    public $items;

    // De la Orden
    public $producto;
    public $servicio;
    public $item;


    public function addProduct(){

        //
    }


    public function render()
    {
        $this->productos = Producto::all();
        $this->servicios = Servicio::all();
        return view('livewire.add-products');
    }
}
