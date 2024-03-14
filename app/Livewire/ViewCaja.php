<?php

namespace App\Livewire;

use App\Models\Factura;
use Livewire\Component;

class ViewCaja extends Component
{
    
    public $facturas;    
    public $totalv;    




    public function render()
    {
        $this->facturas = Factura::all();

        $this->totalv = $this->facturas->sum('total');

        return view('livewire.view-caja');
    }
}
