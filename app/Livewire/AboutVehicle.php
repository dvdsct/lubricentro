<?php

namespace App\Livewire;

use Livewire\Component;

class AboutVehicle extends Component
{
    public $orden;
    public $vehiculo;

    public function render()
    {

        $this->vehiculo = $this->orden->vehiculos;

        return view('livewire.about-vehicle');
    }
}
