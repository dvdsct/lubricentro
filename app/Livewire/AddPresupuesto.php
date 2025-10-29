<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\Perfil;
use App\Models\Persona;
use App\Models\Presupuesto;
use App\Models\PresupuestoItem;
use App\Models\Producto;
use App\Models\Stock;
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


    public function mount()
    {
        $this->clientes = Cliente::all();
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

        $this->presupuesto = Presupuesto::create([
            'cliente_id' => $this->cliente->id,
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
