<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="mb-2 mb-sm-0">
                    <a href="{{ route('ordenes-pago.create') }}" class="btn btn-success font-weight-bold shadow-sm">
                        <i class="fas fa-plus-circle mr-1"></i> Nueva Orden de Pago
                    </a>
                </div>

                <div class="d-flex flex-wrap align-items-center">
                    <!-- FILTRO POR ESTADO -->
                    <select class="form-control form-control-sm mr-2 mb-1" style="max-width: 200px;" wire:model.live="filtroEstado">
                        <option value="">Todos los Estados</option>
                        <option value="pendiente_autorizacion">Pendiente Autorización</option>
                        <option value="autorizada">Autorizada</option>
                        <option value="parcialmente_pagada">Parcialmente Pagada</option>
                        <option value="pagada">Pagada</option>
                    </select>

                    <!-- BUSCADOR -->
                    <div class="input-group input-group-sm mb-1" style="max-width: 280px;">
                        <input type="text" wire:model.live.debounce.300ms="query" class="form-control" placeholder="Buscar por OP, proveedor, obs...">
                        <div class="input-group-append">
                            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0 table-responsive">
            <table class="table table-hover table-striped table-bordered align-middle mb-0 text-sm">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 120px;">N° ORDEN</th>
                        <th>PROVEEDOR</th>
                        <th>FECHA EMISIÓN</th>
                        <th class="text-right">TOTAL AUTORIZADO</th>
                        <th class="text-right">TOTAL PAGADO</th>
                        <th class="text-right">SALDO PENDIENTE</th>
                        <th>ESTADO</th>
                        <th style="width: 120px;" class="text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ordenes as $op)
                    <tr>
                        <td class="font-weight-bold text-dark">
                            <i class="fas fa-file-invoice-dollar mr-1 text-primary"></i>
                            {{ $op->numero ?: ('OP-' . $op->id) }}
                        </td>
                        <td>
                            <strong>{{ $op->proveedor?->nombre_completo ?? 'Proveedor' }}</strong>
                            @if($op->proveedor?->cuit)
                                <small class="text-muted d-block">CUIT: {{ $op->proveedor->cuit }}</small>
                            @endif
                        </td>
                        <td>{{ $op->fecha_emision ? \Carbon\Carbon::parse($op->fecha_emision)->format('d/m/Y') : '-' }}</td>
                        <td class="text-right font-weight-bold text-dark">$ {{ number_format((float)$op->monto_total, 2, '.', ',') }}</td>
                        <td class="text-right font-weight-bold text-success">$ {{ number_format((float)$op->monto_pagado, 2, '.', ',') }}</td>
                        <td class="text-right font-weight-bold {{ $op->saldo_pendiente > 0 ? 'text-danger' : 'text-muted' }}">
                            $ {{ number_format((float)$op->saldo_pendiente, 2, '.', ',') }}
                        </td>
                        <td>
                            @if($op->estado === 'pagada')
                                <span class="badge badge-success px-2 py-1"><i class="fas fa-check-double mr-1"></i> PAGADA</span>
                            @elseif($op->estado === 'parcialmente_pagada')
                                <span class="badge badge-info px-2 py-1"><i class="fas fa-hourglass-half mr-1"></i> PAGO PARCIAL</span>
                            @elseif($op->estado === 'autorizada')
                                <span class="badge badge-primary px-2 py-1"><i class="fas fa-check mr-1"></i> AUTORIZADA</span>
                            @elseif($op->estado === 'pendiente_autorizacion')
                                <span class="badge badge-warning px-2 py-1"><i class="fas fa-user-clock mr-1"></i> PENDIENTE AUTORIZACIÓN</span>
                            @else
                                <span class="badge badge-secondary px-2 py-1">{{ strtoupper(str_replace('_', ' ', $op->estado)) }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('ordenes-pago.show', $op->id) }}" class="btn btn-info btn-sm font-weight-bold" title="Gestionar Orden de Pago">
                                <i class="fas fa-eye mr-1"></i> Ver
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x d-block mb-2 text-secondary"></i>
                            No se encontraron órdenes de pago registradas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($ordenes->hasPages())
        <div class="card-footer py-2 d-flex justify-content-end">
            {{ $ordenes->links() }}
        </div>
        @endif
    </div>
</div>
