<?php

namespace App\Livewire;

use App\Models\Plan;
use App\Models\PlanXTarjeta;
use App\Models\Tarjeta;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ListTarjetas extends Component
{
    public $tarjetas;
    public $tarjeta;
    public $planes;

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

        $plan = Plan::find($id);
        if (Auth::user()->hasRole('admin')) {

            $plan->update([
                'descuento' => $this->descuento,
                'interes' => $this->interes,
                'estado' => '1'
            ]);
        }
    }



    public function editTarjeta($id)
    {

        $plan = Plan::find($id);

        $plan->update([
            'estado' => '2'
        ]);
        $this->descuento = $plan->descuento;
        $this->interes = $plan->interes;
    }



    public function render()
    {
        $this->planes = Plan::all();

        return view(
            'livewire.list-tarjetas'
        );
    }
}
