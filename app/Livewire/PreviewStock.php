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

    // Fraccionamiento / Desarme a Granel
    public $showFractionModal = false;
    public $sourceProductoId = '';
    public $sourceQty = 1;
    public $destProductoId = '';
    public $litrosPorEnvase = '';
    public $fractionMotivo = 'Apertura y fraccionamiento a granel';
    public $searchSource = '';
    public $searchDest = '';

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

    public function openFractionModal()
    {
        $this->reset('sourceProductoId', 'destProductoId', 'sourceQty', 'litrosPorEnvase', 'searchSource', 'searchDest');
        $this->sourceQty = 1;
        $this->fractionMotivo = 'Apertura y fraccionamiento a granel';
        $this->showFractionModal = true;
    }

    public function closeFractionModal()
    {
        $this->showFractionModal = false;
        $this->reset('sourceProductoId', 'destProductoId', 'sourceQty', 'litrosPorEnvase', 'searchSource', 'searchDest');
    }

    public function updatedSourceProductoId($val)
    {
        if ($val) {
            $p = Producto::find($val);
            if ($p) {
                // Auto-detect common volume in description (e.g. 205, 208, 20, 4)
                if (preg_match('/(\d+(?:\.\d+)?)\s*(?:lts|lt|l|litros)\b/i', $p->descripcion . ' ' . $p->codigo, $matches)) {
                    $this->litrosPorEnvase = $matches[1];
                }
            }
        }
    }

    public function confirmFraction()
    {
        $this->validate([
            'sourceProductoId' => 'required|exists:productos,id',
            'destProductoId' => 'required|different:sourceProductoId|exists:productos,id',
            'sourceQty' => 'required|numeric|min:0.01',
            'litrosPorEnvase' => 'required|numeric|min:0.01',
            'fractionMotivo' => 'nullable|string|max:255',
        ], [
            'sourceProductoId.required' => 'Debe seleccionar un producto de origen (envase/tambor).',
            'destProductoId.required' => 'Debe seleccionar un producto destino (granel).',
            'destProductoId.different' => 'El producto destino debe ser diferente al producto origen.',
            'sourceQty.required' => 'Ingrese la cantidad de envases a fraccionar.',
            'sourceQty.min' => 'La cantidad debe ser mayor a 0.',
            'litrosPorEnvase.required' => 'Ingrese los litros por envase.',
            'litrosPorEnvase.min' => 'Los litros por envase deben ser mayor a 0.',
        ]);

        $sucursalId = 1;
        $stockService = app(\App\Services\StockService::class);
        $sQty = floatval(str_replace(',', '.', (string)$this->sourceQty));
        $lPorEnv = floatval(str_replace(',', '.', (string)$this->litrosPorEnvase));
        $totalLitros = $sQty * $lPorEnv;

        $available = $stockService->getAvailableStock($sucursalId, (int)$this->sourceProductoId);
        if ($available < $sQty) {
            $this->addError('sourceQty', "Stock insuficiente en producto origen. Disponible: {$available}");
            return;
        }

        try {
            $stockService->fractionStock(
                $sucursalId,
                (int)$this->sourceProductoId,
                $sQty,
                (int)$this->destProductoId,
                $totalLitros,
                [
                    'motivo' => $this->fractionMotivo ?: 'Apertura y fraccionamiento a granel',
                    'user_id' => auth()->id(),
                ]
            );

            $this->closeFractionModal();
            $this->dispatch('stock-fractioned');
            session()->flash('message', "Fraccionamiento exitoso: se descontaron {$sQty} envase(s) y se sumaron {$totalLitros} L al producto destino.");
        } catch (\Exception $e) {
            $this->addError('sourceQty', 'Error: ' . $e->getMessage());
        }
    }

    public function addCantidad($id)
    {
        $p = Stock::find($id);
        if (!$p) return;

        $newCantidad = floatval(str_replace(',', '.', (string)$this->cantidad));
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

        $sourceProducts = collect();
        $destProducts = collect();
        $sourceStock = 0;
        $destStock = 0;

        if ($this->showFractionModal) {
            $sourceProducts = Producto::select('id', 'descripcion', 'codigo')
                ->when(!empty($this->searchSource), function($q) {
                    $txt = '%' . $this->searchSource . '%';
                    $q->where(function($sq) use ($txt) {
                        $sq->where('descripcion', 'like', $txt)
                           ->orWhere('codigo', 'like', $txt);
                    });
                })
                ->orderBy('descripcion')
                ->limit(60)
                ->get();

            $destProducts = Producto::select('id', 'descripcion', 'codigo')
                ->when(!empty($this->searchDest), function($q) {
                    $txt = '%' . $this->searchDest . '%';
                    $q->where(function($sq) use ($txt) {
                        $sq->where('descripcion', 'like', $txt)
                           ->orWhere('codigo', 'like', $txt);
                    });
                })
                ->orderBy('descripcion')
                ->limit(60)
                ->get();

            if ($this->sourceProductoId) {
                $sourceStockRow = Stock::where('producto_id', $this->sourceProductoId)->where('sucursal_id', 1)->first();
                $sourceStock = $sourceStockRow?->cantidad ?? 0;
            }

            if ($this->destProductoId) {
                $destStockRow = Stock::where('producto_id', $this->destProductoId)->where('sucursal_id', 1)->first();
                $destStock = $destStockRow?->cantidad ?? 0;
            }
        }

        return view('livewire.preview-stock', [
            'stock' => $stockQuery->paginate(10),
            'historyMovements' => $historyMovements,
            'categorias' => $categorias,
            'subcategorias' => $subcategorias,
            'sourceProducts' => $sourceProducts,
            'destProducts' => $destProducts,
            'sourceStock' => $sourceStock,
            'destStock' => $destStock,
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
