<?php

namespace App\Livewire;

use App\Models\CategoriaProducto;
use App\Models\Producto;
use App\Models\SubcategoriaProducto;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ProductosInforme extends Component
{
    // Filtros
    public $selectedCategoria = '';
    public $selectedSubcategoria = '';
    public $period = 'this_month'; // 'today', 'this_week', 'this_month', 'this_year', 'custom', 'all_time'
    public $startDate;
    public $endDate;
    public $metric = 'cantidad'; // 'cantidad' (unidades) o 'total' (monto $)
    public $limit = 10;
    public $searchQuery = '';

    // KPIs
    public $totalUnitsSold = 0;
    public $totalRevenue = 0;
    public $distinctProductsCount = 0;
    public $topProductName = '-';
    public $topProductQty = 0;
    public $topProductRevenue = 0;

    // Chart Data
    public $chartLabels = [];
    public $chartData = [];
    public $chartTooltips = [];

    // Ranking Table List
    public $rankingList = [];

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->toDateString();
        $this->calculateStats();
    }

    public function updatedSelectedCategoria()
    {
        $this->calculateStats();
        $this->dispatchChartUpdate();
    }

    public function updatedSelectedSubcategoria()
    {
        $this->calculateStats();
        $this->dispatchChartUpdate();
    }

    public function updatedPeriod()
    {
        if ($this->period === 'custom') {
            if (!$this->startDate) {
                $this->startDate = Carbon::now()->startOfMonth()->toDateString();
            }
            if (!$this->endDate) {
                $this->endDate = Carbon::now()->toDateString();
            }
        }
        $this->calculateStats();
        $this->dispatchChartUpdate();
    }

    public function updatedStartDate()
    {
        if ($this->period === 'custom') {
            $this->calculateStats();
            $this->dispatchChartUpdate();
        }
    }

    public function updatedEndDate()
    {
        if ($this->period === 'custom') {
            $this->calculateStats();
            $this->dispatchChartUpdate();
        }
    }

    public function updatedMetric()
    {
        $this->calculateStats();
        $this->dispatchChartUpdate();
    }

    public function updatedLimit()
    {
        $this->calculateStats();
        $this->dispatchChartUpdate();
    }

    public function updatedSearchQuery()
    {
        $this->calculateStats();
        $this->dispatchChartUpdate();
    }

    public function resetFilters()
    {
        $this->selectedCategoria = '';
        $this->selectedSubcategoria = '';
        $this->period = 'this_month';
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->toDateString();
        $this->metric = 'cantidad';
        $this->limit = 10;
        $this->searchQuery = '';
        $this->calculateStats();
        $this->dispatchChartUpdate();
    }

    protected function dispatchChartUpdate()
    {
        $this->dispatch('update-products-chart', [
            'labels' => $this->chartLabels,
            'data' => $this->chartData,
            'metric' => $this->metric,
        ]);
    }

    public function calculateStats()
    {
        $start = null;
        $end = Carbon::now()->endOfDay();

        switch ($this->period) {
            case 'today':
                $start = Carbon::today()->startOfDay();
                $end = Carbon::today()->endOfDay();
                break;
            case 'this_week':
                $start = Carbon::now()->startOfWeek()->startOfDay();
                $end = Carbon::now()->endOfWeek()->endOfDay();
                break;
            case 'this_month':
                $start = Carbon::now()->startOfMonth()->startOfDay();
                $end = Carbon::now()->endOfMonth()->endOfDay();
                break;
            case 'this_year':
                $start = Carbon::now()->startOfYear()->startOfDay();
                $end = Carbon::now()->endOfYear()->endOfDay();
                break;
            case 'custom':
                $start = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : Carbon::now()->startOfMonth()->startOfDay();
                $end = $this->endDate ? Carbon::parse($this->endDate)->endOfDay() : Carbon::now()->endOfDay();
                break;
            case 'all_time':
            default:
                $start = null;
                $end = null;
                break;
        }

        // Construir consulta base para items de productos vendidos
        $query = DB::table('items')
            ->join('productos', 'items.producto_id', '=', 'productos.id')
            ->leftJoin('categoria_productos', 'productos.categoria_producto_id', '=', 'categoria_productos.id')
            ->leftJoin('subcategoria_productos', 'productos.subcategoria_producto_id', '=', 'subcategoria_productos.id')
            ->whereNotNull('items.producto_id')
            ->where('items.producto_id', '>', 0)
            // Excluir categoría de descuentos (id 1 o descripcion Descuento)
            ->where(function ($q) {
                $q->whereNull('productos.categoria_producto_id')
                    ->orWhere('productos.categoria_producto_id', '!=', 1);
            })
            ->where('items.subtotal', '>=', 0);

        // Filtro de fechas sobre items.created_at
        if ($start && $end) {
            $query->whereBetween('items.created_at', [$start, $end]);
        } elseif ($start) {
            $query->where('items.created_at', '>=', $start);
        }

        // Filtro por categoría
        if (!empty($this->selectedCategoria)) {
            $query->where('productos.categoria_producto_id', $this->selectedCategoria);
        }

        // Filtro por subcategoría
        if (!empty($this->selectedSubcategoria)) {
            $query->where('productos.subcategoria_producto_id', $this->selectedSubcategoria);
        }

        // Filtro por búsqueda de texto (código o descripción)
        if (!empty($this->searchQuery)) {
            $query->where(function ($q) {
                $term = '%' . trim($this->searchQuery) . '%';
                $q->where('productos.descripcion', 'like', $term)
                    ->orWhere('productos.codigo', 'like', $term)
                    ->orWhere('productos.codigo_de_barras', 'like', $term);
            });
        }

        // Agrupación por producto
        $itemsAggregated = (clone $query)
            ->select(
                'productos.id',
                'productos.codigo',
                'productos.descripcion',
                'productos.precio_venta',
                'categoria_productos.descripcion as categoria_nombre',
                'subcategoria_productos.descripcion as subcategoria_nombre',
                DB::raw('SUM(CAST(items.cantidad AS DECIMAL(10,2))) as total_cantidad'),
                DB::raw('SUM(CAST(items.subtotal AS DECIMAL(12,2))) as total_recaudado'),
                DB::raw('COUNT(items.id) as transacciones_count')
            )
            ->groupBy(
                'productos.id',
                'productos.codigo',
                'productos.descripcion',
                'productos.precio_venta',
                'categoria_productos.descripcion',
                'subcategoria_productos.descripcion'
            )
            ->having('total_cantidad', '>', 0);

        // Ordenar según la métrica seleccionada
        if ($this->metric === 'total') {
            $itemsAggregated->orderByDesc('total_recaudado')->orderByDesc('total_cantidad');
        } else {
            $itemsAggregated->orderByDesc('total_cantidad')->orderByDesc('total_recaudado');
        }

        $allResults = $itemsAggregated->get();

        // Calcular KPIs globales sobre los resultados filtrados
        $this->totalUnitsSold = (float) $allResults->sum('total_cantidad');
        $this->totalRevenue = (float) $allResults->sum('total_recaudado');
        $this->distinctProductsCount = $allResults->count();

        if ($allResults->isNotEmpty()) {
            $top = $allResults->first();
            $this->topProductName = ($top->codigo ? "[{$top->codigo}] " : '') . $top->descripcion;
            $this->topProductQty = (float) $top->total_cantidad;
            $this->topProductRevenue = (float) $top->total_recaudado;
        } else {
            $this->topProductName = 'Sin ventas registradas';
            $this->topProductQty = 0;
            $this->topProductRevenue = 0;
        }

        // Aplicar límite para ranking y gráfico
        $limitNum = is_numeric($this->limit) && $this->limit > 0 ? (int) $this->limit : 10;
        $limitedResults = $allResults->take($limitNum);

        // Preparar lista para tabla
        $this->rankingList = $allResults->map(function ($item, $idx) {
            $cant = (float) $item->total_cantidad;
            $monto = (float) $item->total_recaudado;
            $percUnits = $this->totalUnitsSold > 0 ? round(($cant / $this->totalUnitsSold) * 100, 1) : 0;
            $percRevenue = $this->totalRevenue > 0 ? round(($monto / $this->totalRevenue) * 100, 1) : 0;

            return [
                'pos' => $idx + 1,
                'id' => $item->id,
                'codigo' => $item->codigo ?: '-',
                'descripcion' => $item->descripcion,
                'categoria' => $item->categoria_nombre ?: 'Sin Categoría',
                'subcategoria' => $item->subcategoria_nombre ?: 'Sin Subcategoría',
                'precio_venta' => (float) $item->precio_venta,
                'cantidad' => $cant,
                'recaudado' => $monto,
                'transacciones' => (int) $item->transacciones_count,
                'perc_units' => $percUnits,
                'perc_revenue' => $percRevenue,
            ];
        })->toArray();

        // Preparar datos para el gráfico
        $labels = [];
        $data = [];

        foreach ($limitedResults as $item) {
            // Etiqueta legible y truncada si es muy larga
            $label = $item->codigo ? "{$item->codigo} - " . \Illuminate\Support\Str::limit($item->descripcion, 22) : \Illuminate\Support\Str::limit($item->descripcion, 25);
            $labels[] = $label;
            $data[] = $this->metric === 'total' ? round((float) $item->total_recaudado, 2) : (float) $item->total_cantidad;
        }

        $this->chartLabels = $labels;
        $this->chartData = $data;
    }

    public function render()
    {
        // Categorías disponibles (excluyendo descuentos si tienen id 1)
        $categorias = CategoriaProducto::where('estado', '1')
            ->where('id', '!=', 1)
            ->orderBy('descripcion')
            ->get();

        // Subcategorías disponibles
        $subcategoriasQuery = SubcategoriaProducto::where('estado', '1');

        // Si hay categoría seleccionada, podemos priorizar o listar las subcategorías presentes en productos de esa categoría
        if (!empty($this->selectedCategoria)) {
            $subcatIds = Producto::where('categoria_producto_id', $this->selectedCategoria)
                ->whereNotNull('subcategoria_producto_id')
                ->pluck('subcategoria_producto_id')
                ->unique();
            if ($subcatIds->isNotEmpty()) {
                $subcategoriasQuery->whereIn('id', $subcatIds);
            }
        }

        $subcategorias = $subcategoriasQuery->orderBy('descripcion')->get();

        return view('livewire.productos-informe', [
            'categorias' => $categorias,
            'subcategorias' => $subcategorias,
        ]);
    }
}
