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
    public $es_provisional = false;

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
        } else {
            // Mostrar subcategorías activas también para no-admin
            $this->subcategorias = SubcategoriaProducto::where('estado','1')->get();
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


    public function updatedCosto($costo)
    {
        $c = floatval($costo);
        if ($c >= 0) {
            $this->precioVenta = $c + ($c * 0.80);
        }
    }
    public function updatingPrecioVenta($precio)
    {
        $this->precioVenta = $precio;
    }

    public function saveproduct()
    {
        // Si el usuario ingresó costo pero dejó vacío el precio de venta, calcularlo automáticamente (costo + 80%)
        if ((is_null($this->precioVenta) || $this->precioVenta === '') && is_numeric($this->costo)) {
            $c = floatval($this->costo);
            $this->precioVenta = $c + ($c * 0.80);
        }
        $this->validate([
            'descripcion' => 'required|string|min:2',
            'categoria' => 'required',
            'subcategoria' => 'required',
            'codigo' => 'required|unique:productos,codigo,' . ($this->producto->id ?? ''),
            'precioVenta' => 'required|numeric|min:0',
            'costo' => 'required|numeric|min:0',
        ]);

        $data = [
            'descripcion' => $this->descripcion,
            'codigo' => $this->codigo,
            'costo' => $this->costo,
            'precio_venta' => $this->precioVenta,
            'codigo_de_barras' => $this->cod_barra,
            'subcategoria_producto_id' => $this->subcategoria,
            'categoria_producto_id' => $this->categoria,
        ];

        // Set provisional status
        if (auth()->user()->hasRole('admin')) {
            $data['es_provisional'] = $this->es_provisional ?? false;
        } else {
            // For non-admin users, always set as provisional
            $data['es_provisional'] = true;
        }

        \DB::beginTransaction();
        try {
            if ($this->producto) {
                // Update existing product
                $this->producto->update($data);
                $producto = $this->producto;
                
                // Update stock if admin and stock is provided
                if (auth()->user()->hasRole('admin') && isset($this->stock)) {
                    $stock = $producto->stocks()->firstOrNew(['sucursal_id' => 1]);
                    $stock->cantidad = $this->stock;
                    $stock->save();
                }
            } else {
                // Create new product
                $producto = Producto::create($data);
                
                // Create stock record for the product
                if (auth()->user()->hasRole('admin')) {
                    $stockQty = $this->stock ?? 0;
                } else {
                    // Non-admin users can only create products with 0 stock
                    $stockQty = 0;
                    session()->flash('message', 'Producto provisional creado. Será revisado por un administrador.');
                }
                
                // Create initial stock record
                $producto->stocks()->create([
                    'cantidad' => $stockQty,
                    'sucursal_id' => 1, // Default branch ID
                    'estado' => 1 // Assuming 1 means active
                ]);
            }

            // Associate with provider
            ProductoXProveedor::firstOrCreate([
                'proveedor_id' => $this->proveedor,
                'producto_id' => $producto->id
            ]);

            \DB::commit();

            // Clear form
            $this->resetErrorBag();
            $this->resetValidation();
            $this->reset([
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
                'tipoDes',
                'es_provisional'
            ]);
            
            $this->formProd = true;
            $this->proveedor = '1';
            $this->modalProductos = false;

            $this->dispatch('producto-agregado');

        } catch (\Exception $e) {
            \DB::rollBack();
            session()->flash('error', 'Error al guardar el producto: ' . $e->getMessage());
        }
    }


    public function render()
    {

        return view('livewire.form-add-prod');
    }
}
