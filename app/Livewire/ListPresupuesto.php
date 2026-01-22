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
    public $filterBy = 'all'; // all|patente|nombre|apellido|dni

    public function mount(){
        // no-op: evitar almacenar paginadores en propiedades públicas
    }

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
        $filterValue = strtolower((string) $this->filterBy);
        $presupuestos = Presupuesto::with(['clientes.perfiles.personas', 'itemspres', 'vehiculos'])
            ->when(($t = trim($this->q)) !== '', function ($query) use ($t, $filterValue) {
                $term = '%' . $t . '%';
                $query->where(function ($qq) use ($term, $filterValue) {
                    if ($filterValue === 'patente') {
                        $qq->whereHas('vehiculos', function ($wv) use ($term) {
                            $wv->where('dominio', 'like', $term);
                        });
                    } elseif ($filterValue === 'nombre') {
                        $qq->whereHas('clientes', function ($c) use ($term) {
                            $c->whereHas('perfiles', function ($pf) use ($term) {
                                $pf->whereHas('personas', function ($p) use ($term) {
                                    $p->where('nombre', 'like', $term);
                                });
                            });
                        });
                    } elseif ($filterValue === 'apellido') {
                        $qq->whereHas('clientes', function ($c) use ($term) {
                            $c->whereHas('perfiles', function ($pf) use ($term) {
                                $pf->whereHas('personas', function ($p) use ($term) {
                                    $p->where('apellido', 'like', $term);
                                });
                            });
                        });
                    } elseif ($filterValue === 'dni') {
                        $qq->whereHas('clientes', function ($c) use ($term) {
                            $c->whereHas('perfiles', function ($pf) use ($term) {
                                $pf->whereHas('personas', function ($p) use ($term) {
                                    $p->where('DNI', 'like', $term);
                                });
                            });
                        });
                    } else { // all
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
                    }
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.list-presupuesto', [
            'presupuestos' => $presupuestos
        ]);
    }
}
