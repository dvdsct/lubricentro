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
use App\Models\VehiculosXCliente;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class FormCreateOrder extends Component
{
    // de la vista
    public $modal;
    public $clientes;
    public $servicios;
    public $butt;
    public $act;
    public $selecedtVehiculo;
    public $btnLub = true;
    public $s_btnLub = 'btn-secondary';
    public $btnLav = false;
    public $s_btnLav = 'btn-secondary';

    #[Locked]
    public $formperson = false;

    #[Locked]
    public $formVehiculo = false;
    // de la Orden
    public $cliente;
    public $servicio;
    public $vehiculos;
    public $fecha;
    public $horario;
    public $motivo;

    // del cliente
    public $persona;
    public $vehiculo;

    #[Validate('required', message: 'Debe ingresar el Nombre!', translate: false)]
    public $nombre;
    #[Validate('required', message: 'Debe ingresar el Apellido!', translate: false)]
    public $apellido;
    public $fecha_nac;
    #[Validate('required', message: 'Debe ingresar el DNI!', translate: false)]
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


    public $query = '';



    public function mount()
    {
        $this->clientes = Cliente::all();

        $this->servicios = Servicio::all();
        $this->tipos_vehiculo = TipoVehiculo::all();
        $this->marcas = MarcaVehiculo::all();
        $this->modelos = ModeloVehiculo::all();
        $this->colores = Colores::all();
        $this->horario = Carbon::now()->format('H:i');
        // $this->cliente = 'ok';
    }


    public function search()
    {
        $this->resetPage();
    }




    public function up()
    {
    }

    public function upPerson()
    {
        $this->cliente = Cliente::find($this->cliente);
        // dd($this->cliente);

        $this->nombre = $this->cliente->perfiles->personas->nombre ?? '';
        $this->apellido = $this->cliente->perfiles->personas->apellido ?? '';
        $this->dni = $this->cliente->perfiles->personas->DNI ?? '';
        $this->fecha_nac = $this->cliente->perfiles->personas->fecha_nac ?? '';
    }

    public function addClient()
    {
        if (Auth::user()->hasRole(['cajero', 'admin'])) {


            $this->validate();


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
                'persona_id' => $persona->id
            ]);

            $this->cliente = Cliente::create([
                'perfil_id' => $perfil->id,
            ]);
            $this->formperson = false;
            $this->formVehiculo = true;
        } else {
            return    abort(404);
        }
    }

    public function setForm()
    {
        if ($this->formVehiculo == true) {
            if($this->selecedtVehiculo ==true){
                $this->selecedtVehiculo = false;
                $this->reset('vehiculo');
            }
            $this->formVehiculo = false;
        } else {
            $this->formVehiculo = true;
        }
    }
    public function setMot($mot)
    {
        if ($mot == 'lub') {
            $this->s_btnLub = 'bg-orange';
            $this->s_btnLav = 'btn-secondary';
            $this->btnLub = true;
            $this->btnLav = false;
        } else {
            // dd('lav');
            $this->s_btnLub = 'btn btn-secondary';
            $this->s_btnLav = 'btn btn-primary';
            $this->btnLub = false;
            $this->btnLav = true;
        }
    }

    public function addVehicle()
    {
        $this->vehiculo = Vehiculo::firstOrCreate([
            'modelo_vehiculo_id' => $this->modelo,
            'dominio' => $this->dominio,
            'color' => $this->color,
            'version' => $this->version,
            'año' => $this->año,
            'estado' => 1
        ]);

        VehiculosXCliente::firstOrCreate([
            'cliente_id' => $this->cliente->id,
            'vehiculo_id' => $this->vehiculo->id
        ]);

        $this->vehiculo = $this->vehiculo->id;
        $this->formVehiculo = false;

        $this->selectVehiculo();
    }

    public function selectVehiculo()
    {
        $this->vehiculo = Vehiculo::find($this->vehiculo);
        $this->selecedtVehiculo = true;
    }

    public function addTurno()
    {

        if (is_object($this->vehiculo)) {

            $this->vehiculo = $this->vehiculo->id;
        }
        if ($this->btnLav == true) {



            Orden::create([

                'cliente_id' => $this->cliente->id,
                'vehiculo_id' => $this->vehiculo,
                'motivo' => '1',
                'horario' => $this->horario,
                'estado' => '1'
            ]);
        } else {
            Orden::create([

                'cliente_id' => $this->cliente->id,
                'vehiculo_id' => $this->vehiculo,
                'motivo' => '2',
                'horario' => $this->horario,
                'estado' => '1'
            ]);
        }


        $this->dispatch('added-turn');
        $this->formperson == false;
        $this->closeModal();
    }



    public function formPerson()
    {
        if ($this->formperson == true) {

            $this->formperson = false;
        } else {
            if ($this->cliente != null) {

                $this->reset('cliente', 'nombre', 'apellido', 'dni', 'fecha_nac');
                $this->formperson = false;
            } else {
                $this->formperson = true;
            }
        }
    }


    #[On('modal-order')]
    public function openModal()
    {
        $this->modal = true;
    }

    public function closeModal()
    {
        $this->reset(
            'nombre',
            'apellido',
            'dni',
            'fecha_nac',
            'cliente',
            'vehiculo',
            'servicio',
            'motivo',
            'tipos',
            'modelo',
            'marca',
            'dominio',
            'color',
            'version',
            'selecedtVehiculo',
            'año',
            'btnLub',
            's_btnLub',
            'btnLav',
            's_btnLav',

        );
        $this->modal = false;
        $this->formperson = false;
        $this->horario = Carbon::now()->format('H:i');

        // $this->reset('nombre');
    }



    public function render()
    {
        return view('livewire.form-create-order');
    }
}
