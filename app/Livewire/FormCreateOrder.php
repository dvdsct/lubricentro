<?php

namespace App\Livewire;

use App\Models\Colores;
use App\Models\ModeloVehiculo;
use App\Models\MarcaVehiculo;
use App\Models\TipoVehiculo;
use App\Models\Perfil;
use App\Models\Persona;
use App\Models\Cliente;
use App\Models\Orden;
use App\Models\Servicio;
use App\Models\Vehiculo;
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
    public $persona;
    public $vehiculo;
    public $nombre;
    public $apellido;
    public $fecha_nac;
    public $dni;

    //Select formperson==TRUE
    public $tipos_vehiculo;
    public $tipos;
    public $marcas;
    public $marca;
    public $modelos;
    public $modelo;
    public $colores;
    public $color;
    public $dominio;
    public $version;
    public $año;


    public function mount()
    {
        $this->clientes = Cliente::all();
        $this->servicios = Servicio::all();
        $this->tipos_vehiculo = TipoVehiculo::all();
        $this->marcas = MarcaVehiculo::all();
        $this->modelos = ModeloVehiculo::all();
        $this->colores = Colores::all();
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
            'estado' => 1
        ]);

/*         $perfil = Perfil::create([
        'persona_id'=>$persona->id,
        ]); */
        $perfil = Perfil::create([
              'persona_id'=> $persona->id
        ]);

        $nuevo_cliente = Cliente::create([
            'perfil_id' => $perfil->id
        ]);

        $vehiculo = Vehiculo::create([
            'tipo_vehiculo_id'=>$this->tipos,
            'modelo_vehiculo_id'=>$this->modelo,
            'marca_vehiculo_id'=>$this->marca,
            'dominio' => $this->dominio,
            'color' => $this->color,
            'version' => $this->version,
            'año' => $this->año,
            'estado' => 1
        ]);

        Orden::create([

            'servicio_id' => $this->servicio,
            'cliente_id' => $nuevo_cliente->id,
            'vehiculo_id' => $vehiculo->id,
            'motivo' => $this->motivo,
            'horario' => Carbon::now(),
            'estado' => '1'
        ]);


        $this->reset();
        $this->dispatch('added-turn');
        $this->formperson == false;


    }

    public function ();



    public function formPerson()
    {
        if ($this->formperson == true) {

            $this->formperson = false;

        } else {
         //   $this->vehiculos = false;
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
