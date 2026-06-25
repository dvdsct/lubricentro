<?php

namespace App\Livewire;

use App\Models\Vehiculo;
use App\Models\Orden;
use App\Models\Presupuesto;
use Livewire\Component;

class VehicleProfile extends Component
{
    public Vehiculo $vehiculo;
    public $vehicleOrders;
    public $vehiclePresupuestos;

    public function mount(Vehiculo $vehiculo)
    {
        $this->vehiculo = $vehiculo->load(['modelos.marcas', 'clientes.perfiles.personas']);
        $this->vehicleOrders = Orden::where('vehiculo_id', $this->vehiculo->id)
            ->orderByDesc('created_at')
            ->get();
        $this->vehiclePresupuestos = Presupuesto::where('vehiculo_id', $this->vehiculo->id)
            ->orderByDesc('created_at')
            ->get();
    }

    public function render()
    {
        return view('livewire.vehicle-profile')
            ->layout('components.layouts.page', [
                'title' => 'Perfil de Vehículo - Rocket',
                'header' => 'PERFIL DE VEHÍCULO',
            ]);
    }
}
