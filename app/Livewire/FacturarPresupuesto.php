<?php

namespace App\Livewire;

use Livewire\Component;

class FacturarPresupuesto extends Component
{
    public $presupuesto;
    public $cliente;
    public $items;

    public function mount($presupuesto){
        $this->presupuesto = $presupuesto;
        $this->items =  $this->presupuesto->itemspres;
    }


    public function facturarPres(){
        
        foreach($this->items as $i){

        }
    }
    public function render()
    {
        return view('livewire.facturar-presupuesto');
    }
}
