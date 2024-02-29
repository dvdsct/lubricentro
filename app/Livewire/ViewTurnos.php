<?php

namespace App\Livewire;

use App\Models\Orden;
use Livewire\Component;
use App\Models\Producto;
use Illuminate\Support\Carbon;

class ViewTurnos extends Component
{
    public $turnlav;
    public $headslav = ['vehiculo','motivo','encargado'];
    public $turnlub;
    public $headslub = [];

    public $ordenes;
    public $fecha;


    public function mount(){
        $this->fecha = Carbon::now()->format('Y-m-d');

    }

    public function openModal(){

       $this->dispatch('modal-order')->to(FormCreateOrder::class);
    }

    public function change_day($day)
    {


        if ($day == 'yes') {
            $this->fecha = Carbon::parse($this->fecha)->subDay()->format('Y-m-d');
        }
        if ($day == 'tmw') {
            $this->fecha = Carbon::parse($this->fecha)->addDay()->format('Y-m-d');
        }
    }




    public function render()
    {
        $this->turnlav = Orden::where('motivo','1')
        ->whereDate('created_at', $this->fecha)
        ->get();
        $this->turnlub = Orden::where('motivo','2')
        ->whereDate('created_at', $this->fecha)
        ->get();

        return view('livewire.view-turnos');
    }
}
