<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tarjeta;
use Illuminate\Support\Facades\Auth;

class TarjetaCredito extends Component
{
    public $tarjeta;
    public $descuento;
    public $interes;

    public function mount($tarjeta){
        $this->tarjeta = $tarjeta;
    }

    public function editTarjeta(){

        $this->tarjeta->update([
            'estado' => '2'
        ]);
    }


    public function stTarjeta($id){

        $this->tarjeta = Tarjeta::find($id);
        if(Auth::user()->hasRole('admin')){

            $this->tarjeta->update([
                'descuento' => $this->descuento,
                'interes' => $this->interes,
                'estado' => '1'
            ]);
        }

    }


    public function render()
    {
        return view('livewire.tarjeta-credito');
    }
}
