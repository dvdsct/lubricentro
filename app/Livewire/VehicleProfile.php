<?php

namespace App\Livewire;

use App\Models\MarcaVehiculo;
use App\Models\ModeloVehiculo;
use App\Models\Orden;
use App\Models\OrdenTrabajo;
use App\Models\Presupuesto;
use App\Models\TipoVehiculo;
use App\Models\Vehiculo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VehicleProfile extends Component
{
    public Vehiculo $vehiculo;
    public $vehicleOrders;
    public $vehiclePresupuestos;
    public $ordenesTrabajo;

    public $showEditModal = false;
    public $showOrdenTrabajoModal = false;

    // Campos de edición de vehículo
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

    // Campos de la Orden de Trabajo (Mecánico)
    public $ot_fecha;
    public $ot_cliente_id;
    public $ot_cliente_nombre;
    public $ot_km_entrada = '';
    public $ot_km_proximo_service = '';
    public $ot_trabajo_realizar = 'Cambio de aceite y filtros / Service general';
    public $ot_tipo_aceite = '';
    public $ot_litros_aceite = '';
    public $ot_aceite_envasado = false;
    public $ot_aceite_suelto = false;
    public $ot_aceite_lavado = false;

    public $ot_filtro_aceite = true;
    public $ot_filtro_aceite_detalle = '';
    public $ot_filtro_combustible = false;
    public $ot_filtro_combustible_detalle = '';
    public $ot_filtro_aire = true;
    public $ot_filtro_aire_detalle = '';
    public $ot_filtro_habitaculo = false;
    public $ot_filtro_habitaculo_detalle = '';

    public $ot_controles = [
        'escaneo' => false,
        'reseteo' => false,
        'frenos' => false,
        't_delantero' => false,
        'escobillas' => false,
        'luces' => false,
        'liq_frenos' => false,
        'liq_refrig' => false,
    ];

    public $ot_observaciones = '';
    public $ot_recibir_producto = '';
    public $ot_operador_responsable = '';
    public $ot_control_final = false;

    public function mount(Vehiculo $vehiculo)
    {
        $this->vehiculo = $vehiculo->load(['modelos.marcas', 'clientes.perfiles.personas']);
        $this->vehicleOrders = Orden::where('vehiculo_id', $this->vehiculo->id)
            ->orderByDesc('created_at')
            ->get();
        $this->vehiclePresupuestos = Presupuesto::where('vehiculo_id', $this->vehiculo->id)
            ->orderByDesc('created_at')
            ->get();
        $this->ordenesTrabajo = OrdenTrabajo::where('vehiculo_id', $this->vehiculo->id)
            ->orderByDesc('id')
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

        $updateData = [
            'dominio' => strtoupper(trim($this->dominio)),
            'color' => $this->color ?: null,
            'version' => $this->version ?: null,
            'año' => $this->año ?: null,
            'modelo_vehiculo_id' => $this->modelo_vehiculo_id ?: null,
        ];
        if (\Illuminate\Support\Facades\Schema::hasColumn('vehiculos', 'marca_vehiculo_id')) {
            $updateData['marca_vehiculo_id'] = $this->marca_vehiculo_id ?: null;
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('vehiculos', 'tipo_vehiculo_id')) {
            $updateData['tipo_vehiculo_id'] = $this->tipo_vehiculo_id ?: null;
        }
        $this->vehiculo->update($updateData);

        $this->vehiculo->load(['modelos.marcas', 'clientes.perfiles.personas']);
        $this->showEditModal = false;

        session()->flash('message', 'Datos del vehículo actualizados correctamente.');
    }

    // GESTIÓN DE ORDEN DE TRABAJO
    public function openOrdenTrabajoModal()
    {
        $firstClient = $this->vehiculo->clientes->first();
        $this->ot_fecha = date('Y-m-d');
        $this->ot_cliente_id = $firstClient?->id;
        $this->ot_cliente_nombre = $firstClient
            ? trim(optional(optional($firstClient->perfiles)->personas)->nombre . ' ' . optional(optional($firstClient->perfiles)->personas)->apellido)
            : 'Consumidor Final';

        $this->ot_km_entrada = '';
        $this->ot_km_proximo_service = '';
        $this->ot_trabajo_realizar = 'Cambio de aceite y filtros / Service general';
        $this->ot_tipo_aceite = '';
        $this->ot_litros_aceite = '';
        $this->ot_aceite_envasado = false;
        $this->ot_aceite_suelto = false;
        $this->ot_aceite_lavado = false;

        $this->ot_filtro_aceite = true;
        $this->ot_filtro_aceite_detalle = '';
        $this->ot_filtro_combustible = false;
        $this->ot_filtro_combustible_detalle = '';
        $this->ot_filtro_aire = true;
        $this->ot_filtro_aire_detalle = '';
        $this->ot_filtro_habitaculo = false;
        $this->ot_filtro_habitaculo_detalle = '';

        $this->ot_controles = [
            'escaneo' => false,
            'reseteo' => false,
            'frenos' => false,
            't_delantero' => false,
            'escobillas' => false,
            'luces' => false,
            'liq_frenos' => false,
            'liq_refrig' => false,
        ];

        $this->ot_observaciones = '';
        $this->ot_recibir_producto = '';
        $this->ot_operador_responsable = Auth::user()->name ?? 'Mecánico';
        $this->ot_control_final = false;

        $this->resetValidation();
        $this->showOrdenTrabajoModal = true;
    }

    public function updatedOtKmEntrada($value)
    {
        $cleanKm = preg_replace('/[^0-9]/', '', (string)$value);
        if (is_numeric($cleanKm) && intval($cleanKm) > 0) {
            $this->ot_km_proximo_service = (string)(intval($cleanKm) + 10000);
        }
    }

    public function closeOrdenTrabajoModal()
    {
        $this->showOrdenTrabajoModal = false;
        $this->resetValidation();
    }

    public function saveOrdenTrabajo()
    {
        $this->validate([
            'ot_fecha' => 'required|date',
            'ot_km_entrada' => 'nullable|string|max:50',
            'ot_km_proximo_service' => 'nullable|string|max:50',
            'ot_tipo_aceite' => 'nullable|string|max:100',
            'ot_litros_aceite' => 'nullable|string|max:50',
            'ot_trabajo_realizar' => 'nullable|string|max:500',
        ], [
            'ot_fecha.required' => 'La fecha es obligatoria.',
        ]);

        $ot = OrdenTrabajo::create([
            'vehiculo_id' => $this->vehiculo->id,
            'cliente_id' => $this->ot_cliente_id ?: null,
            'user_id' => Auth::id(),
            'fecha' => $this->ot_fecha ?: date('Y-m-d'),
            'km_entrada' => $this->ot_km_entrada ?: null,
            'km_proximo_service' => $this->ot_km_proximo_service ?: null,
            'trabajo_realizar' => $this->ot_trabajo_realizar ?: null,
            'tipo_aceite' => $this->ot_tipo_aceite ?: null,
            'litros_aceite' => $this->ot_litros_aceite ?: null,
            'aceite_envasado' => (bool)$this->ot_aceite_envasado,
            'aceite_suelto' => (bool)$this->ot_aceite_suelto,
            'aceite_lavado' => (bool)$this->ot_aceite_lavado,
            'filtro_aceite' => (bool)$this->ot_filtro_aceite,
            'filtro_aceite_detalle' => $this->ot_filtro_aceite_detalle ?: null,
            'filtro_combustible' => (bool)$this->ot_filtro_combustible,
            'filtro_combustible_detalle' => $this->ot_filtro_combustible_detalle ?: null,
            'filtro_aire' => (bool)$this->ot_filtro_aire,
            'filtro_aire_detalle' => $this->ot_filtro_aire_detalle ?: null,
            'filtro_habitaculo' => (bool)$this->ot_filtro_habitaculo,
            'filtro_habitaculo_detalle' => $this->ot_filtro_habitaculo_detalle ?: null,
            'controles' => $this->ot_controles,
            'observaciones' => $this->ot_observaciones ?: null,
            'recibir_producto' => $this->ot_recibir_producto ?: null,
            'operador_responsable' => $this->ot_operador_responsable ?: null,
            'control_final' => (bool)$this->ot_control_final,
        ]);

        $this->ordenesTrabajo = OrdenTrabajo::where('vehiculo_id', $this->vehiculo->id)
            ->orderByDesc('id')
            ->get();

        $this->showOrdenTrabajoModal = false;
        session()->flash('message', 'Ficha de trabajo #' . $ot->id . ' guardada con éxito.');

        $this->dispatch('abrir-pdf', ['url' => route('pdf.orden-trabajo', $ot->id)]);
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
