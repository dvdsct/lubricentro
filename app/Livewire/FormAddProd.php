<?php

namespace App\Livewire;

use App\Models\Producto;
use App\Models\Stock;
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
    public $modalProductos = false;


    #[On('modal-prod-on')]
    public function modalProductosOn()
    {
        if($this->modalProductos){

            $this->modalProductos = false;
        }else{

            $this->modalProductos = true;
        }
    }


    public function saveproduct()
    {
        $precio  = $this->costo + (($this->costo /100)*60);
        $p = Producto::create([
            'descripcion' => $this->descripcion,
            'codigo_de_barras' => $this->cod_barra,
            'costo' => $this->costo,
            'codigo' => $this->codigo,
            'precio_venta' => $precio,
        ]);

        Stock::create([
            'sucursal_id' => '1',
            'producto_id' => $p->id,
            'cantidad' => $this->stock,
            'estado' => '1'

        ]);

        $this->modalProductosOn();
        $this->dispatch('added')->to(LwProductos::class);
    }


    public function render()
    {
        return view('livewire.form-add-prod');
    }
}
