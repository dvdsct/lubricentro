<?php

namespace App\Livewire;

use App\Models\Tarjeta;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ListTarjetas extends Component
{
    public $tarjetas;
    public $tarjeta;

    #[Validate('required',message:'Ingrese un valor')]
    public $descuento;

    #[Validate('required',message:'Ingrese un valor')]
    public $interes;


    public function mount()
    {
        $this->tarjetas = Tarjeta::all();
    }





    public function stTarjeta($id)
    {
        $this->validate();
        if (Auth::user()->hasRole('admin')) {

            $this->tarjeta = Tarjeta::find($id);
            $this->tarjeta->update([
                'descuento' => $this->descuento,
                'interes' => $this->interes,
                'estado' => '1'
            ]);
        }
    }



    public function editTarjeta($id)
    {

        $tarjeta = Tarjeta::find($id);

        $tarjeta->update([
            'estado' => '2'
        ]);
        $this->descuento = $tarjeta->descuento;
        $this->interes = $tarjeta->interes;
    }



    public function render()
    {
        $this->tarjetas = Tarjeta::all();

        return view(
            'livewire.list-tarjetas'
        );
    }
}
