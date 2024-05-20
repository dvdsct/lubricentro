<?php

namespace App\Livewire;

use App\Models\Orden;
use Livewire\Component;
use App\Models\Producto;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;

class ViewTurnos extends Component
{
    public $turnlav;
    public $headslav = ['vehiculo', 'motivo', 'encargado'];
    public $turnlub;
    public $headslub = [];

    public $ordenes;
    public $fecha;


    public function mount()
    {
        $this->fecha = Carbon::now()->format('Y-m-d');
    }

    public function openModal()
    {

        $this->dispatch('modal-order')->to(FormCreateOrder::class);
    }

    public function change_day($day)
    {
        if ($day == 'yes') {
            $this->fecha = Carbon::parse($this->fecha)->subDay()->format('Y-m-d');
        }
        if ($day == 'tmw') {
            $this->fecha = Carbon::parse($this->fecha)->addDay()->format('Y-m-d');
        }
    }



    #[On('added-turn')]
    public function render()
    {
        $this->turnlav = Orden::select(
            'ordens.*',
            'clientes.id as cliente_id',
            'perfils.id as perfil_id',
            'personas.id as persona_id',
            'personas.apellido',
            'personas.DNI'
        )
            ->leftJoin('clientes', 'ordens.cliente_id', '=', 'clientes.id')
            ->leftJoin('perfils', 'clientes.perfil_id', '=', 'perfils.id')
            ->leftJoin('personas', 'perfils.persona_id', '=', 'personas.id')
            ->whereColumn('perfils.id', 'clientes.perfil_id')
            ->whereColumn('personas.id', 'perfils.persona_id')
            ->whereDate('ordens.created_at', $this->fecha)
            ->where('motivo', '1')
            ->where('ordens.estado', '!=', '555')
            ->get();

        $this->turnlub = Orden::select(
            'ordens.*',
            'clientes.id as cliente_id',
            'perfils.id as perfil_id',
            'personas.id as persona_id',
            'personas.apellido',
            'personas.DNI'
        )
            ->leftJoin('clientes', 'ordens.cliente_id', '=', 'clientes.id')
            ->leftJoin('perfils', 'clientes.perfil_id', '=', 'perfils.id')
            ->leftJoin('personas', 'perfils.persona_id', '=', 'personas.id')
            ->whereColumn('perfils.id', 'clientes.perfil_id')
            ->whereColumn('personas.id', 'perfils.persona_id')
            ->whereDate('ordens.created_at', $this->fecha)
            ->where('motivo', '2')
            ->where('ordens.estado', '!=', '555')
            ->get();

        return view('livewire.view-turnos');
    }
}
