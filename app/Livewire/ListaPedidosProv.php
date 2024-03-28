<?php

namespace App\Livewire;

use App\Models\PedidoProveedor;
use Livewire\Component;
use Livewire\WithPagination;

class ListaPedidosProv extends Component
{
    use WithPagination;
    public $query = ' ';

    public function search()
    {
        // $this->resetPage();
    }
    public function render()
    {
        return view('livewire.lista-pedidos-prov', [

            'pedidos' => PedidoProveedor::select(
                'pedido_proveedors.*',
                'proveedors.id as proveedor_id',
                'proveedors.cuit as cuit',
                'perfils.id as perfil_id',
                'personas.nombre',
                'personas.apellido'
            )
                ->leftjoin('proveedors', 'pedido_proveedors.proveedor_id', '=', 'proveedors.id')
                ->leftjoin('perfils', 'proveedors.perfil_id', '=', 'perfils.id')
                ->leftjoin('personas', 'perfils.persona_id', '=', 'personas.id')
                ->where('nombre', 'like', '%' . $this->query . '%')
                ->orWhere('apellido', 'like', '%' . $this->query . '%')
                ->orWhere('cuit', 'like', '%' . $this->query . '%')
                ->orWhere('pedido_proveedors.descripcion', 'like', '%' . $this->query . '%')
                ->paginate(10)
        ]);
    }
}
