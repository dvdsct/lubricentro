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


    public function mount()
    {
        $this->proveedores = Proveedor::all();
        $this->categorias = CategoriaProducto::all();
        $this->subcategorias = SubcategoriaProducto::all();
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
        }
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
            $this->reset('descripcion', 'codigo', 'stock', 'costo', 'cod_barra', 'producto');
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
        $this->stock =  $sp->first()->cantidad;

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

        if ($this->categoria == 1) {

            $p = Producto::firstOrCreate([
                'descripcion' => $this->descripcion,
                'categoria_producto_id' => $this->categoria,
                'subcategoria_producto_id' => $this->subcategoria,
                'codigo_de_barras' => $this->cod_barra,
                'codigo' => $this->codigo,
            ]);

            $p->update([
                'monto' => $this->monto,
                'porcentaje' => $this->porcentaje,
            ]);

            $s = Stock::firstOrCreate([
                'sucursal_id' => '1',
                'producto_id' => $p->id,
                'estado' => '1'
            ]);
            $ns = $s->cantidad + $this->stock;

            $s->update([
                'cantidad' => $ns,

            ]);

            ProductoXProveedor::firstOrCreate([
                'proveedor_id' => $this->proveedor,
                'producto_id' => $p->id
            ]);
        } else {

            $p = Producto::firstOrCreate([
                'descripcion' => $this->descripcion,
                'categoria_producto_id' => $this->categoria,
                'subcategoria_producto_id' => $this->subcategoria,
                'codigo_de_barras' => $this->cod_barra,
                'codigo' => $this->codigo,
            ]);

            $p->update([
                'costo' => $this->costo,
                'precio_venta' => $this->precioVenta,
            ]);

            $s = Stock::firstOrCreate([
                'sucursal_id' => '1',
                'producto_id' => $p->id,
                'estado' => '1'
            ]);
            $ns = $s->cantidad + $this->stock;

            $s->update([
                'cantidad' => $ns,

            ]);
            ProductoXProveedor::firstOrCreate([
                'proveedor_id' => $this->proveedor,
                'producto_id' => $p->id
            ]);
        }





        $this->modalProductosOn();
        $this->dispatch('added')->to(LwProductos::class);
    }


    public function render()
    {

        return view('livewire.form-add-prod');
    }
}
