<div>
    <!-- RESUMEN DE SALDOS Y SERVICIOS NO PAGADOS EN LA PARTE SUPERIOR -->
    <div class="row mb-3">
        <!-- CANTIDAD DE SERVICIOS NO PAGADOS -->
        <div class="col-md-4 col-sm-6 mb-2">
            <div class="info-box bg-light shadow-sm border">
                <span class="info-box-icon bg-warning text-white"><i class="fas fa-file-invoice-dollar"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-muted font-weight-bold">Servicios No Pagados</span>
                    <span class="info-box-number text-dark h4 mb-0 font-weight-bold">
                        {{ $serviciosNoPagados }} {{ $serviciosNoPagados == 1 ? 'servicio' : 'servicios' }}
                    </span>
                    <small class="text-muted">Cargos registrados en cuenta corriente</small>
                </div>
            </div>
        </div>

        <!-- MONTO TOTAL QUE ADEUDA -->
        <div class="col-md-4 col-sm-6 mb-2">
            <div class="info-box bg-light shadow-sm border">
                <span class="info-box-icon {{ $saldoDeudor > 0 ? 'bg-danger text-white' : 'bg-success text-white' }}">
                    <i class="fas {{ $saldoDeudor > 0 ? 'fa-exclamation-triangle' : 'fa-check' }}"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text text-muted font-weight-bold">Monto Total Adeudado</span>
                    <span class="info-box-number {{ $saldoDeudor > 0 ? 'text-danger' : 'text-success' }} h4 mb-0 font-weight-bold">
                        ${{ number_format($saldoDeudor, 2, '.', ',') }}
                    </span>
                    <small class="{{ $saldoDeudor > 0 ? 'text-danger font-weight-bold' : 'text-success' }}">
                        {{ $saldoDeudor > 0 ? 'Saldo deudor pendiente' : 'Cuenta corriente al día' }}
                    </small>
                </div>
            </div>
        </div>

        <!-- TOTAL ABONADO / ACCIÓN COBRAR SALDO -->
        <div class="col-md-4 col-sm-12 mb-2">
            <div class="info-box bg-light shadow-sm border d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <span class="info-box-icon bg-success text-white mr-3"><i class="fas fa-hand-holding-usd"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-muted font-weight-bold">Total Abonado</span>
                        <span class="info-box-number text-success h5 mb-0 font-weight-bold">
                            ${{ number_format($totalHaber, 2, '.', ',') }}
                        </span>
                    </div>
                </div>
                @if($saldoDeudor > 0)
                <div class="pr-2">
                    <button type="button" class="btn btn-primary btn-sm font-weight-bold shadow-sm" wire:click="cobrarTotal">
                        <i class="fas fa-cash-register mr-1"></i> Cobrar Saldo Total
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session()->has('info'))
        <div class="alert alert-info alert-dismissible fade show shadow-sm mb-3" role="alert">
            <i class="fas fa-info-circle mr-1"></i> {{ session('info') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- TABLA DE MOVIMIENTOS DE CUENTA CORRIENTE -->
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header py-2 bg-light">
            <h5 class="card-title font-weight-bold mb-0 text-dark">
                <i class="fas fa-list-alt mr-1"></i> Historial de Movimientos en Cuenta Corriente
            </h5>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover table-striped mb-0 text-sm">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 80px;">Pago ID</th>
                        <th>FECHA TRANSACCIÓN</th>
                        <th>CONCEPTO</th>
                        <th class="text-right" style="width: 140px;">TOTAL</th>
                        <th class="text-center" style="width: 180px;">ESTADO</th>
                        <th class="text-right" style="width: 120px;">ACCIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pagos as $pago)
                        @php
                            $isDebe = ($pago->estado === 'debe');
                            $montoAbs = abs(floatval($pago->total));
                            $conceptoTexto = $pago->pagos?->concepto ?: ($isDebe ? 'Consumo / Servicio' : 'Cobro Cuenta Corriente');
                            $ordenId = $pago->pagos?->facturas?->orden_id;
                        @endphp
                        <tr>
                            <td class="font-weight-bold text-muted">#{{ $pago->id }}</td>
                            <td>{{ $pago->created_at->translatedFormat('d/m/Y H:i') }}</td>
                            <td>
                                <strong>{{ $conceptoTexto }}</strong>
                                @if($ordenId)
                                    <small class="text-muted d-block"><i class="fas fa-receipt mr-1"></i>Orden de servicio #{{ $ordenId }}</small>
                                @endif
                            </td>
                            <td class="text-right font-weight-bold {{ $isDebe ? 'text-danger' : 'text-success' }}">
                                {{ $isDebe ? '-' : '+' }} ${{ number_format($montoAbs, 2, '.', ',') }}
                            </td>
                            <td class="text-center">
                                @if($isDebe)
                                    <span class="badge badge-danger px-2 py-1 shadow-xs font-weight-bold">
                                        <i class="fas fa-exclamation-circle mr-1"></i> Debe (Pendiente)
                                    </span>
                                @else
                                    <span class="badge badge-success px-2 py-1 shadow-xs font-weight-bold">
                                        <i class="fas fa-check-circle mr-1"></i> Haber (Pagado / Abono)
                                    </span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if($isDebe)
                                    <button type="button" class="btn btn-primary btn-sm font-weight-bold shadow-sm" wire:click="pagar({{ $pago->id }})">
                                        <i class="fas fa-cash-register mr-1"></i> Cobrar
                                    </button>
                                @else
                                    <span class="badge badge-light border text-muted px-2 py-1">
                                        <i class="fas fa-check text-success mr-1"></i> Abonado
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x d-block mb-2 text-secondary"></i>
                                No se registran movimientos en la cuenta corriente de este cliente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pagos->hasPages())
        <div class="card-footer d-flex justify-content-end py-2 bg-light">
            {{ $pagos->links() }}
        </div>
        @endif
    </div>

    <!-- MODAL DE COBRO DE CUENTA CORRIENTE -->
    @if($modal)
        <div class="modal fade show" style="position: fixed; inset: 0; z-index: 1050; display:block; background-color: rgba(0,0,0,.5);" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-dialog-centered" style="z-index: 1051;">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title font-weight-bold mb-0">
                            <i class="fas fa-hand-holding-usd mr-2"></i> Cobro de Cuenta Corriente
                        </h5>
                        <button type="button" class="close text-white" wire:click="$set('modal', false)"><span>&times;</span></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Monto a cobrar ($)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number" step="0.01" min="0.01" class="form-control font-weight-bold text-dark" wire:model="monto">
                            </div>
                            @error('monto')<small class="text-danger font-weight-bold">{{ $message }}</small>@enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Medio de pago</label>
                            <select class="form-control" wire:model.live="medioPago">
                                <option value="">Seleccione medio de pago</option>
                                <option value="2">Efectivo</option>
                                <option value="5">Transferencia</option>
                            </select>
                            @error('medioPago')<small class="text-danger font-weight-bold">{{ $message }}</small>@enderror
                        </div>

                        @if($medioPago == 5)
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Código / N° de operación</label>
                                <input type="text" class="form-control" wire:model="code_op" placeholder="Comprobante de transferencia">
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer bg-light py-2 justify-content-between">
                        <button type="button" class="btn btn-secondary" wire:click="$set('modal', false)">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </button>
                        <button type="button" class="btn btn-primary font-weight-bold px-4" wire:click="confirmarCobro">
                            <i class="fas fa-check mr-1"></i> Confirmar Cobro
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
