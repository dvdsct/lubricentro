<?php

namespace App\Livewire;

use App\Models\MarcaVehiculo;
use App\Models\ModeloVehiculo;
use App\Models\Orden;
use App\Models\Presupuesto;
use App\Models\TipoVehiculo;
use App\Models\Vehiculo;
use Livewire\Component;

class VehicleProfile extends Component
{
    public Vehiculo $vehiculo;
    public $vehicleOrders;
    public $vehiclePresupuestos;

    public $showEditModal = false;

    public $dominio;
    public $color;
    public $version;
    public $año;
    public $marca_vehiculo_id;
    public $modelo_vehiculo_id;
    public $tipo_vehiculo_id;

    public $marcas = [];
    public $modelos = [];
    public $tipos = [];

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

    public function openEditModal()
    {
        $this->dominio = $this->vehiculo->dominio ?? '';
        $this->color = $this->vehiculo->color ?? '';
        $this->version = $this->vehiculo->version ?? '';
        $this->año = $this->vehiculo->año ?? '';
        $this->tipo_vehiculo_id = $this->vehiculo->tipo_vehiculo_id ?? null;
        $this->modelo_vehiculo_id = $this->vehiculo->modelo_vehiculo_id ?? null;

        $this->marca_vehiculo_id = $this->vehiculo->marca_vehiculo_id
            ?: (optional($this->vehiculo->modelos)->marca_vehiculo_id ?? null);

        $this->marcas = MarcaVehiculo::orderBy('descripcion')->get();
        $this->tipos = TipoVehiculo::all();

        if ($this->marca_vehiculo_id) {
            $this->modelos = ModeloVehiculo::where('marca_vehiculo_id', $this->marca_vehiculo_id)
                ->orderBy('descripcion')
                ->get();
        } else {
            $this->modelos = collect();
        }

        $this->resetValidation();
        $this->showEditModal = true;
    }

    public function updatedMarcaVehiculoId($value)
    {
        if ($value) {
            $this->modelos = ModeloVehiculo::where('marca_vehiculo_id', $value)
                ->orderBy('descripcion')
                ->get();
        } else {
            $this->modelos = collect();
        }
        $this->modelo_vehiculo_id = null;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetValidation();
    }

    public function updateVehicle()
    {
        $this->validate([
            'dominio' => 'required|string|max:50|unique:vehiculos,dominio,' . $this->vehiculo->id,
            'color' => 'nullable|string|max:50',
            'version' => 'nullable|string|max:100',
            'año' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'marca_vehiculo_id' => 'nullable|exists:marca_vehiculos,id',
            'modelo_vehiculo_id' => 'nullable|exists:modelo_vehiculos,id',
            'tipo_vehiculo_id' => 'nullable|exists:tipo_vehiculos,id',
        ], [
            'dominio.required' => 'La patente / dominio es obligatoria.',
            'dominio.unique' => 'Esta patente ya pertenece a otro vehículo.',
            'año.integer' => 'El año debe ser un número entero.',
            'año.min' => 'El año debe ser mayor a 1900.',
            'año.max' => 'El año no puede ser superior a ' . (date('Y') + 1) . '.',
        ]);

        $this->vehiculo->update([
            'dominio' => strtoupper(trim($this->dominio)),
            'color' => $this->color ?: null,
            'version' => $this->version ?: null,
            'año' => $this->año ?: null,
            'marca_vehiculo_id' => $this->marca_vehiculo_id ?: null,
            'modelo_vehiculo_id' => $this->modelo_vehiculo_id ?: null,
            'tipo_vehiculo_id' => $this->tipo_vehiculo_id ?: null,
        ]);

        $this->vehiculo->load(['modelos.marcas', 'clientes.perfiles.personas']);
        $this->showEditModal = false;

        session()->flash('message', 'Datos del vehículo actualizados correctamente.');
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
