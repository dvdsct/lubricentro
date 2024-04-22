<?php

namespace App\Livewire;

use App\Models\Presupuesto;
use Livewire\Component;

class ListPresupuesto extends Component
{
    public $presupuestos;

    public function mount(){
        $this->presupuestos = Presupuesto::all()->sortByDesc('estado');
    }
    
    public function render()
    {
        $this->presupuestos = Presupuesto::all();
        return view('livewire.list-presupuesto');
    }
}
