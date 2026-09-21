<div>
    <!-- MODAL PARA ABRIR CAJA DEL DIA -->
    @can('caja')
    @if ($modalAbrirCaja)
    <div class="modal fade show" id="modal-default" aria-modal="true" role="dialog" style="padding-right: 17px; background-color: rgba(0, 0, 0, 0.5); display: block;" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-info text-white">
                    <h4 class="modal-title font-weight-bold mb-0">
                        <i class="fas fa-cash-register mr-2"></i> APERTURA DE CAJA
                    </h4>
                    <button type="button" class="close text-white" aria-label="Close" wire:click="cerrarModal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div>
                        @if ($step == 1)
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="sucursal" class="font-weight-bold small text-muted">Sucursal</label>
                                <input type="text" class="form-control bg-light" value="Rocket - Suc. Lugones" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cajero" class="font-weight-bold small text-muted">Cajero</label>
                                <input type="text" class="form-control bg-light" value="{{ $perfil->first()?->personas?->nombre ?? 'Cajero' }}" readonly>
                            </div>
                        </div>

                        <!-- SELECCIÓN DE CUENTA / BANCO DONDE VAN LOS PAGOS -->
                        <div class="form-group mb-3">
                            <label for="bancoId" class="font-weight-bold">
                                <i class="fas fa-university mr-1 text-primary"></i> Cuenta donde van los pagos <span class="text-danger">*</span>
                            </label>
                            <select class="form-control @error('bancoId') is-invalid @enderror" wire:model="bancoId" id="bancoId">
                                <option value="">Seleccionar cuenta para acreditación...</option>
                                @foreach ($bancos as $b)
                                    <option value="{{ $b->id }}">{{ $b->descripcion }} ({{ $b->sucursal_banco }})</option>
                                @endforeach
                            </select>
                            @error('bancoId')
                                <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Seleccione la cuenta bancaria donde se imputarán los cobros y transferencias de esta sesión.
                            </small>
                        </div>

                        <!-- MONTO INICIAL EN EFECTIVO -->
                        <div class="form-group mb-0">
                            <label for="montoInicial" class="font-weight-bold">
                                <i class="fas fa-money-bill-wave mr-1 text-success"></i> Monto Inicial en Efectivo ($) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text font-weight-bold">$</span>
                                </div>
                                <input type="number" step="0.01" min="0" class="form-control @error('montoInicial') is-invalid @enderror"
                                       wire:model='montoInicial' wire:keydown.enter='abrirCaja' placeholder="0.00">
                            </div>
                            @error('montoInicial')
                                <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        @endif

                        @if ($step == 2)
                        @php
                            $bancoSeleccionado = $bancos->firstWhere('id', $bancoId);
                        @endphp
                        <div class="row mt-2" style="display: flex; justify-content: center;">
                            <div class="col-lg-10" style="cursor: pointer;" wire:click='abrirCaja'>
                                <div class="small-box bg-info shadow">
                                    <div class="inner p-3">
                                        <p class="m-0 text-white-50">Monto inicial en caja</p>
                                        <h3 class="mb-2">${{ number_format((float)$montoInicial, 2, '.', ',') }}</h3>
                                        @if($bancoSeleccionado)
                                            <div class="pt-2 border-top border-white-50">
                                                <small class="d-block text-white">
                                                    <i class="fas fa-university mr-1"></i> Cuenta: <strong>{{ $bancoSeleccionado->descripcion }}</strong>
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-cash-register"></i>
                                    </div>
                                    <a href="#" class="small-box-footer font-weight-bold py-2">
                                        Confirmar y Abrir Caja <i class="fas fa-arrow-circle-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer justify-content-between bg-light py-2">
                    <button type="button" class="btn btn-secondary" wire:click="cerrarModal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-success font-weight-bold px-4" wire:click='abrirCaja'>
                        <i class="fas fa-check mr-1"></i> Aceptar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endcan

    @can('adminCajas')
    @livewire('indicadores-ventas')

    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title font-weight-bold mb-0">
                            <i class="fas fa-history mr-1"></i> Historial de Cajas
                        </h3>
                        <div class="input-group input-group-sm" style="width: 300px;">
                            <input type="text" wire:model.live.debounce.300ms='query' class="form-control" placeholder="Buscar por ID, cajero, cuenta...">
                            <div class="input-group-append">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-striped mb-0 text-sm">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 70px;">ID</th>
                                <th>FECHA</th>
                                <th>CAJERO</th>
                                <th>CUENTA ASIGNADA</th>
                                <th>RENDICIÓN</th>
                                <th>VENTA</th>
                                <th>GASTOS</th>
                                <th>OBSERVACIONES</th>
                                <th style="width: 80px;" class="text-right">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cajas as $c)
                            <tr>
                                <td class="font-weight-bold text-muted">#{{ $c->id }}</td>
                                <td>{{ \Carbon\Carbon::parse($c->created_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge badge-info px-2 py-1 font-weight-bold">
                                        {{ $c->cajeros?->perfiles?->personas?->nombre ?? 'Cajero' }}
                                    </span>
                                </td>
                                <td>
                                    @if($c->bancos)
                                        <span class="text-dark font-weight-bold">
                                            <i class="fas fa-university text-primary mr-1"></i>{{ $c->bancos->descripcion }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="font-weight-bold">{{ $c->rendicion ? '$' . number_format((float)$c->rendicion, 2) : '-' }}</td>
                                <td class="text-success font-weight-bold">{{ $c->venta ? '$' . number_format((float)$c->venta, 2) : '$0.00' }}</td>
                                <td class="text-danger font-weight-bold">{{ $c->gastos ? '$' . number_format((float)$c->gastos, 2) : '$0.00' }}</td>
                                <td>{{ $c->observaciones ?: '-' }}</td>
                                <td class="project-actions text-right">
                                    <a class="btn btn-primary btn-sm" href="{{ route('venta.show', $c->id) }}" title="Ver movimientos">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block text-secondary"></i>
                                    No se encontraron registros de caja.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>
