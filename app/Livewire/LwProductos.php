<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Producto;
use App\Models\ProductoXProveedor;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class LwProductos extends Component
{
    use WithPagination;
    public $head = ['descripcion', 'costo'];
    public $list;
    public $producto;
    public $query ='';

    // public function mount(){

    //     $this->list = Producto::all();

    // }

    public function search(){
        $this->resetPage();
    }

    // #[On('added')]
    // public function oo()
    // {

    //     $this->list = Producto::all();
    //     // dd('here');
    // }



    public function delProd(string $id)
    {

        $item = Producto::find($id);
        $item->delete();
    }

    public function render()
    {
        return view('livewire.lw-productos',[

            'productos' => ProductoXProveedor::leftJoin('productos', 'producto_x_proveedors.producto_id', '=', 'productos.id')
            ->leftJoin('proveedors', 'producto_x_proveedors.proveedor_id', '=', 'proveedors.id')
            ->leftJoin('perfils', 'proveedors.perfil_id', '=', 'perfils.id')
            ->leftJoin('personas', 'perfils.persona_id', '=', 'personas.id')
            ->select('producto_x_proveedors.*', 'productos.*', 'proveedors.tipo', 'proveedors.cuit', 'perfils.persona_id', 'personas.nombre')
            ->where('descripcion','like','%'.$this->query .'%')
            ->orWhere('codigo','like','%'.$this->query .'%')
            ->paginate(20)

            // 'productos' => Producto::where('descripcion','like','%'.$this->query .'%')
            // ->orWhere('codigo','like','%'.$this->query .'%')
            //             ->paginate(20)
        ]);
    }
}
