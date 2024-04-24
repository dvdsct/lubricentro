<?php

namespace App\Livewire;

use App\Models\Tarjeta;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ListTarjetas extends Component
{
    public $tarjetas;
  

    public function mount(){
        $this->tarjetas = Tarjeta::all();
    }



    public function render()
    {
        return view('livewire.list-tarjetas');
    }
}
