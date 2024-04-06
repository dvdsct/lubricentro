<?php

namespace App\Livewire;

use Livewire\WithPagination;
use App\Models\Stock;
use Livewire\Component;

class PreviewStock extends Component
{

    use WithPagination;



    public $query = '';
    public $cantidad;

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
        // $this->stock = Stock::all();
        return view('livewire.preview-stock', [
            'stock' => Stock::select(
                'stocks.*',
                'productos.categoria_productos_id',
                'productos.proveedor_id',
                'proveedors.id as proveedor_id',
                'proveedors.perfil_id',
                'perfils.id as perfil_id',
                'personas.id as persona_id',
                'personas.nombre',
                'personas.DNI',
                'productos.descripcion as descripcion'
            )
                ->leftJoin('productos', 'stocks.producto_id', '=', 'productos.id')
                ->leftJoin('proveedors', 'productos.proveedor_id', '=', 'proveedors.id')
                ->leftJoin('perfils', 'proveedors.perfil_id', '=', 'perfils.id')
                ->leftJoin('personas', 'perfils.persona_id', '=', 'personas.id')


                ->where('descripcion', 'like', '%' . $this->query . '%')->paginate(10),
        ]);
    }
}
