<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- DATOS DEL PROVEEDOR Y ACCIONES -->
    <div class="card card-outline card-info shadow-sm mb-3">
        <div class="card-header py-2 d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="font-weight-bold mb-0 text-dark">
                <i class="fas fa-warehouse mr-2 text-info"></i>
                CUENTA CORRIENTE: {{ strtoupper($proveedor->nombre_completo) }}
            </h5>
            <div>
                <a href="{{ route('ordenes-pago.create', ['proveedor_id' => $proveedor->id]) }}" class="btn btn-success btn-sm font-weight-bold mr-2">
                    <i class="fas fa-file-invoice-dollar mr-1"></i> Generar Orden de Pago (Ruta B)
                </a>
                <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Volver a Proveedores
                </a>
            </div>
        </div>
        <div class="card-body py-2 px-3 text-sm">
            <div class="row">
                <div class="col-md-3 col-sm-6 mb-1">
                    <span class="text-muted d-block font-weight-bold">CUIT:</span>
                    <span>{{ $proveedor->cuit ?: '-' }}</span>
                </div>
                <div class="col-md-3 col-sm-6 mb-1">
                    <span class="text-muted d-block font-weight-bold">Teléfono:</span>
                    <span>{{ $proveedor->telefono ?: '-' }}</span>
                </div>
                <div class="col-md-3 col-sm-6 mb-1">
                    <span class="text-muted d-block font-weight-bold">Email:</span>
                    <span>{{ $proveedor->email ?: '-' }}</span>
                </div>
                <div class="col-md-3 col-sm-6 mb-1">
                    <span class="text-muted d-block font-weight-bold">Dirección:</span>
                    <span>{{ $proveedor->direccion ?: '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- TARJETAS DE SALDOS Y ESTADO FINANCIERO -->
    <div class="row mb-3">
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="info-box shadow-sm mb-0">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-file-invoice"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-muted">Total Compras</span>
                    <span class="info-box-number font-weight-bold">$ {{ number_format((float)$totalFacturado, 2, '.', ',') }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="info-box shadow-sm mb-0">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-balance-scale"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-muted">Saldo Total Deuda</span>
                    <span class="info-box-number font-weight-bold text-danger">$ {{ number_format((float)$saldoTotal, 2, '.', ',') }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="info-box shadow-sm mb-0">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-lock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-muted">Monto Bloqueado (NC)</span>
                    <span class="info-box-number font-weight-bold text-warning">$ {{ number_format((float)$montoBloqueado, 2, '.', ',') }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="info-box shadow-sm mb-0">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-hand-holding-usd"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-muted">Disponible para Pago</span>
                    <span class="info-box-number font-weight-bold text-success">$ {{ number_format((float)$saldoDisponible, 2, '.', ',') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN DE NOTAS DE CRÉDITO PENDIENTES DE RESOLUCIÓN (ESCENARIO 2) -->
    @if($notasCreditoPendientes->isNotEmpty())
    <div class="card card-outline card-warning shadow-sm mb-4">
        <div class="card-header py-2 bg-warning-light d-flex justify-content-between align-items-center">
            <h6 class="font-weight-bold mb-0 text-dark">
                <i class="fas fa-exclamation-triangle mr-1 text-warning"></i>
                Notas de Crédito Pendientes de Emisión / Reclamadas (Escenario 2)
            </h6>
            <span class="badge badge-warning">{{ $notasCreditoPendientes->count() }} pendiente(s)</span>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-sm table-bordered table-hover mb-0 text-sm">
                <thead class="thead-light">
                    <tr>
                        <th>OC Origen</th>
                        <th>Factura Vinculada</th>
                        <th>Motivo del Faltante</th>
                        <th class="text-right">Monto a Descontar</th>
                        <th>Estado</th>
                        <th class="text-center" style="width: 180px;">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notasCreditoPendientes as $ncp)
                    <tr>
                        <td><strong>OC #{{ $ncp->pedido_proveedor_id ?? '-' }}</strong></td>
                        <td>Factura #{{ $ncp->factura?->numero_factura ?: ($ncp->factura_id ? 'FAC-'.$ncp->factura_id : '-') }}</td>
                        <td>{{ $ncp->motivo }}</td>
                        <td class="text-right font-weight-bold text-danger">$ {{ number_format((float)$ncp->monto, 2, '.', ',') }}</td>
                        <td><span class="badge badge-warning">RECLAMADA / PENDIENTE EMISIÓN</span></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-success btn-xs font-weight-bold" wire:click="openModalNC({{ $ncp->id }})">
                                <i class="fas fa-check-circle mr-1"></i> Cargar NC Recibida
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- SECCIÓN DE FACTURAS PENDIENTES DE PAGO -->
    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-header py-2 d-flex justify-content-between align-items-center">
            <h6 class="font-weight-bold mb-0 text-dark">
                <i class="fas fa-file-invoice mr-1 text-primary"></i> Facturas Pendientes de Pago
            </h6>
            <span class="badge badge-primary">{{ $facturasPendientes->count() }} factura(s)</span>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-bordered table-hover mb-0 text-sm">
                <thead class="thead-light">
                    <tr>
                        <th>Comprobante</th>
                        <th>Fecha Emisión</th>
                        <th>OC Origen</th>
                        <th class="text-right">Total Factura</th>
                        <th class="text-right">Saldo Pendiente</th>
                        <th class="text-right">Bloqueado por NC</th>
                        <th class="text-right">Disponible para Pago</th>
                        <th>Estado</th>
                        <th class="text-center" style="width: 180px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facturasPendientes as $fac)
                    <tr>
                        <td><strong>Factura {{ $fac->tipoFactura?->descripcion ?? '' }} #{{ $fac->numero_factura ?: $fac->id }}</strong></td>
                        <td>{{ $fac->fecha_emision ? \Carbon\Carbon::parse($fac->fecha_emision)->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($fac->pedido_proveedor_id)
                                <a href="{{ route('pedidos.show', $fac->pedido_proveedor_id) }}" class="badge badge-light border">OC #{{ $fac->pedido_proveedor_id }}</a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-right font-weight-bold">$ {{ number_format((float)$fac->total, 2, '.', ',') }}</td>
                        <td class="text-right font-weight-bold text-dark">$ {{ number_format((float)$fac->saldo_pendiente, 2, '.', ',') }}</td>
                        <td class="text-right font-weight-bold text-danger">
                            @if($fac->monto_bloqueado > 0)
                                <span class="badge badge-warning p-1">$ {{ number_format((float)$fac->monto_bloqueado, 2, '.', ',') }}</span>
                            @else
                                $ 0.00
                            @endif
                        </td>
                        <td class="text-right font-weight-bold text-success">$ {{ number_format((float)$fac->monto_disponible_pago, 2, '.', ',') }}</td>
                        <td>
                            @if($fac->estado === 'bloqueada_parcial')
                                <span class="badge badge-warning"><i class="fas fa-lock mr-1"></i> BLOQUEO POR NC</span>
                            @elseif($fac->estado === 'parcial')
                                <span class="badge badge-info">PAGO PARCIAL</span>
                            @else
                                <span class="badge badge-primary">PENDIENTE</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($fac->monto_disponible_pago > 0)
                                <a href="{{ route('ordenes-pago.create', ['proveedor_id' => $proveedor->id, 'factura_id' => $fac->id]) }}" class="btn btn-success btn-xs font-weight-bold">
                                    <i class="fas fa-bolt mr-1"></i> Pago Inmediato (Ruta A)
                                </a>
                            @else
                                <span class="text-muted text-xs">Bloqueado para pago</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-3 text-muted">No hay facturas pendientes de pago para este proveedor.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- LIBRO MAYOR / HISTORIAL DE CUENTA CORRIENTE -->
    <div class="card card-outline card-secondary shadow-sm mb-4">
        <div class="card-header py-2">
            <h6 class="font-weight-bold mb-0 text-dark">
                <i class="fas fa-history mr-1 text-secondary"></i> Historial de Movimientos de Cuenta Corriente
            </h6>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-bordered table-striped table-hover mb-0 text-sm">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 100px;">Fecha</th>
                        <th>Tipo</th>
                        <th>Comprobante</th>
                        <th>Descripción</th>
                        <th class="text-right" style="width: 130px;">Debe (Compras)</th>
                        <th class="text-right" style="width: 130px;">Haber (Pagos/NC)</th>
                        <th class="text-right" style="width: 130px;">Saldo Acumulado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movimientos as $mov)
                    <tr>
                        <td>{{ $mov->fecha ? \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y') : $mov->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($mov->tipo_movimiento === 'factura')
                                <span class="badge badge-primary">FACTURA</span>
                            @elseif($mov->tipo_movimiento === 'nota_credito')
                                <span class="badge badge-warning">NOTA DE CRÉDITO</span>
                            @elseif($mov->tipo_movimiento === 'pago')
                                <span class="badge badge-success">PAGO</span>
                            @else
                                <span class="badge badge-secondary">{{ strtoupper($mov->tipo_movimiento) }}</span>
                            @endif
                        </td>
                        <td class="font-weight-bold">{{ $mov->comprobante_tipo }} {{ $mov->comprobante_numero ? '#' . $mov->comprobante_numero : '' }}</td>
                        <td>{{ $mov->descripcion }}</td>
                        <td class="text-right font-weight-bold text-danger">
                            {{ $mov->debe > 0 ? '$ ' . number_format((float)$mov->debe, 2, '.', ',') : '-' }}
                        </td>
                        <td class="text-right font-weight-bold text-success">
                            {{ $mov->haber > 0 ? '$ ' . number_format((float)$mov->haber, 2, '.', ',') : '-' }}
                        </td>
                        <td class="text-right font-weight-bold text-dark">
                            $ {{ number_format((float)$mov->saldo, 2, '.', ',') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No se registran movimientos en la cuenta corriente.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($movimientos->hasPages())
        <div class="card-footer py-2 d-flex justify-content-end">
            {{ $movimientos->links() }}
        </div>
        @endif
    </div>

    <!-- MODAL PARA CARGAR / APLICAR NOTA DE CRÉDITO RECIBIDA -->
    @if($modalNC)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-file-invoice-dollar mr-2"></i> Cargar y Aplicar Nota de Crédito Recibida</h5>
                    <button type="button" class="close text-white" wire:click="closeModalNC"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted text-sm mb-3">
                        Ingrese los datos de la Nota de Crédito emitida por el proveedor para conciliar la diferencia y desbloquear el saldo para pago.
                    </p>
                    <div class="form-group mb-2">
                        <label class="small font-weight-bold">Número de Nota de Crédito (*):</label>
                        <input type="text" class="form-control form-control-sm @error('ncNumero') is-invalid @enderror"
                               wire:model="ncNumero" placeholder="Ej: NC-0001-00000845">
                        @error('ncNumero') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-2">
                        <label class="small font-weight-bold">Fecha de Emisión (*):</label>
                        <input type="date" class="form-control form-control-sm @error('ncFecha') is-invalid @enderror"
                               wire:model="ncFecha">
                        @error('ncFecha') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-2">
                        <label class="small font-weight-bold">Importe de la NC (*):</label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                            <input type="number" step="0.01" class="form-control form-control-sm text-right font-weight-bold @error('ncMonto') is-invalid @enderror"
                                   wire:model="ncMonto">
                        </div>
                        @error('ncMonto') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-0">
                        <label class="small font-weight-bold">Observaciones:</label>
                        <textarea class="form-control form-control-sm" rows="2" wire:model="ncObservaciones"
                                  placeholder="Notas adicionales..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" wire:click="closeModalNC">Cancelar</button>
                    <button type="button" class="btn btn-success font-weight-bold" wire:click="aplicarNotaCredito">
                        <i class="fas fa-check mr-1"></i> Aplicar y Desbloquear Saldo
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
