<?php

namespace App\Livewire;

use App\Models\CategoriaProducto;
use App\Models\Producto;
use App\Models\Stock;
use App\Models\SubcategoriaProducto;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ProductosProvisionales extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public $modal = false;
    public $producto_id = null;

    public $descripcion = '';
    public $codigo = '';
    public $precio_venta = '';
    public $costo = '0';
    public $categoria_id = '';
    public $subcategoria_id = '';
    public $codigo_de_barras = '';

    public $query = '';
    public $perPage = 15;

    public function mount()
    {
        $cat = CategoriaProducto::first();
        $this->categoria_id = $cat ? $cat->id : 3;
    }

    public function search()
    {
        $this->resetPage();
    }

    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function generarCodigo()
    {
        do {
            $nuevoCodigo = 'PROV-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
        } while (Producto::where('codigo', $nuevoCodigo)->exists());

        $this->codigo = $nuevoCodigo;
    }

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['producto_id', 'descripcion', 'codigo', 'precio_venta', 'costo', 'codigo_de_barras', 'subcategoria_id']);
        
        $cat = CategoriaProducto::first();
        $this->categoria_id = $cat ? $cat->id : 3;

        if ($id) {
            $p = Producto::findOrFail($id);
            $this->producto_id = $p->id;
            $this->descripcion = $p->descripcion;
            $this->codigo = $p->codigo;
            $this->precio_venta = $p->precio_venta;
            $this->costo = $p->costo ?? 0;
            $this->categoria_id = $p->categoria_producto_id;
            $this->subcategoria_id = $p->subcategoria_producto_id;
            $this->codigo_de_barras = $p->codigo_de_barras;
        } else {
            $this->generarCodigo();
            $this->costo = '0';
        }

        $this->modal = true;
    }

    public function closeModal()
    {
        $this->modal = false;
        $this->resetValidation();
    }

    public function save()
    {
        $idRule = $this->producto_id ? ',' . $this->producto_id : '';
        $this->validate([
            'descripcion' => 'required|string|max:255',
            'codigo' => 'required|string|max:100|unique:productos,codigo' . $idRule,
            'precio_venta' => 'required|numeric|min:0',
            'costo' => 'nullable|numeric|min:0',
        ], [
            'descripcion.required' => 'El nombre o descripción del producto es obligatorio.',
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique' => 'Este código ya está en uso por otro producto.',
            'precio_venta.required' => 'El precio de venta es obligatorio.',
            'precio_venta.numeric' => 'El precio de venta debe ser un número válido.',
        ]);

        $data = [
            'descripcion' => trim($this->descripcion),
            'codigo' => trim($this->codigo),
            'precio_venta' => floatval($this->precio_venta),
            'precio_presupuesto' => floatval($this->precio_venta),
            'costo' => floatval($this->costo ?: 0),
            'categoria_producto_id' => $this->categoria_id ?: null,
            'subcategoria_producto_id' => $this->subcategoria_id ?: null,
            'codigo_de_barras' => $this->codigo_de_barras ? trim($this->codigo_de_barras) : null,
            'es_provisional' => true,
        ];

        DB::transaction(function () use ($data) {
            if ($this->producto_id) {
                $p = Producto::findOrFail($this->producto_id);
                $p->update($data);
                $prodId = $p->id;
            } else {
                $p = Producto::create($data);
                $prodId = $p->id;
            }

            // Asegurar registro de stock con 0 para que aparezca en el modal de ventas/ordenes
            Stock::firstOrCreate([
                'producto_id' => $prodId,
                'sucursal_id' => 1,
            ], [
                'cantidad' => '0',
                'cantidad_num' => 0.000,
                'estado' => '1',
            ]);
        });

        session()->flash('success', $this->producto_id ? 'Producto provisional actualizado correctamente.' : 'Producto provisional creado exitosamente. Ya está listo para usarse en órdenes de venta.');
        $this->closeModal();
    }

    public function delProd($id)
    {
        $p = Producto::where('id', $id)->where('es_provisional', true)->first();
        if ($p) {
            Stock::where('producto_id', $p->id)->delete();
            $p->delete();
            session()->flash('success', 'Producto provisional eliminado.');
        }
    }

    public function render()
    {
        $categorias = CategoriaProducto::all();
        $subcategorias = SubcategoriaProducto::all();

        $query = Producto::where('es_provisional', true);

        if (!empty($this->query)) {
            $term = '%' . trim($this->query) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('descripcion', 'like', $term)
                  ->orWhere('codigo', 'like', $term)
                  ->orWhere('codigo_de_barras', 'like', $term);
            });
        }

        $productos = $query->orderByDesc('id')->paginate($this->perPage);

        return view('livewire.productos-provisionales', [
            'productos' => $productos,
            'categorias' => $categorias,
            'subcategorias' => $subcategorias,
        ]);
    }
}
