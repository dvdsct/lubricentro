<?php

namespace App\Livewire;

use App\Models\Producto;
use Livewire\Attributes\On;
use Livewire\Component;

class FormAddProd extends Component
{
    public $producto;
    public $descripcion;
    public $cod_barra;
    public $costo;
    public $codigo;
    public $modal;


    public function modalOn()
    {
        $this->modal = true;
    }
    public function modalOff()
    {
        $this->modal = false;
    }

    public function saveproduct()
    {

        $p = Producto::create([
            'descripcion' => $this->descripcion,
            'codigo_de_barras' => $this->cod_barra,
            'costo' => $this->costo,
            'codigo' => $this->codigo,
        ]);
        $this->modalOff();
        $this->dispatch('added')->to(LwProductos::class);
    }

    
    public function render()
    {
        return view('livewire.form-add-prod');
    }
}
