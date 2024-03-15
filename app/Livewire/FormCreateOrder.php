<?php

namespace App\Livewire;




use App\Models\Vehiculo;
use App\Models\Perfil;
use App\Models\Persona;
use App\Models\Cliente;
use App\Models\Orden;
use App\Models\Servicio;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class FormCreateOrder extends Component
{
    // de la vista
    public $modal;
    public $clientes;
    public $servicios;
    public $formperson = false;

    // de la Orden
    public $cliente;
    public $servicio;
    public $vehiculos;
    public $fecha;
    public $motivo;

    // del cliente
    public $vehiculo;
    public $nombre;
    public $apellido;
    public $fecha_nac;
    public $dni;

    public function mount()
    {
        $this->clientes = Cliente::all();
        $this->servicios = Servicio::all();
        // $this->cliente = 'ok';
    }

    public function up()
    {
    }

    public function upPerson()
    {
        $this->cliente = Cliente::find($this->cliente);

        $this->formperson = true;
        $this->nombre = $this->cliente->perfiles->personas->nombre;
        $this->apellido = $this->cliente->perfiles->personas->apellido;
        $this->dni = $this->cliente->perfiles->personas->DNI;
        $this->fecha_nac = $this->cliente->perfiles->personas->fecha_nac;



        $this->vehiculos = $this->cliente->vehiculos;


        // dd($this->nombre);

    }



    public function addTurno()
    {

        $persona = Persona::create([
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'DNI' => $this->dni,
            'fecha_nac' => $this->fecha_nac,
            'estado' => '1'
        ]);

/*         $perfil = Perfil::create([
        'persona_id'=>$persona->id,
        ]); */

        $cliente = Cliente::create([
            'perfil_id' => Perfil::create(['persona_id' => $persona->id])->id
        ]);

        $vehiculo = Vehiculo::create([
            'dominio' => $this->dominio,
            'color' => $this->color,
            'año' => $this->año,
            'version' => $this->version,
            'estado' => '1'
        ]);

        Orden::create([

            'servicio_id' => $this->servicio,
            'cliente_id' => $this->cliente->id,
            'vehiculo_id' => $this->vehiculo,
            'motivo' => $this->motivo,
            'horario' => Carbon::now(),
            'estado' => '1'
        ]);


        $this->reset();
        $this->dispatch('added-turn');

    }



    public function formPerson()
    {
        if ($this->formperson == true) {

            $this->formperson = false;
        } else {
            $this->formperson = true;
        }
    }

    #[On('modal-order')]
    public function openModal()
    {

        $this->modal = true;
    }

    public function closeModal()
    {
        $this->modal = false;
        $this->formperson = false;

        // $this->reset('nombre');
    }




    public function render()
    {
        return view('livewire.form-create-order');
    }
}
