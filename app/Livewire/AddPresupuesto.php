<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\Colores;
use App\Models\MarcaVehiculo;
use App\Models\Perfil;
use App\Models\Persona;
use App\Models\Presupuesto;
use App\Models\PresupuestoItem;
use App\Models\Producto;
use App\Models\Stock;
use App\Models\TipoVehiculo;
use App\Models\Vehiculo;
use App\Models\VehiculosXCliente;
use Livewire\Attributes\On;
use Livewire\Component;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonInterface;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;

// Establecer el idioma a español
Carbon::setLocale('es');


class AddPresupuesto extends Component
{
    public $clientes;
    public $cliente;
    public $modal;

    public $presupuesto;

    #[Locked]
    public $formperson = false;
    #[Locked]
    public $formVehiculo = false;


    #[Validate('required', message: 'Debe ingresar el Nombre!')]
    public $nombre;
    #[Validate('required', message: 'Debe ingresar el Apellido!')]
    public $apellido;
    public $fecha_nac;
    public $dni;
    // Nuevo: número de teléfono (solo dígitos, opcional)
    #[Validate('nullable|regex:/^\\d+$/', message: 'El número de teléfono debe contener solo dígitos.', translate: false)]
    public $numero_telefono;


    // del cliente
    public $persona;
    public $vehiculo;
    public $vehiculos = [];


    public function mount()
    {
        $this->clientes = Cliente::all();
        // Vehículo: catálogos
        $this->tiposVehiculo = TipoVehiculo::all();
        $this->marcas = [];
        $this->modelos = [];
        $this->colores = Colores::all();
    }

    public function upPerson()
    {
        $this->cliente = Cliente::find($this->cliente);
        // dd($this->cliente);

        $this->nombre = $this->cliente->perfiles->personas->nombre ?? '';
        $this->apellido = $this->cliente->perfiles->personas->apellido ?? '';
        $this->dni = $this->cliente->perfiles->personas->DNI ?? '';
        $this->fecha_nac = $this->cliente->perfiles->personas->fecha_nac ?? '';
        $this->numero_telefono = $this->cliente->perfiles->personas->numero_telefono ?? '';

        // cargar vehículos existentes del cliente
        $this->vehiculos = $this->cliente->vehiculos ?? [];
    }


    public function formPerson()
    {
        if ($this->formperson == true) {

            $this->formperson = false;
        } else {
            if ($this->cliente != null) {

                $this->reset('cliente', 'nombre', 'apellido', 'dni', 'fecha_nac', 'numero_telefono');
                $this->formperson = false;
            } else {
                $this->formperson = true;
            }
        }
    }

    // ================= Vehículo (similar a Turnos) =================
    public $tiposVehiculo;
    public $tipo;
    public $marcas;
    public $marca;
    public $modelos;
    public $modelo;
    public $colores;
    public $color;
    public $dominio;
    public $version;
    public $año;
    public $selecedtVehiculo = false;

    public function setFormVehiculo()
    {
        if ($this->formVehiculo == true) {
            if ($this->selecedtVehiculo == true) {
                $this->selecedtVehiculo = false;
                $this->reset('vehiculo');
            }
            $this->formVehiculo = false;
        } else {
            $this->formVehiculo = true;
        }
    }

    public function upMarcas()
    {
        $this->marcas = MarcaVehiculo::where('tipo_vehiculo_id', $this->tipo)->get();
        $this->modelos = [];
        $this->modelo = null;
    }

    public function upModelos()
    {
        $this->modelos = \App\Models\ModeloVehiculo::where('marca_vehiculo_id', $this->marca)
            ->where('tipo_vehiculo_id', $this->tipo)
            ->get();
    }

    public function addVehicle()
    {
        // Validaciones mínimas para poder crear el vehículo
        $this->validate([
            'tipo' => 'required',
            'marca' => 'required',
            'modelo' => 'required',
        ], [
            'tipo.required' => 'Seleccione el tipo de vehículo',
            'marca.required' => 'Seleccione la marca',
            'modelo.required' => 'Seleccione el modelo',
        ]);

        // Normalizar dominio (si existe)
        $dominio = $this->dominio ? strtoupper(trim($this->dominio)) : null;

        if ($dominio) {
            // Identificar por dominio únicamente para evitar colisiones por otros campos
            $this->vehiculo = Vehiculo::firstOrCreate(
                ['dominio' => $dominio],
                [
                    'modelo_vehiculo_id' => $this->modelo,
                    'color' => $this->color ?: null,
                    'version' => $this->version ?: null,
                    'año' => $this->año ?: null,
                    'estado' => 1,
                ]
            );
        } else {
            // Crear siempre un nuevo vehículo si no hay dominio para no colapsar registros
            $this->vehiculo = Vehiculo::create([
                'modelo_vehiculo_id' => $this->modelo,
                'dominio' => null,
                'color' => $this->color ?: null,
                'version' => $this->version ?: null,
                'año' => $this->año ?: null,
                'estado' => 1,
            ]);
        }

        VehiculosXCliente::firstOrCreate([
            'cliente_id' => $this->cliente->id,
            'vehiculo_id' => $this->vehiculo->id
        ]);

        $this->vehiculo = $this->vehiculo->id;
        // refresh vehicles list for the selector
        $this->vehiculos = Cliente::find($this->cliente->id)->vehiculos()->get();
        $this->formVehiculo = false;
        $this->selecedtVehiculo = true;
    }

    public function selectVehiculo()
    {
        // cuando se elige desde el selector existente
        if ($this->vehiculo) {
            $this->selecedtVehiculo = true;
            $this->formVehiculo = false;
        }
    }

    public function refreshVehiculos()
    {
        if ($this->cliente) {
            // fuerza recarga desde base
            $this->vehiculos = Cliente::find($this->cliente->id)->vehiculos()->get();
        }
    }


    public function addClient()
    {
        if (Auth::user()->hasRole(['cajero', 'admin'])) {


            $this->validate();

            $persona = Persona::create([
                'nombre' => $this->nombre,
                'apellido' => $this->apellido,
                'DNI' => $this->dni,
                'numero_telefono' => $this->numero_telefono,
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

        } else {
            return    abort(404);
        }
    }

    public function continueForm()
    {

        // si $vehiculo es objeto, usar su id
        $vehiculoId = is_object($this->vehiculo) ? ($this->vehiculo->id ?? null) : $this->vehiculo;

        $this->presupuesto = Presupuesto::create([
            'cliente_id' => $this->cliente->id,
            'vehiculo_id' => $vehiculoId,
            'estado' => '1'
        ]);
        $this->modalOnOff();
        redirect('presupuesto/'. $this->presupuesto->id);
    }

    #[On('addPresupuesto')]
    public function modalOnOff()
    {
        if ($this->modal == true) {
            $this->modal = false;
        } else {

            $this->modal = true;
        }
    }


    public function render()
    {
        return view('livewire.add-presupuesto');
    }
}
