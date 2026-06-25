<?php

namespace App\Livewire;

use App\Models\Orden;
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

    public function render()
    {
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

        return view('livewire.clientes', [
            'orders' => $orders,
        ])->layout('components.layouts.page', [
            'title' => 'Clientes - Rocket',
            'header' => 'CLIENTES',
        ]);
    }
}
