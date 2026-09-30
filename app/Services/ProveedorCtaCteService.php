<?php

namespace App\Services;

use App\Models\Factura;
use App\Models\NotaCredito;
use App\Models\OrdenPago;
use App\Models\OrdenPagoFactura;
use App\Models\Pago;
use App\Models\PagosXCaja;
use App\Models\Caja;
use App\Models\CuentaCorrienteProveedor;
use App\Models\PedidoProveedor;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProveedorCtaCteService
{
    /**
     * Registrar Factura de Proveedor en la Cuenta Corriente
     */
    public function registrarFactura(array $data): Factura
    {
        return DB::transaction(function () use ($data) {
            $total = floatval($data['total'] ?? 0);
            $montoBloqueado = floatval($data['monto_bloqueado'] ?? 0);
            $saldoPendiente = $total;

            $factura = Factura::create([
                'pedido_proveedor_id' => $data['pedido_proveedor_id'] ?? null,
                'proveedor_id' => $data['proveedor_id'],
                'tipo_factura_id' => $data['tipo_factura_id'] ?? 1,
                'numero_factura' => $data['numero_factura'] ?? null,
                'fecha_emision' => $data['fecha_emision'] ?? Carbon::today(),
                'fecha_vencimiento' => $data['fecha_vencimiento'] ?? null,
                'subtotal' => $total,
                'total' => $total,
                'monto_bloqueado' => $montoBloqueado,
                'saldo_pendiente' => $saldoPendiente,
                'escenario_recepcion' => $data['escenario_recepcion'] ?? 'escenario_1',
                'observaciones' => $data['observaciones'] ?? null,
                'estado' => $montoBloqueado > 0 ? 'bloqueada_parcial' : 'pendiente_pago',
            ]);

            // Si hay monto bloqueado (Escenario 2), registrar el reclamo de Nota de Crédito pendiente
            if ($montoBloqueado > 0) {
                NotaCredito::create([
                    'factura_id' => $factura->id,
                    'proveedor_id' => $data['proveedor_id'],
                    'pedido_proveedor_id' => $data['pedido_proveedor_id'] ?? null,
                    'monto' => $montoBloqueado,
                    'monto_aplicado' => 0,
                    'estado' => 'pendiente_emision',
                    'motivo' => 'Faltante de entrega en OC #' . ($data['pedido_proveedor_id'] ?? '-') . ' contra factura total',
                    'observaciones' => 'Pendiente de emisión por proveedor para conciliar diferencia',
                ]);
            }

            // Calcular saldo acumulado del proveedor
            $ultimoMov = CuentaCorrienteProveedor::where('proveedor_id', $data['proveedor_id'])
                ->orderByDesc('id')
                ->first();
            $saldoAnterior = $ultimoMov ? floatval($ultimoMov->saldo) : 0;
            $nuevoSaldo = $saldoAnterior + $total;

            // Asentar en Cuenta Corriente
            CuentaCorrienteProveedor::create([
                'proveedor_id' => $data['proveedor_id'],
                'fecha' => $data['fecha_emision'] ?? Carbon::today(),
                'tipo_movimiento' => 'factura',
                'comprobante_tipo' => 'Factura ' . ($factura->tipoFactura?->descripcion ?? ''),
                'comprobante_numero' => $data['numero_factura'] ?? ('FAC-' . $factura->id),
                'descripcion' => 'Factura por compra OC #' . ($data['pedido_proveedor_id'] ?? '-') . ($montoBloqueado > 0 ? " (Bloqueado por NC: $" . number_format($montoBloqueado, 2) . ")" : ""),
                'debe' => $total,
                'haber' => 0,
                'saldo' => $nuevoSaldo,
                'monto_bloqueado' => $montoBloqueado,
                'factura_id' => $factura->id,
                'pedido_proveedor_id' => $data['pedido_proveedor_id'] ?? null,
                'estado' => 'activo',
            ]);

            return $factura;
        });
    }

    /**
     * Aplicar Nota de Crédito recibida del proveedor
     */
    public function aplicarNotaCredito(int $notaCreditoId, array $data): NotaCredito
    {
        return DB::transaction(function () use ($notaCreditoId, $data) {
            $nc = NotaCredito::findOrFail($notaCreditoId);
            $monto = floatval($data['monto'] ?? $nc->monto);
            $numero = $data['numero'] ?? $nc->numero;
            $fecha = $data['fecha_emision'] ?? Carbon::today();

            $nc->update([
                'numero' => $numero,
                'fecha_emision' => $fecha,
                'monto_aplicado' => $monto,
                'estado' => 'aplicada',
                'observaciones' => $data['observaciones'] ?? $nc->observaciones,
            ]);

            // Desbloquear saldo y reducir deuda en la factura asociada
            if ($nc->factura_id) {
                $factura = Factura::find($nc->factura_id);
                if ($factura) {
                    $nuevoBloqueado = max(0, floatval($factura->monto_bloqueado) - $monto);
                    $nuevoSaldo = max(0, floatval($factura->saldo_pendiente) - $monto);
                    $nuevoEstado = $nuevoSaldo <= 0 ? 'pagada' : ($nuevoBloqueado > 0 ? 'bloqueada_parcial' : 'pendiente_pago');

                    $factura->update([
                        'monto_bloqueado' => $nuevoBloqueado,
                        'saldo_pendiente' => $nuevoSaldo,
                        'estado' => $nuevoEstado,
                    ]);
                }
            }

            // Asentar en Cuenta Corriente
            $ultimoMov = CuentaCorrienteProveedor::where('proveedor_id', $nc->proveedor_id)
                ->orderByDesc('id')
                ->first();
            $saldoAnterior = $ultimoMov ? floatval($ultimoMov->saldo) : 0;
            $nuevoSaldo = max(0, $saldoAnterior - $monto);

            CuentaCorrienteProveedor::create([
                'proveedor_id' => $nc->proveedor_id,
                'fecha' => $fecha,
                'tipo_movimiento' => 'nota_credito',
                'comprobante_tipo' => 'Nota de Crédito',
                'comprobante_numero' => $numero ?: ('NC-' . $nc->id),
                'descripcion' => 'Aplicación de Nota de Crédito' . ($nc->factura_id ? " a Factura #" . $nc->factura_id : ""),
                'debe' => 0,
                'haber' => $monto,
                'saldo' => $nuevoSaldo,
                'monto_bloqueado' => 0,
                'nota_credito_id' => $nc->id,
                'factura_id' => $nc->factura_id,
                'pedido_proveedor_id' => $nc->pedido_proveedor_id,
                'estado' => 'activo',
            ]);

            return $nc;
        });
    }

    /**
     * Registrar Pago Realizado (Fase 5)
     */
    public function registrarPago(OrdenPago $ordenPago, array $pagoData): Pago
    {
        return DB::transaction(function () use ($ordenPago, $pagoData) {
            $monto = floatval($pagoData['monto']);
            $fecha = $pagoData['fecha'] ?? Carbon::today();
            $medioPagoId = $pagoData['medio_pago_id'] ?? 2; // 2 efectivo
            $cajaId = $pagoData['caja_id'] ?? null;
            $codeOp = $pagoData['code_op'] ?? null;
            $concepto = $pagoData['concepto'] ?? ('Pago OP #' . $ordenPago->numero);

            // 1. Crear el registro de Pago (egreso)
            $pago = Pago::create([
                'factura_id' => $ordenPago->items->first()?->factura_id ?? 1,
                'orden_pago_id' => $ordenPago->id,
                'proveedor_id' => $ordenPago->proveedor_id,
                'tipo_pago_id' => 2,
                'medio_pago_id' => $medioPagoId,
                'in_out' => 'out', // Egreso
                'efectivo' => $medioPagoId == 2 ? $monto : 0,
                'total' => $monto,
                'code_op' => $codeOp,
                'concepto' => $concepto,
                'estado' => '200',
            ]);

            // 2. Asociar a Caja si corresponde
            if ($cajaId) {
                PagosXCaja::create([
                    'pago_id' => $pago->id,
                    'caja_id' => $cajaId,
                    'estado' => '200',
                ]);

                // Actualizar egresos/gastos en la Caja
                $caja = Caja::find($cajaId);
                if ($caja) {
                    $gastosActuales = floatval($caja->gastos ?? 0);
                    $caja->update([
                        'gastos' => $gastosActuales + $monto,
                    ]);
                }
            }

            // 3. Imputar pago a las facturas de la Orden de Pago
            $montoRestante = $monto;
            foreach ($ordenPago->items as $item) {
                if ($montoRestante <= 0) {
                    break;
                }
                $pendienteItem = floatval($item->monto_imputado) - floatval($item->monto_pagado);
                if ($pendienteItem <= 0) {
                    continue;
                }

                $aplicar = min($montoRestante, $pendienteItem);
                $nuevoPagadoItem = floatval($item->monto_pagado) + $aplicar;
                $item->update(['monto_pagado' => $nuevoPagadoItem]);

                // Actualizar saldo de la Factura
                $factura = Factura::find($item->factura_id);
                if ($factura) {
                    $nuevoSaldoFactura = max(0, floatval($factura->saldo_pendiente) - $aplicar);
                    $facturaEstado = $nuevoSaldoFactura <= 0 ? 'pagada' : 'parcial';
                    $factura->update([
                        'saldo_pendiente' => $nuevoSaldoFactura,
                        'estado' => $facturaEstado,
                    ]);
                }

                $montoRestante -= $aplicar;
            }

            // 4. Actualizar estado y montos en la Orden de Pago
            $nuevoTotalPagado = floatval($ordenPago->monto_pagado) + $monto;
            $estadoOP = ($nuevoTotalPagado >= floatval($ordenPago->monto_total)) ? 'pagada' : 'parcialmente_pagada';
            $ordenPago->update([
                'monto_pagado' => $nuevoTotalPagado,
                'estado' => $estadoOP,
            ]);

            // 5. Asentar egreso/pago en Cuenta Corriente
            $ultimoMov = CuentaCorrienteProveedor::where('proveedor_id', $ordenPago->proveedor_id)
                ->orderByDesc('id')
                ->first();
            $saldoAnterior = $ultimoMov ? floatval($ultimoMov->saldo) : 0;
            $nuevoSaldo = max(0, $saldoAnterior - $monto);

            CuentaCorrienteProveedor::create([
                'proveedor_id' => $ordenPago->proveedor_id,
                'fecha' => $fecha,
                'tipo_movimiento' => 'pago',
                'comprobante_tipo' => 'Pago OP ' . ($ordenPago->numero ?? ('#' . $ordenPago->id)),
                'comprobante_numero' => $codeOp ?: ('REC-' . $pago->id),
                'descripcion' => $concepto . ' (' . ($pago->medios?->descripcion ?? 'Pago') . ')',
                'debe' => 0,
                'haber' => $monto,
                'saldo' => $nuevoSaldo,
                'monto_bloqueado' => 0,
                'orden_pago_id' => $ordenPago->id,
                'pago_id' => $pago->id,
                'estado' => 'activo',
            ]);

            return $pago;
        });
    }
}
