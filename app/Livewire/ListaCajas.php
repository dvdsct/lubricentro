<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\Perfil;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ListaCajas extends Component
{
    public $cajas;
    public $caja;
    public $cajero;
    public $perfil;
    public $modalAbrirCaja;

    public function mount(){

        $this->caja = Caja::where('estado','200')->get();
        $this->perfil = Perfil::where('user_id',Auth::user()->id)->get();

        if($this->caja){
            $this->modalAbrirCaja = true;

        }
    }



    public function abrirCaja(){



        // Caja Estado 200 es una caja abierta
        $this->cajero = $this->perfil->first()->cajeros->first();
        // dd($this->cajero);

        if (Auth::user()->hasRole(['cajero', 'admin'])) {
            $this->caja =  Caja::firstOrCreate([
                'cajero_id' => $this->cajero->id,
                'estado' => '200'

            ]);;
        }

        redirect('venta/'.$this->caja->id);




    }
    public function render()
    {
        $this->cajas = Caja::all();

        return view('livewire.lista-cajas');
    }
}
