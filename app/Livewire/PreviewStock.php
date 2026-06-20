<?php

namespace App\Livewire;

use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
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
    public $historyProductId;
    public $historyProductoDesc;

    // Filtros
    public $categoriaId = '';
    public $subcategoriaId = '';

    // Cuando se cambie el filtro de subcategoría, resetear la paginación
    public function updatedSubcategoriaId($value)
    {
        $this->subcategoriaId = $value === '' ? '' : (int) $value;
        $this->resetPage();
    }

    public function subcategoriaChanged($value)
    {
        $this->subcategoriaId = $value === '' ? '' : (int) $value;
        Log::debug('PreviewStock subcategoriaChanged called', ['value' => $value, 'subcategoriaId' => $this->subcategoriaId]);
        $this->resetPage();
    }

    public function search()
    {
        $this->resetPage();
    }

    public function addCantidad($id)
    {
        $p = Stock::find($id);
        if (!$p) return;

        $newCantidad = floatval($this->cantidad);
        $oldCantidad = floatval($p->cantidad);
        $delta = $newCantidad - $oldCantidad;

        if ($delta !== 0.0) {
            $stockService = app(\App\Services\StockService::class);
            $sucursalId = $p->sucursal_id ?: 1;
            $stockService->adjustStock($sucursalId, $p->producto_id, $delta, [
                'motivo' => 'Ajuste rápido en lista de stock',
                'operacion' => 'Ajuste manual',
                'referencia_type' => 'Stock',
                'referencia_id' => $p->id,
            ]);
        }

        $p->update([
            'estado' => '1',
        ]);

        $this->reset('cantidad');
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
        // Log current filters for debugging
        Log::debug('PreviewStock render filters', ['subcategoriaId' => $this->subcategoriaId, 'query' => $this->query]);
        $categorias = CategoriaProducto::select('id','descripcion')->orderBy('descripcion')->get();
        $subcategorias = SubcategoriaProducto::select('id','descripcion')->orderBy('descripcion')->get();

        // Construir la consulta usando relaciones para evitar inconsistencias
        $stockQuery = Stock::with('productos')
            ->when(!empty($this->subcategoriaId), function ($q) {
                $subId = (int) $this->subcategoriaId;
                $q->whereHas('productos', function ($qp) use ($subId) {
                    $qp->where('subcategoria_producto_id', $subId);
                });
            })
            ->when(trim($this->query) !== '', function ($q) {
                $txt = '%' . $this->query . '%';
                $q->whereHas('productos', function ($qp) use ($txt) {
                    $qp->where('descripcion', 'like', $txt)
                       ->orWhere('codigo', 'like', $txt);
                });
            });

        // Log de debugging: mostrar SQL aproximado y bindings
        try {
            $toSql = $stockQuery->toBase()->toSql();
            Log::debug('PreviewStock query', ['sql' => $toSql, 'subcategoriaId' => $this->subcategoriaId, 'query' => $this->query]);
        } catch (\Exception $e) {
            Log::debug('PreviewStock query build failed', ['message' => $e->getMessage()]);
        }

        $historyMovements = collect();
        if ($this->showHistory && $this->historyProductId) {
            $historyMovements = StockMovement::where('producto_id', $this->historyProductId)
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->with('user')
                ->paginate(5, ['*'], 'historyPage');
        }

        return view('livewire.preview-stock', [
            'stock' => $stockQuery->paginate(10),
            'historyMovements' => $historyMovements,
            'categorias' => $categorias,
            'subcategorias' => $subcategorias,
        ]);
    }

    public function openHistory($stockId)
    {
        $stock = Stock::find($stockId);
        if (!$stock) return;
        $productoId = $stock->producto_id;
        $p = Producto::find($productoId);
        $this->historyProductoDesc = $p?->descripcion;

        $this->historyProductId = $productoId;
        $this->resetPage('historyPage');
        $this->showHistory = true;
    }

    public function closeHistory()
    {
        $this->showHistory = false;
        $this->historyProductId = null;
        $this->historyProductoDesc = null;
        $this->resetPage('historyPage');
    }
}
