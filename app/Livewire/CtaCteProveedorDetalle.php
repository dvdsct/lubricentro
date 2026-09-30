<?php

namespace App\Livewire;

use App\Models\Proveedor;
use App\Models\Factura;
use App\Models\NotaCredito;
use App\Models\CuentaCorrienteProveedor;
use App\Services\ProveedorCtaCteService;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class CtaCteProveedorDetalle extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';

    public $proveedorId;
    public $proveedor;

    // Modal Cargar / Aplicar Nota de Crédito
    public $modalNC = false;
    public $selectedNcId;
    public $ncNumero = '';
    public $ncFecha = '';
    public $ncMonto = '';
    public $ncObservaciones = '';

    public function mount($proveedorId)
    {
        $this->proveedorId = $proveedorId;
        $this->proveedor = Proveedor::with(['perfiles.personas'])->findOrFail($proveedorId);
        $this->ncFecha = Carbon::today()->format('Y-m-d');
    }

    public function openModalNC($ncId)
    {
        $this->selectedNcId = $ncId;
        $nc = NotaCredito::find($ncId);
        if ($nc) {
            $this->ncNumero = $nc->numero ?? '';
            $this->ncMonto = $nc->monto;
            $this->ncObservaciones = $nc->observaciones ?? '';
            $this->ncFecha = $nc->fecha_emision ? Carbon::parse($nc->fecha_emision)->format('Y-m-d') : Carbon::today()->format('Y-m-d');
        }
        $this->modalNC = true;
    }

    public function closeModalNC()
    {
        $this->modalNC = false;
        $this->reset(['selectedNcId', 'ncNumero', 'ncMonto', 'ncObservaciones']);
    }

    public function aplicarNotaCredito()
    {
        $this->validate([
            'ncNumero' => 'required|string|min:3',
            'ncFecha' => 'required|date',
            'ncMonto' => 'required|numeric|min:0.01',
        ], [
            'ncNumero.required' => 'Ingrese el número de comprobante de la Nota de Crédito.',
            'ncFecha.required' => 'Indique la fecha de la Nota de Crédito.',
            'ncMonto.required' => 'Indique el importe de la Nota de Crédito.',
        ]);

        $service = app(ProveedorCtaCteService::class);
        $service->aplicarNotaCredito($this->selectedNcId, [
            'numero' => $this->ncNumero,
            'fecha_emision' => $this->ncFecha,
            'monto' => floatval($this->ncMonto),
            'observaciones' => $this->ncObservaciones,
        ]);

        $this->closeModalNC();
        $this->proveedor->refresh();
        session()->flash('success', "Nota de Crédito #{$this->ncNumero} aplicada correctamente. Se desbloqueó el saldo correspondiente.");
    }

    public function render()
    {
        $movimientos = CuentaCorrienteProveedor::where('proveedor_id', $this->proveedorId)
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->paginate(15);

        $facturasPendientes = Factura::where('proveedor_id', $this->proveedorId)
            ->where('estado', '!=', 'pagada')
            ->orderBy('fecha_emision', 'asc')
            ->get();

        $notasCreditoPendientes = NotaCredito::where('proveedor_id', $this->proveedorId)
            ->where('estado', 'pendiente_emision')
            ->get();

        $totalFacturado = CuentaCorrienteProveedor::where('proveedor_id', $this->proveedorId)
            ->where('estado', 'activo')
            ->sum('debe');

        $totalHaber = CuentaCorrienteProveedor::where('proveedor_id', $this->proveedorId)
            ->where('estado', 'activo')
            ->sum('haber');

        $saldoTotal = max(0, floatval($totalFacturado) - floatval($totalHaber));
        $montoBloqueado = floatval($this->proveedor->saldo_bloqueado);
        $saldoDisponible = max(0, $saldoTotal - $montoBloqueado);

        return view('livewire.cta-cte-proveedor-detalle', [
            'movimientos' => $movimientos,
            'facturasPendientes' => $facturasPendientes,
            'notasCreditoPendientes' => $notasCreditoPendientes,
            'totalFacturado' => $totalFacturado,
            'totalHaber' => $totalHaber,
            'saldoTotal' => $saldoTotal,
            'montoBloqueado' => $montoBloqueado,
            'saldoDisponible' => $saldoDisponible,
        ]);
    }
}
