<?php

namespace App\Livewire;

use App\Models\OrdenPago;
use App\Models\Proveedor;
use Livewire\Component;
use Livewire\WithPagination;

class OrdenesPagoList extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';

    public $query = '';
    public $filtroEstado = '';
    public $filtroProveedor = '';

    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function updatedFiltroEstado()
    {
        $this->resetPage();
    }

    public function updatedFiltroProveedor()
    {
        $this->resetPage();
    }

    public function render()
    {
        $ordenes = OrdenPago::with(['proveedor.perfiles.personas', 'autorizadoPor', 'usuarioCreador'])
            ->when(trim($this->query) !== '', function ($q) {
                $term = '%' . trim($this->query) . '%';
                $q->where(function ($sub) use ($term) {
                    $sub->where('numero', 'like', $term)
                        ->orWhere('observaciones', 'like', $term)
                        ->orWhereHas('proveedor', function ($prov) use ($term) {
                            $prov->where('nombre_fantasia', 'like', $term)
                                ->orWhere('cuit', 'like', $term)
                                ->orWhereHas('perfiles.personas', function ($per) use ($term) {
                                    $per->where('nombre', 'like', $term)
                                        ->orWhere('apellido', 'like', $term);
                                });
                        });
                });
            })
            ->when($this->filtroEstado !== '', function ($q) {
                $q->where('estado', $this->filtroEstado);
            })
            ->when($this->filtroProveedor !== '', function ($q) {
                $q->where('proveedor_id', $this->filtroProveedor);
            })
            ->orderByDesc('id')
            ->paginate(12);

        $proveedores = Proveedor::with('perfiles.personas')->get();

        return view('livewire.ordenes-pago-list', [
            'ordenes' => $ordenes,
            'proveedores' => $proveedores,
        ]);
    }
}
