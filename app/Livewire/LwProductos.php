<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Producto;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class LwProductos extends Component
{
    use WithPagination;
    public $head = ['descripcion', 'costo'];
    public $list;
    public $producto;
    // public $productos;

    public $query = ' ';

    public function search()
    {
        $this->resetPage();
    }

    #[On('added')]
    public function oo()
    {

        $this->list = Producto::all();
        // dd('here');
    }

    public function render()
    {
        return view('livewire.lw-productos', [
            'productos' => Producto::where('descripcion','like', '%'. $this->query .'%')
            ->orWhere('codigo','like', '%'. $this->query .'%')
            ->orderBy('descripcion', 'desc')
            ->paginate(10)
        ]);
    }
}
