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

    public function mount(){
        // no-op: evitar almacenar paginadores en propiedades públicas
    }

    public function render()
    {
        $presupuestos = Presupuesto::with(['clientes.perfiles.personas', 'itemspres'])
            ->orderBy('created_at','desc')
            ->paginate($this->perPage);

        return view('livewire.list-presupuesto', [
            'presupuestos' => $presupuestos
        ]);
    }
}
