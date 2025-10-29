<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PagoCtacte;
use App\Models\Factura;
use App\Models\Pago;
use App\Models\PagosXCaja;
use App\Models\Caja;
use App\Models\Perfil;
use Illuminate\Support\Facades\Auth;
use App\Livewire\ListaCajas;

class CtaCteDetalle extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';

    public $clienteId;

    // UI state
    public $modal = false;
    public $medioPago; // 2 efectivo, 5 transferencia
    public $code_op;   // transferencia
    public $monto;
    public $tipoFactura = '4';
    public $concepto = 'Cobro Cuenta Corriente';

    // Selected movement
    public $selectedPagoId;

    public function mount($clienteId)
    {
        $this->clienteId = $clienteId;
    }

    public function pagar($pagoId)
    {
        $this->selectedPagoId = $pagoId;
        $pago = PagoCtacte::find($pagoId);
        if (!$pago) {
            return;
        }
        // Prefill amount as positive
        $this->monto = abs($pago->total ?? 0);
        $this->modal = true;
    }

    public function confirmarCobro()
    {
        $this->validate([
            'medioPago' => 'required'
        ]);

        $pagoCta = PagoCtacte::find($this->selectedPagoId);
        if (!$pagoCta) {
            return;
        }

        // Validar caja abierta del usuario actual (si es cajero)
        $perfil = Perfil::where('user_id', Auth::user()->id)->first();
        $cajero = optional($perfil)->cajeros->first();
        if ($cajero) {
            $caja = Caja::where('cajero_id', $cajero->id)->where('estado', '200')->first();
            if (!$caja) {
                $this->dispatch('setModalCaja')->to(ListaCajas::class);
                return;
            }
        } else {
            $caja = null;
        }

        // Estado según medio de pago
        $estado = $this->medioPago == 2 ? '200' : ($this->medioPago == 5 ? '90' : '200');

        // Crear comprobante simple
        $factura = Factura::create([
            'orden_id' => null,
            'tipo_factura_id' => $this->tipoFactura,
            'total' => $this->monto,
            'iva' => 0,
            'estado' => $estado,
        ]);

        // Crear pago de ingreso
        $pago = Pago::create([
            'in_out' => 'in',
            'factura_id' => $factura->id,
            'cliente_id' => $this->clienteId,
            'medio_pago_id' => $this->medioPago,
            'tipo_pago_id' => '2',
            'efectivo' => $this->medioPago == 2 ? $this->monto : 0,
            'total' => $this->monto,
            'code_op' => $this->medioPago == 5 ? $this->code_op : null,
            'concepto' => $this->concepto,
            'estado' => $estado,
        ]);

        // Asociar pago a caja si corresponde
        if ($caja) {
            PagosXCaja::create([
                'pago_id' => $pago->id,
                'caja_id' => $caja->id,
                'estado' => $estado,
            ]);
        }

        // Registrar haber en cuenta corriente
        PagoCtacte::create([
            'cliente_id' => $this->clienteId,
            'pago_id' => $pago->id,
            'total' => $this->monto,
            'estado' => 'haber',
        ]);

        // Cerrar modal y refrescar
        $this->reset(['modal', 'medioPago', 'code_op', 'monto', 'selectedPagoId']);
        $this->dispatch('pago-added')->to(ViewCaja::class);
    }

    public function render()
    {
        $pagos = PagoCtacte::where('cliente_id', $this->clienteId)
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.cta-cte-detalle', [
            'pagos' => $pagos,
        ]);
    }
}
