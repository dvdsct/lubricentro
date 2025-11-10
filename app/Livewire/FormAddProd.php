<?php

namespace App\Livewire;

use App\Models\CategoriaProducto;
use App\Models\Producto;
use App\Models\ProductoXProveedor;
use App\Models\Proveedor;
use App\Models\Stock;
use App\Models\SubcategoriaProducto;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class FormAddProd extends Component
{
    public $producto;
    public $descripcion;
    public $cod_barra;
    public $costo;
    public $codigo;
    public $stock;
    public $precioVenta;
    public $subcategoria;
    public $subcategorias;
    public $categoria;
    public $categorias;
    public $proveedores;
    public $proveedor = '1';
    public $modalProductos = false;
    public $formProd = true;
    public $formDes;
    public $tipoDes;
    public $monto;
    public $porcentaje;

    // Corrección de stock en modal
    public $stockActual;
    public $stockMode = 'ajustar'; // ajustar | fijar
    public $stockDelta;
    public $stockFinal;
    public $stockPreview;
    public $stockMotivo;


    public function mount()
    {
        $this->proveedores = Proveedor::all();
        $this->categorias = CategoriaProducto::all();
        if (Auth::user()->hasRole('admin')) {
            $this->subcategorias = SubcategoriaProducto::all();
        }else{
            $this->subcategorias = SubcategoriaProducto::where('estado','2')->get();
        }
    }

    public function updatedStockDelta()
    {
        if ($this->stockMode === 'ajustar') {
            $base = intval($this->stockActual ?? 0);
            $delta = intval($this->stockDelta ?? 0);
            $this->stockPreview = $base + $delta;
        }
    }

    public function updatedStockFinal()
    {
        if ($this->stockMode === 'fijar') {
            $this->stockPreview = intval($this->stockFinal ?? 0);
        }
    }

    public function applyStockCorrection()
    {
        $sucursalId = 1;
        $this->validate([
            'stockMode' => 'required|in:ajustar,fijar',
        ]);

        $delta = 0;
        if ($this->stockMode === 'ajustar') {
            $this->validate([
                'stockDelta' => 'required|integer|not_in:0',
            ]);
            $delta = intval($this->stockDelta);
        } else {
            $this->validate([
                'stockFinal' => 'required|integer|min:0',
            ]);
            $base = intval($this->stockActual ?? 0);
            $delta = intval($this->stockFinal) - $base;
        }

        if (!$this->producto) {
            return; // solo corrección cuando existe producto
        }

        $service = app(\App\Services\StockService::class);
        $service->ensureStockRecord($sucursalId, $this->producto->id);
        $row = $service->adjustStock(
            $sucursalId,
            $this->producto->id,
            $delta,
            [
                'motivo' => $this->stockMotivo ?: 'Corrección manual en modal de producto',
                'referencia_type' => 'Producto',
                'referencia_id' => $this->producto->id,
            ]
        );

        // Refrescar valores en UI
        $this->stockActual = intval($row->cantidad);
        $this->stock = $this->stockActual;
        $this->stockPreview = $this->stockActual;
        $this->reset('stockDelta','stockFinal','stockMotivo');
        $this->dispatch('stock-corrected');
    }



    // Formulario Producto o descuento
    public function selTipo()
    {


        if ($this->categoria == 1) {
            if (Auth::user()->hasRole('admin')) {

                $this->formDes = true;
                $this->formProd = false;


                $this->descripcion = 'Descuento';
                $this->codigo = $this->porcentaje;

                $this->subcategorias = [
                    ['1', 'Monto'],
                    ['2', 'Porcentaje']
                ];
            } else {
                $this->reset('categoria');
                return  $this->dispatch('nonDesc');
            }
        } else {
            $this->formDes = false;
            $this->formProd = true;
            $this->subcategorias = SubcategoriaProducto::where('estado','1')->get();
        }
        $this->reset('subcategoria');
    }

    public function selSubTipo()
    {

        if ($this->subcategoria == 2) {
            $this->tipoDes = true;
        } else {
            $this->tipoDes = false;
        }
    }

    #[On('modal-prod-on')]
    public function modalProductosOn()
    {
        if ($this->modalProductos) {

            $this->modalProductos = false;
            // Limpiar TODOS los campos del formulario al cerrar
            $this->reset(
                'producto',
                'descripcion',
                'cod_barra',
                'costo',
                'codigo',
                'stock',
                'precioVenta',
                'subcategoria',
                'categoria',
                'porcentaje',
                'monto',
                'formDes',
                'tipoDes'
            );
            // Volver a valores por defecto
            $this->formProd = true;
            $this->proveedor = '1';
        } else {

            $this->modalProductos = true;
        }
    }

    #[On('modal-prod-edit')]
    public function editProd(string $id)
    {

        $this->producto = Producto::find($id);
        $sp = Stock::where('producto_id', $id)->get();
        $this->descripcion =  $this->producto->descripcion;
        $this->categoria =  $this->producto->categoria_producto_id;
        $this->subcategoria =  $this->producto->subcategoria_producto_id;
        $this->cod_barra =  $this->producto->codigo_de_barras;
        $this->costo =  $this->producto->costo;
        $this->codigo =  $this->producto->codigo;
        $this->precioVenta = $this->producto->precio_venta;
        $this->stock =  $sp->first()->cantidad;
        $this->stockActual = $this->stock;
        $this->stockPreview = $this->stockActual;
        $this->reset('stockDelta','stockFinal','stockMotivo');

        $this->modalProductosOn();
    }


    public function updatingCosto($costo)
    {
        $this->precioVenta = floatval($costo) + (floatval($costo) / 100) * 60;
    }
    public function updatingPrecioVenta($precio)
    {
        $this->precioVenta = $precio;
    }

    public function saveproduct()
    {
        $this->validate([
            'descripcion' => 'required|string|min:2',
            'categoria' => 'required',
            'subcategoria' => 'required',
            'stock' => 'nullable|numeric|min:0',
            'costo' => 'nullable|numeric|min:0',
            'precioVenta' => 'nullable|numeric|min:0',
        ]);

        if ($this->categoria == 1) {
            $p = Producto::firstOrCreate([
                'descripcion' => $this->descripcion,
                'codigo_de_barras' => $this->cod_barra,
                'codigo' => $this->codigo,
            ]);

            $p->update([
                'monto' => $this->monto,
                'porcentaje' => $this->porcentaje,
                'categoria_producto_id' => $this->categoria,
                'subcategoria_producto_id' => $this->subcategoria,
            ]);

            $sucursalId = 1;
            $service = app(\App\Services\StockService::class);
            $service->ensureStockRecord($sucursalId, $p->id);
            if (!empty($this->stock) && intval($this->stock) > 0) {
                $service->adjustStock($sucursalId, $p->id, intval($this->stock));
            }

            ProductoXProveedor::firstOrCreate([
                'proveedor_id' => $this->proveedor,
                'producto_id' => $p->id
            ]);
        } else {
            $p = Producto::firstOrCreate([
                'descripcion' => $this->descripcion,
                'codigo_de_barras' => $this->cod_barra,
                'codigo' => $this->codigo,
            ]);

            $p->update([
                'costo' => $this->costo,
                'precio_venta' => $this->precioVenta,
                'categoria_producto_id' => $this->categoria,
                'subcategoria_producto_id' => $this->subcategoria,
            ]);

            $sucursalId = 1;
            $service = app(\App\Services\StockService::class);
            $service->ensureStockRecord($sucursalId, $p->id);
            if (!empty($this->stock) && intval($this->stock) > 0) {
                $service->adjustStock($sucursalId, $p->id, intval($this->stock));
            }
            ProductoXProveedor::firstOrCreate([
                'proveedor_id' => $this->proveedor,
                'producto_id' => $p->id
            ]);
        }

        // Limpiar formulario tras crear el producto
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset(
            'producto',
            'descripcion',
            'cod_barra',
            'costo',
            'codigo',
            'stock',
            'precioVenta',
            'subcategoria',
            'categoria',
            'porcentaje',
            'monto',
            'formDes',
            'tipoDes'
        );
        $this->formProd = true;
        $this->proveedor = '1';
        // Opcional: mantener modal abierto para seguir cargando
        $this->modalProductos = true;

        // Notificar al frontend que se creó el producto
        $this->dispatch('producto-agregado');
    }


    public function render()
    {

        return view('livewire.form-add-prod');
    }
}
