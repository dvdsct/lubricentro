<?php

namespace App\Livewire;

use App\Models\Orden;
use App\Models\Cliente;
use App\Models\Vehiculo;
use Livewire\Component;
use Livewire\WithPagination;

class Clientes extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $q = '';
    public $perPage = 10;
    public $term = '';
    public $filterBy = 'all'; // all|patente|apellido|dni

    // Navigation and Profile properties
    public $viewState = 'list'; // list|client_profile|vehicle_profile
    public $selectedClientId = null;
    public $selectedVehicleId = null;

    public function updatedQ()
    {
        $this->resetPage();
    }

    public function updatedFilterBy()
    {
        $this->resetPage();
    }

    public function search()
    {
        $this->term = trim($this->q);
        $this->resetPage();
    }

    public function clear()
    {
        $this->q = '';
        $this->term = '';
        $this->resetPage();
    }

    public function showClientProfile($clientId)
    {
        $this->selectedClientId = $clientId;
        $this->selectedVehicleId = null;
        $this->viewState = 'client_profile';
    }

    public function showVehicleProfile($vehicleId)
    {
        $this->selectedVehicleId = $vehicleId;
        $this->viewState = 'vehicle_profile';
    }

    public function goBack()
    {
        if ($this->viewState === 'vehicle_profile' && $this->selectedClientId) {
            $this->viewState = 'client_profile';
            $this->selectedVehicleId = null;
        } else {
            $this->viewState = 'list';
            $this->selectedClientId = null;
            $this->selectedVehicleId = null;
        }
    }

    public function render()
    {
        $client = null;
        $vehicle = null;
        $vehicleOrders = null;
        $orders = null;

        if ($this->viewState === 'client_profile') {
            $client = Cliente::with(['perfiles.personas', 'vehiculos.modelos.marcas'])->find($this->selectedClientId);
        } elseif ($this->viewState === 'vehicle_profile') {
            $vehicle = Vehiculo::with(['modelos.marcas', 'clientes.perfiles.personas'])->find($this->selectedVehicleId);
            $vehicleOrders = Orden::where('vehiculo_id', $this->selectedVehicleId)
                ->orderByDesc('created_at')
                ->get();
        } else {
            $filterValue = strtolower((string) $this->filterBy);
            $orders = Orden::with([
                    'clientes.perfiles.personas',
                    'vehiculos.modelos.marcas',
                ])
                ->when(($t = trim($this->term)) !== '', function ($query) use ($t, $filterValue) {
                    $term = '%' . $t . '%';
                    $query->where(function ($w) use ($term, $filterValue) {
                        if ($filterValue === 'patente') {
                            $w->whereHas('vehiculos', function ($vv) use ($term) {
                                $vv->where('dominio', 'like', $term);
                            });
                        } elseif ($filterValue === 'dni') {
                            $w->whereHas('clientes', function ($c) use ($term) {
                                $c->whereHas('perfiles', function ($pf) use ($term) {
                                    $pf->whereHas('personas', function ($p) use ($term) {
                                        $p->where('DNI', 'like', $term);
                                    });
                                });
                            });
                        } elseif ($filterValue === 'apellido') {
                            $w->whereHas('clientes', function ($c) use ($term) {
                                $c->whereHas('perfiles', function ($pf) use ($term) {
                                    $pf->whereHas('personas', function ($p) use ($term) {
                                        $p->where('apellido', 'like', $term);
                                    });
                                });
                            });
                        } else { // all
                            $w->whereHas('clientes', function ($c) use ($term) {
                                $c->whereHas('perfiles', function ($pf) use ($term) {
                                    $pf->whereHas('personas', function ($p) use ($term) {
                                        $p->where('nombre', 'like', $term)
                                          ->orWhere('apellido', 'like', $term)
                                          ->orWhere('DNI', 'like', $term);
                                    });
                                });
                            })
                            ->orWhereHas('vehiculos', function ($vv) use ($term) {
                                $vv->where('dominio', 'like', $term);
                            });
                        }
                    });
                })
                ->orderByDesc('created_at')
                ->paginate($this->perPage);
        }

        return view('livewire.clientes', [
            'orders' => $orders,
            'client' => $client,
            'vehicle' => $vehicle,
            'vehicleOrders' => $vehicleOrders,
        ])->layout('components.layouts.page', [
            'title' => 'Clientes - Rocket',
            'header' => 'CLIENTES',
        ]);
    }
}
