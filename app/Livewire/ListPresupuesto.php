<?php

namespace App\Livewire;

use App\Models\Presupuesto;
use Livewire\Component;
use Livewire\WithPagination;

class ListPresupuesto extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $q = '';

    public function mount(){
        // no-op: evitar almacenar paginadores en propiedades públicas
    }

    public function updatedQ()
    {
        $this->resetPage();
    }

    public function search()
    {
        $this->q = trim((string) $this->q);
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->q = '';
        $this->resetPage();
    }

    public function render()
    {
        $presupuestos = Presupuesto::with(['clientes.perfiles.personas', 'itemspres', 'vehiculos'])
            ->when(trim($this->q) !== '', function ($query) {
                $term = '%' . trim($this->q) . '%';
                $query->where(function ($qq) use ($term) {
                    // Coincidencias por cliente (nombre, apellido, DNI)
                    $qq->whereHas('clientes', function ($c) use ($term) {
                        $c->whereHas('perfiles', function ($pf) use ($term) {
                            $pf->whereHas('personas', function ($p) use ($term) {
                                $p->where('nombre', 'like', $term)
                                  ->orWhere('apellido', 'like', $term)
                                  ->orWhere('DNI', 'like', $term);
                            });
                        });
                    })
                    // O coincidencias por patente (vehículo dominio)
                    ->orWhereHas('vehiculos', function ($wv) use ($term) {
                        $wv->where('dominio', 'like', $term);
                    });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.list-presupuesto', [
            'presupuestos' => $presupuestos
        ]);
    }
}
