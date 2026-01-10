<?php

namespace App\Livewire;

use Livewire\WithPagination;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Producto;
use Livewire\Component;
use App\Models\CategoriaProducto;
use App\Models\SubcategoriaProducto;

class PreviewStock extends Component
{

    use WithPagination;



    public $cantidad;
    public $query = '';
    protected $paginationTheme = 'bootstrap';

    // Historial
    public $showHistory = false;
    public $historyMovements = [];
    public $historyProductoDesc;

    // Filtros
    public $categoriaId = '';
    public $subcategoriaId = '';

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
        $categorias = CategoriaProducto::select('id','descripcion')->orderBy('descripcion')->get();
        $subcategorias = SubcategoriaProducto::select('id','descripcion')->orderBy('descripcion')->get();

        return view(
            'livewire.preview-stock',
            [
                'stock' => (function() {
                    $q = Stock::select(
                        'stocks.*',
                        'productos.codigo',
                        'productos.descripcion as descripcion'
                    )
                        ->leftJoin('productos', 'stocks.producto_id', '=', 'productos.id');

                    // Filtro por subcategoría si se selecciona (requerido por el usuario)
                    if (!empty($this->subcategoriaId)) {
                        $q->where('productos.subcategoria_producto_id', $this->subcategoriaId);
                    }

                    // Filtro de búsqueda por texto (agrupado)
                    $queryTxt = '%' . $this->query . '%';
                    $q->where(function($w) use ($queryTxt) {
                        $w->where('productos.descripcion', 'like', $queryTxt)
                          ->orWhere('productos.codigo', 'like', $queryTxt);
                    });

                    return $q->paginate(10);
                })(),
                'categorias' => $categorias,
                'subcategorias' => $subcategorias,
            ]
        );
    }

    public function openHistory($stockId)
    {
        $stock = Stock::find($stockId);
        if (!$stock) return;
        $productoId = $stock->producto_id;
        $p = Producto::find($productoId);
        $this->historyProductoDesc = $p?->descripcion;

        $this->historyMovements = StockMovement::where('producto_id', $productoId)
            ->orderByDesc('created_at')
            ->limit(100)
            ->with('user')
            ->get()
            ->toArray();
        $this->showHistory = true;
    }

    public function closeHistory()
    {
        $this->showHistory = false;
        $this->historyMovements = [];
        $this->historyProductoDesc = null;
    }
}
