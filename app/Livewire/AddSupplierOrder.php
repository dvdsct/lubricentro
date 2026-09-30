<?php

namespace App\Livewire;

use App\Models\PedidoProveedor;
use App\Models\Proveedor;
use App\Models\TipoPedido;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AddSupplierOrder extends Component
{
    public $modal = false;
    public $proveedores = [];

    #[Validate('required', message: 'Seleccione el proveedor')]
    public $proveedor;
    public $tiposPedidos = [];

    #[Validate('required', message: 'Defina una categoría para este pedido')]
    public $tipoPedido;

    public $condiciones_pago = 'Contado';
    public $observaciones = '';

    #[Validate('required', message: 'Indique la fecha de la orden')]
    public $fechaIn;

    public function mount()
    {
        $this->proveedores = Proveedor::with('perfiles.personas')->get();
        $this->tiposPedidos = TipoPedido::all();
        $this->fechaIn = Carbon::today()->format('Y-m-d');
    }

    #[On('modalSupOrder')]
    public function modalOn()
    {
        $this->modal = true;
        $this->fechaIn = Carbon::today()->format('Y-m-d');
        $this->tipoPedido = $this->tiposPedidos->first()?->id;
    }

    public function modalOff()
    {
        $this->modal = false;
    }

    public function continueForm()
    {
        $this->validate();

        $p = PedidoProveedor::create([
            'proveedor_id' => $this->proveedor,
            'tipo_pedido_id' => $this->tipoPedido,
            'fecha_ingreso' => $this->fechaIn,
            'condiciones_pago' => $this->condiciones_pago,
            'sucursal_id' => 1,
            'observaciones' => $this->observaciones,
            'estado' => 'borrador',
            'usuario_creador_id' => Auth::id(),
        ]);

        return redirect()->route('pedidos.show', $p->id);
    }

    public function render()
    {
        return view('livewire.add-supplier-order');
    }
}
