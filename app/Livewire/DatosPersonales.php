<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class DatosPersonales extends Component
{
    public $persona;
    public $orden;
    public $modalDatos;

    public $dni;
    public $nombre;
    public $apellido;

    public function mount($orden,$persona){
        $this->orden = $orden;
        $this->persona = $persona;
        $this->dni = $this->persona->perfiles->personas->DNI;
        $this->nombre = $this->persona->perfiles->personas->nombre;
        $this->apellido = $this->persona->perfiles->personas->apellido;
    }


    

    #[On('modal-datosPac')]
    public function modalDatosOn()
    {
        if ($this->modalDatos) {
            $this->modalDatos = false;
        } else {
            $this->modalDatos = true;
        }
    }



    public function render()
    {

        // dd($this->orden);

        return view('livewire.datos-personales');
    }
}
