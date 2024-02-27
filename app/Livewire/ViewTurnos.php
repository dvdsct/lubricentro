<?php

namespace App\Livewire;

use App\Models\Orden;
use Livewire\Component;
use App\Models\Producto;

class ViewTurnos extends Component
{
    public $turnlav;
    public $headslav = ['vehiculo','motivo','encargado'];
    public $turnlub;
    public $headslub = [];

    public $ordenes;



    public function render()
    {
        $this->turnlav = Orden::where('motivo','1')->get();
        $this->turnlub = Orden::where('motivo','2')->get();

        return view('livewire.view-turnos');
    }
}
