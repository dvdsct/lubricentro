<?php

namespace App\Livewire;

use App\Models\Proveedor;
use App\Models\Factura;
use App\Models\OrdenPago;
use App\Models\OrdenPagoFactura;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateOrdenPago extends Component
{
    public $proveedor_id;
    public $proveedores = [];
    public $facturas = [];

    // Facturas seleccionadas e importes a imputar
    public $selectedFacturas = []; // [factura_id => true/false]
    public $montosImputar = [];    // [factura_id => float]

    public $fechaEmision;
    public $observaciones = '';

    public function mount($proveedor_id = null, $factura_id = null)
    {
        $this->proveedores = Proveedor::with('perfiles.personas')->get();
        $this->fechaEmision = Carbon::today()->format('Y-m-d');

        if ($proveedor_id) {
            $this->proveedor_id = $proveedor_id;
            $this->cargarFacturas();

            if ($factura_id) {
                $this->selectedFacturas[$factura_id] = true;
                $fac = Factura::find($factura_id);
                if ($fac) {
                    $this->montosImputar[$factura_id] = $fac->monto_disponible_pago;
                }
            }
        }
    }

    public function updatedProveedorId()
    {
        $this->cargarFacturas();
    }

    public function cargarFacturas()
    {
        $this->selectedFacturas = [];
        $this->montosImputar = [];

        if (!$this->proveedor_id) {
            $this->facturas = [];
            return;
        }

        $this->facturas = Factura::where('proveedor_id', $this->proveedor_id)
            ->where('estado', '!=', 'pagada')
            ->orderBy('fecha_emision', 'asc')
            ->get();

        foreach ($this->facturas as $f) {
            $disp = $f->monto_disponible_pago;
            if ($disp > 0) {
                $this->selectedFacturas[$f->id] = true;
                $this->montosImputar[$f->id] = $disp;
            } else {
                $this->selectedFacturas[$f->id] = false;
                $this->montosImputar[$f->id] = 0;
            }
        }
    }

    public function toggleFactura($facturaId)
    {
        if (!empty($this->selectedFacturas[$facturaId])) {
            $fac = Factura::find($facturaId);
            $this->montosImputar[$facturaId] = $fac ? $fac->monto_disponible_pago : 0;
        } else {
            $this->montosImputar[$facturaId] = 0;
        }
    }

    public function guardarOrdenPago()
    {
        $this->validate([
            'proveedor_id' => 'required|exists:proveedors,id',
            'fechaEmision' => 'required|date',
        ], [
            'proveedor_id.required' => 'Seleccione un proveedor.',
            'fechaEmision.required' => 'Indique la fecha de emisión.',
        ]);

        $facturasAImputar = [];
        $totalOrden = 0;

        foreach ($this->selectedFacturas as $facturaId => $isSelected) {
            if ($isSelected) {
                $monto = floatval($this->montosImputar[$facturaId] ?? 0);
                if ($monto > 0) {
                    $fac = Factura::find($facturaId);
                    if ($fac) {
                        $disponible = $fac->monto_disponible_pago;
                        if ($monto > $disponible) {
                            $this->addError('monto_' . $facturaId, "El monto a imputar ($" . number_format($monto, 2) . ") supera el disponible para pago ($" . number_format($disponible, 2) . ") de la Factura #" . ($fac->numero_factura ?: $fac->id));
                            return;
                        }
                        $facturasAImputar[] = [
                            'factura_id' => $fac->id,
                            'monto_factura' => floatval($fac->total),
                            'monto_imputado' => $monto,
                        ];
                        $totalOrden += $monto;
                    }
                }
            }
        }

        if (empty($facturasAImputar) || $totalOrden <= 0) {
            session()->flash('error', 'Debe seleccionar al menos una factura con un importe a pagar mayor a cero.');
            return;
        }

        $ordenPago = DB::transaction(function () use ($totalOrden, $facturasAImputar) {
            $opCount = OrdenPago::withTrashed()->count() + 1;
            $numeroOP = 'OP-' . str_pad($opCount, 6, '0', STR_PAD_LEFT);

            $op = OrdenPago::create([
                'proveedor_id' => $this->proveedor_id,
                'numero' => $numeroOP,
                'fecha_emision' => $this->fechaEmision,
                'monto_total' => $totalOrden,
                'monto_pagado' => 0,
                'estado' => 'pendiente_autorizacion',
                'observaciones' => $this->observaciones,
                'usuario_creador_id' => Auth::id(),
            ]);

            foreach ($facturasAImputar as $item) {
                OrdenPagoFactura::create([
                    'orden_pago_id' => $op->id,
                    'factura_id' => $item['factura_id'],
                    'monto_factura' => $item['monto_factura'],
                    'monto_imputado' => $item['monto_imputado'],
                    'monto_pagado' => 0,
                ]);
            }

            return $op;
        });

        session()->flash('success', "Orden de Pago {$ordenPago->numero} generada con éxito. Pendiente de autorización por el dueño.");
        return redirect()->route('ordenes-pago.show', $ordenPago->id);
    }

    public function render()
    {
        $proveedor = $this->proveedor_id ? Proveedor::find($this->proveedor_id) : null;
        $saldoTotalProveedor = $proveedor ? $proveedor->saldo_total : 0;
        $montoBloqueadoProveedor = $proveedor ? $proveedor->saldo_bloqueado : 0;
        $saldoDisponibleProveedor = $proveedor ? $proveedor->saldo_disponible : 0;

        $totalAImputarCalculado = 0;
        foreach ($this->selectedFacturas as $fId => $sel) {
            if ($sel) {
                $totalAImputarCalculado += floatval($this->montosImputar[$fId] ?? 0);
            }
        }

        $saldoRemanenteEstimado = max(0, $saldoTotalProveedor - $totalAImputarCalculado);

        return view('livewire.create-orden-pago', [
            'proveedor' => $proveedor,
            'saldoTotalProveedor' => $saldoTotalProveedor,
            'montoBloqueadoProveedor' => $montoBloqueadoProveedor,
            'saldoDisponibleProveedor' => $saldoDisponibleProveedor,
            'totalAImputarCalculado' => $totalAImputarCalculado,
            'saldoRemanenteEstimado' => $saldoRemanenteEstimado,
        ]);
    }
}
