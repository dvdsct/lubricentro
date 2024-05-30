<?php

namespace App\Livewire;

use App\Models\CategoriaProducto;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Stock;
use App\Models\SubcategoriaProducto;
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


    public function mount(){
        $this->proveedores= Proveedor::all();
        $this->categorias= CategoriaProducto::all();
        $this->subcategorias= SubcategoriaProducto::all();

    }

    #[On('modal-prod-on')]
    public function modalProductosOn()
    {
        if($this->modalProductos){

            $this->modalProductos = false;
            $this->reset('descripcion','codigo','stock','costo','cod_barra','producto');
        }else{

            $this->modalProductos = true;
        }

    }

    #[On('modal-prod-edit')]
    public function editProd(string $id){
        
        $this->producto = Producto::find($id);
        $sp = Stock::where('producto_id',$id)->get();
        $this->descripcion =  $this->producto->descripcion;
        $this->cod_barra =  $this->producto->codigo_de_barras;
        $this->costo =  $this->producto->costo;
        $this->codigo =  $this->producto->codigo;
        $this->stock =  $sp->first()->cantidad;
        
        $this->modalProductosOn();
    }


    public function updatedCosto($costo){
        dd($costo);
        $this->precioVenta = floatval($this->costo) + (floatval($this->costo) / 100)* 60;

    }

    public function saveproduct()
    {
        $precio  = $this->costo + (($this->costo /100)*60);
        $p = Producto::firstOrCreate([
            'descripcion' => $this->descripcion,
            'codigo_de_barras' => $this->cod_barra,
            'codigo' => $this->codigo,
        ]);

        $p->update([
            'costo' => $this->costo,
            'precio_venta' => $precio,
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



        $this->modalProductosOn();
        $this->dispatch('added')->to(LwProductos::class);
    }


    public function render()
    {

        return view('livewire.form-add-prod');
    }
}
