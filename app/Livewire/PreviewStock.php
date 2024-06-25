<?php

namespace App\Livewire;

use Livewire\WithPagination;
use App\Models\Stock;
use Livewire\Component;

class PreviewStock extends Component
{

    use WithPagination;



    public $cantidad;
    public $query = '';

    public function search()
    {
        $this->resetPage();
    }

    public function addCantidad($id)
    {
        $p = Stock::find($id);
        $p->update(
            [
                'estado' => '1',
                'cantidad' => $this->cantidad
            ]
        );

        // $this->render();

    }
    public function editPStock($id)
    {
        // dd('s');
        $p = Stock::find($id);
        $this->cantidad = $p->cantidad;

        $p->update(
            [
                'estado' => '2',
            ]
        );
    }

    // public $stock;
    public function render()
    {
        return view(
            'livewire.preview-stock',
            [
                'stock' => Stock::select(
                    'stocks.*',
                    'productos.codigo',
                    'productos.descripcion as descripcion'
                )
                    ->leftJoin('productos', 'stocks.producto_id', '=', 'productos.id')


                    ->where('descripcion', 'like', '%' . $this->query . '%')
                    ->orWhere('codigo', 'like', '%' . $this->query . '%')
                    ->paginate(10),
            ]
        );
    }
}
