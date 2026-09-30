<?php

namespace App\Livewire;

use App\Models\OrdenPago;
use App\Models\MedioPago;
use App\Models\Caja;
use App\Models\Banco;
use App\Models\Perfil;
use App\Services\ProveedorCtaCteService;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GestionOrdenPago extends Component
{
    public $ordenPagoId;
    public $ordenPago;

    // Modales de control
    public $modalAutorizacion = false;
    public $modalConciliacion = false;
    public $modalPago = false;

    // FASE 4: Autorización del Dueño
    public $authTipo = 'digital'; // 'digital', 'fisica'
    public $authNotas = '';

    // FASE 4: Cotejo de Resumen del Proveedor
    public $resumenConciliado = true;
    public $resumenIncidencias = '';

    // FASE 5: Registro de Pago
    public $pagoMonto = '';
    public $pagoFecha = '';
    public $pagoMedioId = 5; // Default 5: Transferencia o 2: Efectivo
    public $pagoCajaId = null;
    public $pagoBancoId = null;
    public $pagoCodeOp = '';
    public $pagoConcepto = '';

    public $mediosPago = [];
    public $cajasAbiertas = [];
    public $bancos = [];

    public function mount($id)
    {
        $this->ordenPagoId = $id;
        $this->cargarOrdenPago();

        $this->mediosPago = MedioPago::all();
        $this->bancos = Banco::all();
        $this->pagoFecha = Carbon::today()->format('Y-m-d');
        $this->pagoConcepto = "Pago OP #" . ($this->ordenPago->numero ?? $this->ordenPago->id);
    }

    public function cargarOrdenPago()
    {
        $this->ordenPago = OrdenPago::with([
            'proveedor.perfiles.personas',
            'items.factura.tipoFactura',
            'pagos.medios',
            'autorizadoPor',
            'usuarioCreador'
        ])->findOrFail($this->ordenPagoId);

        $this->pagoMonto = $this->ordenPago->saldo_pendiente;
        $this->resumenConciliado = (bool)$this->ordenPago->resumen_conciliado;
        $this->resumenIncidencias = $this->ordenPago->resumen_incidencias ?? '';
    }

    // ==========================================
    // FASE 4: AUTORIZACIÓN DEL DUEÑO
    // ==========================================

    public function openModalAutorizacion()
    {
        $this->modalAutorizacion = true;
    }

    public function closeModalAutorizacion()
    {
        $this->modalAutorizacion = false;
    }

    public function autorizarOrdenPago()
    {
        $this->ordenPago->update([
            'estado' => 'autorizada',
            'autorizado_por' => Auth::id(),
            'fecha_autorizacion' => Carbon::now(),
            'autorizacion_tipo' => $this->authTipo,
            'autorizacion_notas' => $this->authNotas,
        ]);

        $this->closeModalAutorizacion();
        $this->cargarOrdenPago();
        session()->flash('success', 'Orden de Pago autorizada por el dueño. Lista para cotejo y ejecución.');
    }

    // ==========================================
    // FASE 4: COTEJO DE RESUMEN DEL PROVEEDOR
    // ==========================================

    public function openModalConciliacion()
    {
        $this->modalConciliacion = true;
    }

    public function closeModalConciliacion()
    {
        $this->modalConciliacion = false;
    }

    public function guardarConciliacion()
    {
        $this->ordenPago->update([
            'resumen_conciliado' => $this->resumenConciliado,
            'resumen_incidencias' => $this->resumenIncidencias,
        ]);

        $this->closeModalConciliacion();
        $this->cargarOrdenPago();

        if ($this->resumenConciliado) {
            session()->flash('success', 'Resumen del proveedor conciliado con éxito. Se puede proceder al pago.');
        } else {
            session()->flash('warning', 'Se registraron incidencias en el resumen del proveedor. Resolver antes de emitir el pago.');
        }
    }

    // ==========================================
    // FASE 5: REGISTRO DE PAGO REALIZADO
    // ==========================================

    public function openModalPago()
    {
        $this->pagoMonto = $this->ordenPago->saldo_pendiente;
        // Cargar cajas abiertas para egreso
        $this->cajasAbiertas = Caja::with(['sucursals', 'cajeros.perfils.personas'])
            ->where('estado', '200')
            ->get();

        if ($this->cajasAbiertas->isNotEmpty() && !$this->pagoCajaId) {
            $this->pagoCajaId = $this->cajasAbiertas->first()->id;
        }

        $this->modalPago = true;
    }

    public function closeModalPago()
    {
        $this->modalPago = false;
    }

    public function registrarPago()
    {
        $saldoPendiente = $this->ordenPago->saldo_pendiente;

        $this->validate([
            'pagoMonto' => 'required|numeric|min:0.01|max:' . $saldoPendiente,
            'pagoFecha' => 'required|date',
            'pagoMedioId' => 'required|exists:medio_pagos,id',
            'pagoCodeOp' => 'nullable|string',
        ], [
            'pagoMonto.required' => 'Ingrese el monto pagado.',
            'pagoMonto.max' => 'El monto no puede superar el saldo pendiente autorizado ($' . number_format($saldoPendiente, 2) . ').',
            'pagoFecha.required' => 'Indique la fecha efectiva de pago.',
            'pagoMedioId.required' => 'Seleccione el medio de pago utilizado.',
        ]);

        $service = app(ProveedorCtaCteService::class);
        $service->registrarPago($this->ordenPago, [
            'monto' => floatval($this->pagoMonto),
            'fecha' => $this->pagoFecha,
            'medio_pago_id' => $this->pagoMedioId,
            'caja_id' => ($this->pagoMedioId == 2) ? $this->pagoCajaId : null,
            'code_op' => $this->pagoCodeOp,
            'concepto' => $this->pagoConcepto ?: ('Pago OP #' . $this->ordenPago->numero),
        ]);

        $this->closeModalPago();
        $this->cargarOrdenPago();

        session()->flash('success', "Pago de $" . number_format(floatval($this->pagoMonto), 2) . " registrado correctamente. Se actualizaron cajas, facturas y cuenta corriente.");
    }

    public function render()
    {
        return view('livewire.gestion-orden-pago');
    }
}
