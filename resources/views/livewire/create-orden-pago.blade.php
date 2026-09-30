<div>
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3" role="alert">
            <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- FORMULARIO DE SELECCIÓN Y FACTURAS -->
        <div class="col-lg-8">
            <div class="card card-outline card-primary shadow-sm mb-4">
                <div class="card-header py-2">
                    <h5 class="card-title font-weight-bold mb-0">
                        <i class="fas fa-file-invoice-dollar mr-2 text-primary"></i> 4. Generar Orden de Pago
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2">
                            <label class="font-weight-bold">Proveedor (*):</label>
                            <select class="form-control @error('proveedor_id') is-invalid @enderror" wire:model.live="proveedor_id">
                                <option value="">-- Seleccionar Proveedor --</option>
                                @foreach($proveedores as $prov)
                                    <option value="{{ $prov->id }}">{{ $prov->nombre_completo }} {{ $prov->cuit ? '(' . $prov->cuit . ')' : '' }}</option>
                                @endforeach
                            </select>
                            @error('proveedor_id') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="font-weight-bold">Fecha de Emisión (*):</label>
                            <input type="date" class="form-control @error('fechaEmision') is-invalid @enderror" wire:model="fechaEmision">
                            @error('fechaEmision') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="font-weight-bold">Observaciones:</label>
                            <input type="text" class="form-control" wire:model="observaciones" placeholder="Opcional...">
                        </div>
                    </div>

                    @if($proveedor_id)
                        <h6 class="font-weight-bold text-dark mt-4 mb-2">
                            <i class="fas fa-list-check mr-1 text-info"></i> Facturas Pendientes del Proveedor
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0 text-sm">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 40px;" class="text-center">#</th>
                                        <th>Factura</th>
                                        <th>Fecha</th>
                                        <th class="text-right">Total Factura</th>
                                        <th class="text-right">Bloqueado NC</th>
                                        <th class="text-right">Disponible</th>
                                        <th style="width: 170px;" class="text-center">Monto a Pagar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($facturas as $f)
                                    @php
                                        $disponible = $f->monto_disponible_pago;
                                        $bloqueado = $f->monto_bloqueado > 0;
                                    @endphp
                                    <tr class="{{ !empty($selectedFacturas[$f->id]) ? 'table-primary' : '' }}">
                                        <td class="text-center">
                                            <input type="checkbox" wire:model.live="selectedFacturas.{{ $f->id }}"
                                                   wire:change="toggleFactura({{ $f->id }})"
                                                   @if($disponible <= 0) disabled @endif>
                                        </td>
                                        <td>
                                            <strong>Factura {{ $f->tipoFactura?->descripcion ?? '' }} #{{ $f->numero_factura ?: $f->id }}</strong>
                                            @if($f->pedido_proveedor_id)
                                                <small class="text-muted d-block">OC #{{ $f->pedido_proveedor_id }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $f->fecha_emision ? \Carbon\Carbon::parse($f->fecha_emision)->format('d/m/Y') : '-' }}</td>
                                        <td class="text-right font-weight-bold">$ {{ number_format((float)$f->total, 2, '.', ',') }}</td>
                                        <td class="text-right font-weight-bold text-danger">
                                            @if($f->monto_bloqueado > 0)
                                                <span class="badge badge-warning p-1">$ {{ number_format((float)$f->monto_bloqueado, 2, '.', ',') }}</span>
                                            @else
                                                $ 0.00
                                            @endif
                                        </td>
                                        <td class="text-right font-weight-bold text-success">$ {{ number_format((float)$disponible, 2, '.', ',') }}</td>
                                        <td>
                                            @if($disponible > 0)
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                                    <input type="number" step="0.01" min="0.01" max="{{ $disponible }}"
                                                           class="form-control text-right font-weight-bold"
                                                           wire:model.live.debounce.300ms="montosImputar.{{ $f->id }}"
                                                           @if(empty($selectedFacturas[$f->id])) disabled @endif>
                                                </div>
                                                @error('monto_' . $f->id) <span class="text-danger text-xs d-block">{{ $message }}</span> @enderror
                                            @else
                                                <span class="badge badge-secondary p-1">Bloqueado por NC</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-3 text-muted">Este proveedor no registra facturas pendientes de pago.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-light border text-center py-4 text-muted">
                            <i class="fas fa-arrow-up fa-2x mb-2 d-block text-secondary"></i>
                            Seleccione un proveedor para listar sus facturas y armar la Orden de Pago.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- RESUMEN FINANCIERO Y DISTRIBUCIÓN -->
        <div class="col-lg-4">
            <div class="card card-outline card-success shadow-sm mb-4">
                <div class="card-header py-2 bg-light">
                    <h6 class="font-weight-bold mb-0 text-dark">
                        <i class="fas fa-calculator mr-1 text-success"></i> Resumen de Cuenta Corriente
                    </h6>
                </div>
                <div class="card-body">
                    @if($proveedor)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Deuda Total Proveedor:</span>
                            <span class="font-weight-bold">$ {{ number_format((float)$saldoTotalProveedor, 2, '.', ',') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Monto Bloqueado (NC):</span>
                            <span class="font-weight-bold text-danger">- $ {{ number_format((float)$montoBloqueadoProveedor, 2, '.', ',') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                            <span class="text-muted">Disponible para Pago:</span>
                            <span class="font-weight-bold text-success">$ {{ number_format((float)$saldoDisponibleProveedor, 2, '.', ',') }}</span>
                        </div>

                        <div class="alert alert-primary py-2 px-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="font-weight-bold">TOTAL ORDEN DE PAGO:</span>
                                <h4 class="font-weight-bold mb-0 text-white">$ {{ number_format((float)$totalAImputarCalculado, 2, '.', ',') }}</h4>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mb-3 text-sm">
                            <span class="text-muted">Saldo remanente en Cta. Cte.:</span>
                            <span class="font-weight-bold text-dark">$ {{ number_format((float)$saldoRemanenteEstimado, 2, '.', ',') }}</span>
                        </div>

                        <button type="button" class="btn btn-success btn-block btn-lg font-weight-bold shadow-sm"
                                wire:click="guardarOrdenPago"
                                @if($totalAImputarCalculado <= 0) disabled @endif>
                            <i class="fas fa-check-circle mr-1"></i> Generar Orden de Pago
                        </button>
                    @else
                        <p class="text-muted text-sm text-center mb-0">Seleccione un proveedor para visualizar la proyección de saldos.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
