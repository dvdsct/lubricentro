<?php

namespace App\Livewire;

use App\Models\Proveedor;
use App\Models\TipoPedido;
use Livewire\Component;
use Livewire\Attributes\On;

class AddSupplierOrder extends Component
{

    public $modal = false;
    public $proveedores;
    public $proveedor;
    public $tiposPedidos;
    public $tipoPedido;
    public function mount()
    {
        $this->proveedores = Proveedor::all();
        $this->tiposPedidos = TipoPedido::all();
    }

    #[On('modalSupOrder')]
    public function modalOn()
    {
        $this->modal = true;
    }


    public function continueForm(){

        $p = Pedido::create([
            
            

'proveedor_id'=> '',	
'tipo_pedido_id'=> '',	
'descripcion'=> '',	
'fecha_ingreso'=> '',	
'monto_total'=> '',	
'observaciones'=> '',
        ]);

        dd($this->proveedor);
    }
    public function render()
    {
        return view('livewire.add-supplier-order');
    }
}
