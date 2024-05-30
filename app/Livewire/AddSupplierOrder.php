<?php

namespace App\Livewire;

use App\Models\PedidoProveedor;
use App\Models\Proveedor;
use App\Models\TipoPedido;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class AddSupplierOrder extends Component
{

    public $modal = false;
    public $proveedores;

    #[Validate('required', message: 'Seleccione el proveedor')]
    public $proveedor;
    public $tiposPedidos;

    #[Validate('required', message: 'Defina una categoria para este pedido')]
    public $tipoPedido;

    public $observaciones = '';

    #[Validate('required', message: '*')]
    public $fechaIn;


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

    public function modalOff()
    {
        $this->modal = false;

        //
    }

    public function continueForm()
    {

        $this->validate();
        $p = PedidoProveedor::create([

            'proveedor_id' => $this->proveedor,
            'tipo_pedido_id' => $this->tipoPedido,
            'fecha_ingreso' => $this->fechaIn,
            'sucursal_id' => '1',
            'observaciones' => $this->observaciones,
        ]);

        redirect(route('pedidos.show',$p->id));
    }
    public function render()
    {
        return view('livewire.add-supplier-order');
    }
}
