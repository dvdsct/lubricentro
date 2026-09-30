<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session()->has('warning'))
        <div class="alert alert-warning alert-dismissible fade show shadow-sm mb-3" role="alert">
            <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('warning') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- ENCABEZADO DE LA ORDEN DE PAGO -->
    <div class="card card-outline card-primary shadow-sm mb-3">
        <div class="card-header py-2 d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h4 class="font-weight-bold mb-0 text-dark">
                    <i class="fas fa-file-invoice-dollar mr-2 text-primary"></i>
                    ORDEN DE PAGO {{ $ordenPago->numero ?: ('#' . $ordenPago->id) }}
                </h4>
                <span class="text-muted small">Proveedor: <strong>{{ $ordenPago->proveedor?->nombre_completo ?? 'Proveedor' }}</strong> (CUIT: {{ $ordenPago->proveedor?->cuit ?: '-' }})</span>
            </div>
            <div class="d-flex align-items-center mt-2 mt-sm-0">
                @if($ordenPago->estado === 'pagada')
                    <span class="badge badge-success px-3 py-2 font-weight-bold mr-2"><i class="fas fa-check-double mr-1"></i> PAGADA</span>
                @elseif($ordenPago->estado === 'parcialmente_pagada')
                    <span class="badge badge-info px-3 py-2 font-weight-bold mr-2"><i class="fas fa-hourglass-half mr-1"></i> PARCIALMENTE PAGADA</span>
                @elseif($ordenPago->estado === 'autorizada')
                    <span class="badge badge-primary px-3 py-2 font-weight-bold mr-2"><i class="fas fa-check mr-1"></i> AUTORIZADA POR DUEÑO</span>
                @else
                    <span class="badge badge-warning px-3 py-2 font-weight-bold mr-2"><i class="fas fa-user-clock mr-1"></i> PENDIENTE AUTORIZACIÓN</span>
                @endif

                <a href="{{ route('proveedores.show', $ordenPago->proveedor_id) }}" class="btn btn-outline-info btn-sm mr-2" title="Ver Cuenta Corriente">
                    <i class="fas fa-warehouse mr-1"></i> Cta. Cte.
                </a>
                <a href="{{ route('ordenes-pago.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Volver
                </a>
            </div>
        </div>

        <div class="card-body py-2 px-3">
            <div class="row text-sm">
                <div class="col-md-3 col-sm-6 mb-1">
                    <span class="text-muted d-block">Fecha de Emisión:</span>
                    <strong>{{ $ordenPago->fecha_emision ? \Carbon\Carbon::parse($ordenPago->fecha_emision)->format('d/m/Y') : '-' }}</strong>
                </div>
                <div class="col-md-3 col-sm-6 mb-1">
                    <span class="text-muted d-block">Total Autorizado:</span>
                    <strong class="text-dark">$ {{ number_format((float)$ordenPago->monto_total, 2, '.', ',') }}</strong>
                </div>
                <div class="col-md-3 col-sm-6 mb-1">
                    <span class="text-muted d-block">Total Pagado:</span>
                    <strong class="text-success">$ {{ number_format((float)$ordenPago->monto_pagado, 2, '.', ',') }}</strong>
                </div>
                <div class="col-md-3 col-sm-6 mb-1">
                    <span class="text-muted d-block">Saldo Pendiente:</span>
                    <strong class="{{ $ordenPago->saldo_pendiente > 0 ? 'text-danger' : 'text-muted' }}">$ {{ number_format((float)$ordenPago->saldo_pendiente, 2, '.', ',') }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- TARJETAS DE FLUJO: FASE 4 (AUTORIZACIÓN Y COTEJO) & FASE 5 (PAGO) -->
    <div class="row mb-4">
        <!-- 1. AUTORIZACIÓN DEL DUEÑO -->
        <div class="col-md-4 mb-3">
            <div class="card card-outline {{ $ordenPago->autorizado_por ? 'card-success' : 'card-warning' }} shadow-sm h-100 mb-0">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="mr-3 {{ $ordenPago->autorizado_por ? 'text-success' : 'text-warning' }}">
                                <i class="fas fa-user-check fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold mb-0 text-dark">1. Autorización del Dueño</h6>
                                <small class="text-muted">Fija el importe máximo y distribución</small>
                            </div>
                        </div>
                        @if($ordenPago->autorizado_por)
                            <div class="alert alert-light border py-1 px-2 text-xs mb-0">
                                <strong>Autorizada:</strong> {{ $ordenPago->fecha_autorizacion?->format('d/m/Y H:i') }}<br>
                                <strong>Tipo:</strong> {{ ucfirst($ordenPago->autorizacion_tipo) }} {{ $ordenPago->autorizacion_notas ? ' - ' . $ordenPago->autorizacion_notas : '' }}
                            </div>
                        @else
                            <p class="text-muted small mb-0">La OP debe ser aprobada antes de ejecutar el cotejo y pago.</p>
                        @endif
                    </div>
                    @if(!$ordenPago->autorizado_por)
                        <div class="mt-3">
                            <button type="button" class="btn btn-warning btn-block font-weight-bold shadow-sm" wire:click="openModalAutorizacion">
                                <i class="fas fa-pen-nib mr-1"></i> Autorizar Orden de Pago
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 2. COTEJO CON RESUMEN DEL PROVEEDOR -->
        <div class="col-md-4 mb-3">
            <div class="card card-outline {{ $ordenPago->resumen_conciliado ? 'card-success' : ($ordenPago->resumen_incidencias ? 'card-danger' : 'card-info') }} shadow-sm h-100 mb-0">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="mr-3 {{ $ordenPago->resumen_conciliado ? 'text-success' : ($ordenPago->resumen_incidencias ? 'text-danger' : 'text-info') }}">
                                <i class="fas fa-clipboard-check fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold mb-0 text-dark">2. Comparar Resumen Proveedor</h6>
                                <small class="text-muted">Cotejar facturas, NC e importes</small>
                            </div>
                        </div>
                        @if($ordenPago->resumen_conciliado)
                            <div class="alert alert-success py-1 px-2 text-xs mb-0">
                                <i class="fas fa-check-circle mr-1"></i> Resumen cotejado y sin diferencias.
                            </div>
                        @elseif($ordenPago->resumen_incidencias)
                            <div class="alert alert-danger py-1 px-2 text-xs mb-0">
                                <strong>Incidencias:</strong> {{ $ordenPago->resumen_incidencias }}
                            </div>
                        @else
                            <p class="text-muted small mb-0">Verificar resumen de cuenta corriente del proveedor antes de emitir dinero.</p>
                        @endif
                    </div>
                    @if($ordenPago->isAutorizada() && $ordenPago->saldo_pendiente > 0)
                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-info btn-block font-weight-bold" wire:click="openModalConciliacion">
                                <i class="fas fa-tasks mr-1"></i> Registrar Cotejo / Incidencias
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 3. PAGO EXTERNO Y REGISTRO -->
        <div class="col-md-4 mb-3">
            <div class="card card-outline {{ $ordenPago->estado === 'pagada' ? 'card-success' : 'card-primary' }} shadow-sm h-100 mb-0">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="mr-3 {{ $ordenPago->estado === 'pagada' ? 'text-success' : 'text-primary' }}">
                                <i class="fas fa-money-bill-wave fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold mb-0 text-dark">3. Pago Externo y Registro</h6>
                                <small class="text-muted">Transferencia, cheque o efectivo</small>
                            </div>
                        </div>
                        <p class="text-muted text-xs mb-0">
                            <em>El pago se realiza fuera de la app. La autorización por sí sola no registra salida de dinero.</em>
                        </p>
                    </div>
                    @if($ordenPago->isAutorizada() && $ordenPago->saldo_pendiente > 0)
                        <div class="mt-3">
                            <button type="button" class="btn btn-success btn-block font-weight-bold shadow-sm" wire:click="openModalPago"
                                    @if(!$ordenPago->resumen_conciliado && $ordenPago->resumen_incidencias) disabled title="Resuelva las incidencias antes de pagar" @endif>
                                <i class="fas fa-receipt mr-1"></i> Registrar Pago Realizado
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- TABLA DE FACTURAS IMPUTADAS EN LA ORDEN DE PAGO -->
    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-header py-2">
            <h6 class="font-weight-bold mb-0 text-dark">
                <i class="fas fa-file-invoice mr-1 text-primary"></i> Facturas Incluidas en la Orden de Pago
            </h6>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-bordered table-hover mb-0 text-sm">
                <thead class="thead-light">
                    <tr>
                        <th>Factura</th>
                        <th>Fecha Emisión</th>
                        <th class="text-right">Total Factura</th>
                        <th class="text-right">Monto Imputado en OP</th>
                        <th class="text-right">Monto Pagado</th>
                        <th class="text-right">Saldo Restante Factura</th>
                        <th>Estado Factura</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ordenPago->items as $item)
                    <tr>
                        <td>
                            <strong>Factura {{ $item->factura?->tipoFactura?->descripcion ?? '' }} #{{ $item->factura?->numero_factura ?: $item->factura_id }}</strong>
                            @if($item->factura?->pedido_proveedor_id)
                                <small class="text-muted d-block">OC #{{ $item->factura->pedido_proveedor_id }}</small>
                            @endif
                        </td>
                        <td>{{ $item->factura?->fecha_emision ? \Carbon\Carbon::parse($item->factura->fecha_emision)->format('d/m/Y') : '-' }}</td>
                        <td class="text-right font-weight-bold">$ {{ number_format((float)$item->monto_factura, 2, '.', ',') }}</td>
                        <td class="text-right font-weight-bold text-primary">$ {{ number_format((float)$item->monto_imputado, 2, '.', ',') }}</td>
                        <td class="text-right font-weight-bold text-success">$ {{ number_format((float)$item->monto_pagado, 2, '.', ',') }}</td>
                        <td class="text-right font-weight-bold {{ $item->factura?->saldo_pendiente > 0 ? 'text-danger' : 'text-muted' }}">
                            $ {{ number_format((float)$item->factura?->saldo_pendiente, 2, '.', ',') }}
                        </td>
                        <td>
                            @if($item->factura?->estado === 'pagada')
                                <span class="badge badge-success">PAGADA</span>
                            @elseif($item->factura?->estado === 'parcial')
                                <span class="badge badge-info">PAGO PARCIAL</span>
                            @else
                                <span class="badge badge-primary">PENDIENTE</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- TABLA DE PAGOS / EGRESOS REGISTRADOS -->
    @if($ordenPago->pagos->isNotEmpty())
    <div class="card card-outline card-success shadow-sm mb-4">
        <div class="card-header py-2">
            <h6 class="font-weight-bold mb-0 text-dark">
                <i class="fas fa-receipt mr-1 text-success"></i> Pagos Registrados / Salidas de Dinero
            </h6>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-bordered table-striped table-hover mb-0 text-sm">
                <thead class="thead-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Medio de Pago</th>
                        <th>N° Comprobante / Ref.</th>
                        <th>Concepto</th>
                        <th class="text-right">Importe Pagado</th>
                        <th>Caja / Origen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ordenPago->pagos as $p)
                    <tr>
                        <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                        <td><span class="badge badge-info">{{ $p->medios?->descripcion ?? 'Pago' }}</span></td>
                        <td><strong>{{ $p->code_op ?: ('PAGO #' . $p->id) }}</strong></td>
                        <td>{{ $p->concepto }}</td>
                        <td class="text-right font-weight-bold text-success">$ {{ number_format((float)$p->total, 2, '.', ',') }}</td>
                        <td>
                            @if($p->cajas->isNotEmpty())
                                <span class="badge badge-secondary">Caja #{{ $p->cajas->first()->id }} ({{ $p->cajas->first()->turno ?? 'Turno' }})</span>
                            @else
                                <span class="text-muted">Banco / Externo</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- MODAL 1: AUTORIZACIÓN DEL DUEÑO -->
    @if($modalAutorizacion)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-user-check mr-2"></i> Autorizar Orden de Pago</h5>
                    <button type="button" class="close" wire:click="closeModalAutorizacion"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info text-sm mb-3">
                        Monto total a autorizar: <strong>${{ number_format((float)$ordenPago->monto_total, 2, '.', ',') }}</strong> para <strong>{{ $ordenPago->proveedor?->nombre_completo ?? '' }}</strong>.
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Tipo de Autorización:</label>
                        <select class="form-control" wire:model="authTipo">
                            <option value="digital">Aprobación Digital (en el sistema)</option>
                            <option value="fisica">Firma Física Documentada</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold">Observaciones / Notas:</label>
                        <textarea class="form-control" rows="2" wire:model="authNotas" placeholder="Ej: OK para pago bancario / cheque acordado..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" wire:click="closeModalAutorizacion">Cancelar</button>
                    <button type="button" class="btn btn-warning font-weight-bold" wire:click="autorizarOrdenPago">
                        <i class="fas fa-check mr-1"></i> Confirmar Autorización
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL 2: COTEJO DEL RESUMEN DEL PROVEEDOR -->
    @if($modalConciliacion)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-tasks mr-2"></i> Cotejo de Resumen del Proveedor</h5>
                    <button type="button" class="close text-white" wire:click="closeModalConciliacion"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted text-sm mb-3">
                        Coteje el estado de cuenta y facturas enviadas por el proveedor contra las facturas e importes de esta Orden de Pago.
                    </p>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Resultado del Cotejo:</label>
                        <select class="form-control" wire:model.live="resumenConciliado">
                            <option value="1">Conforme / Coincide con el resumen del proveedor</option>
                            <option value="0">Discrepancia / Con incidencias (bloquea pago hasta resolver)</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold">Detalle de Incidencias o Notas:</label>
                        <textarea class="form-control" rows="3" wire:model="resumenIncidencias"
                                  placeholder="Indique si hay diferencias en importes, notas de crédito no aplicadas, etc..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" wire:click="closeModalConciliacion">Cancelar</button>
                    <button type="button" class="btn btn-info font-weight-bold" wire:click="guardarConciliacion">
                        <i class="fas fa-save mr-1"></i> Guardar Cotejo
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL 3: REGISTRO DE PAGO REALIZADO (FASE 5) -->
    @if($modalPago)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-receipt mr-2"></i> Registrar Pago Realizado (Fase 5)</h5>
                    <button type="button" class="close text-white" wire:click="closeModalPago"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-light border text-sm mb-3">
                        Saldo autorizado pendiente: <strong>${{ number_format((float)$ordenPago->saldo_pendiente, 2, '.', ',') }}</strong>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="small font-weight-bold">Monto Pagado (*):</label>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                <input type="number" step="0.01" class="form-control text-right font-weight-bold @error('pagoMonto') is-invalid @enderror"
                                       wire:model="pagoMonto">
                            </div>
                            @error('pagoMonto') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="small font-weight-bold">Fecha Efectiva (*):</label>
                            <input type="date" class="form-control form-control-sm @error('pagoFecha') is-invalid @enderror"
                                   wire:model="pagoFecha">
                            @error('pagoFecha') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="small font-weight-bold">Medio de Pago (*):</label>
                            <select class="form-control form-control-sm" wire:model.live="pagoMedioId">
                                @foreach($mediosPago as $mp)
                                    <option value="{{ $mp->id }}">{{ $mp->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($pagoMedioId == 2)
                        <div class="col-md-6 mb-2">
                            <label class="small font-weight-bold">Caja de Egreso (Efectivo):</label>
                            <select class="form-control form-control-sm" wire:model="pagoCajaId">
                                @foreach($cajasAbiertas as $cj)
                                    <option value="{{ $cj->id }}">Caja #{{ $cj->id }} ({{ $cj->turno ?? 'Turno' }})</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="col-md-6 mb-2">
                            <label class="small font-weight-bold">N° Comprobante / Op. Bancaria:</label>
                            <input type="text" class="form-control form-control-sm" wire:model="pagoCodeOp"
                                   placeholder="Ej: Transf. #9482710">
                        </div>
                        <div class="col-md-12 mb-0">
                            <label class="small font-weight-bold">Concepto / Notas:</label>
                            <input type="text" class="form-control form-control-sm" wire:model="pagoConcepto">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" wire:click="closeModalPago">Cancelar</button>
                    <button type="button" class="btn btn-success font-weight-bold" wire:click="registrarPago">
                        <i class="fas fa-check-circle mr-1"></i> Asentar Egreso y Actualizar Saldos
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
