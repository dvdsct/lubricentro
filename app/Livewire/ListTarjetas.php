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

        // Propiedad para controlar el estado del modal
        public $modal = false;
        public $editing = false;
        public $editPlanId = null;

    // Campos del formulario de creación
    #[Validate('required', message: 'Ingrese el nombre de la tarjeta')]
    public $tarjetaNombre;

    #[Validate('required', message: 'Ingrese un valor')]
    public $nuevoDescuento;

    #[Validate('required', message: 'Ingrese un valor')]
    public $nuevoInteres;

    #[Validate('required', message: 'Ingrese el nombre del plan')]
    public $nuevoNombrePlan;

    #[Validate('required', message: 'Ingrese la descripción o cuotas')]
    public $nuevoCuotas;


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


    public function delTarjeta($id)
    {
        Plan::find($id)->delete();
    }
    

    // Método para abrir el modal
    public function abrirModal()
    {
        $this->reset(
            'tarjetaNombre',
            'nuevoDescuento',
            'nuevoInteres',
            'nuevoNombrePlan',
            'nuevoCuotas'
        );
        $this->editing = false;
        $this->editPlanId = null;
        $this->modal = true;
    }

    // Método para cerrar el modal
    public function cerrarModal()
    {
        $this->modal = false;
    }

    public function crearTarjetaPlan()
    {
        // Solo admin puede crear
        if (!Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $this->validate([
            'tarjetaNombre' => 'required|string',
            'nuevoDescuento' => 'required|numeric',
            'nuevoInteres' => 'required|numeric',
            'nuevoNombrePlan' => 'required|string',
            'nuevoCuotas' => 'required|string',
        ]);

        // Crear o recuperar tarjeta
        $tarjeta = Tarjeta::firstOrCreate(
            ['nombre_tarjeta' => $this->tarjetaNombre],
            // Solo establecer columnas existentes en la tabla tarjetas
            ['estado' => '1']
        );

        // Crear plan asociado
        Plan::create([
            'tarjeta_id' => $tarjeta->id,
            'nombre_plan' => $this->nuevoNombrePlan,
            'descripcion_plan' => $this->nuevoCuotas,
            'estado' => '1',
            'interes' => $this->nuevoInteres,
            'descuento' => $this->nuevoDescuento,
        ]);

        // Refrescar listas y cerrar modal
        $this->planes = Plan::all();
        $this->tarjetas = Tarjeta::all();
        $this->cerrarModal();
    }

    public function editarPlan($id)
    {
        $plan = Plan::with('tarjetas')->find($id);
        if (!$plan) return;

        $this->tarjetaNombre = optional($plan->tarjetas)->nombre_tarjeta;
        $this->nuevoDescuento = $plan->descuento;
        $this->nuevoInteres = $plan->interes;
        $this->nuevoNombrePlan = $plan->nombre_plan;
        $this->nuevoCuotas = $plan->descripcion_plan;

        $this->editing = true;
        $this->editPlanId = $id;
        $this->modal = true;
    }

    public function guardarEdicion()
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403);
        }
        $this->validate([
            'nuevoDescuento' => 'required|numeric',
            'nuevoInteres' => 'required|numeric',
            'nuevoNombrePlan' => 'required|string',
            'nuevoCuotas' => 'required|string',
        ]);

        $plan = Plan::find($this->editPlanId);
        if ($plan) {
            $plan->update([
                'nombre_plan' => $this->nuevoNombrePlan,
                'descripcion_plan' => $this->nuevoCuotas,
                'interes' => $this->nuevoInteres,
                'descuento' => $this->nuevoDescuento,
            ]);
        }

        $this->planes = Plan::all();
        $this->editing = false;
        $this->editPlanId = null;
        $this->cerrarModal();
    }


    public function render()
    {
        $this->planes = Plan::all();

        return view(
            'livewire.list-tarjetas'
        );
    }
}
