<?php

namespace App\Livewire;

use App\Models\PedidoProveedor;
use Livewire\Component;
use Livewire\WithPagination;

class ListaPedidosProv extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public $query = '';

    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function search()
    {
        $this->resetPage();
    }

    public function delPedido($id)
    {
        $model = PedidoProveedor::find($id);
        if ($model) {
            $model->delete();
        }
    }

    public function render()
    {
        $pedidos = PedidoProveedor::with(['proveedores.perfiles.personas', 'tipos'])
            ->when(trim($this->query) !== '', function ($q) {
                $term = '%' . trim($this->query) . '%';
                $q->where(function ($sub) use ($term) {
                    $sub->where('pedido_proveedors.id', 'like', $term)
                        ->orWhere('pedido_proveedors.descripcion', 'like', $term)
                        ->orWhere('pedido_proveedors.observaciones', 'like', $term)
                        ->orWhereHas('proveedores', function ($provQuery) use ($term) {
                            $provQuery->where('nombre_fantasia', 'like', $term)
                                ->orWhere('cuit', 'like', $term)
                                ->orWhereHas('perfiles.personas', function ($personaQuery) use ($term) {
                                    $personaQuery->where('nombre', 'like', $term)
                                        ->orWhere('apellido', 'like', $term);
                                });
                        });
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.lista-pedidos-prov', [
            'pedidos' => $pedidos
        ]);
    }
}
