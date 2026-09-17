<div>
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        @can('hacerPedido')
                        <div class="mb-2 mb-sm-0">
                            <button type="button" class="btn btn-success font-weight-bold" wire:click="$dispatchTo('add-supplier-order', 'modalSupOrder')">
                                <i class="fas fa-plus-circle mr-1"></i> Nuevo Pedido
                            </button>
                        </div>
                        @else
                        <div></div>
                        @endcan

                        <div class="input-group" style="max-width: 320px;">
                            <input type="text" wire:model.live.debounce.300ms='query' class="form-control" placeholder="Buscar por ID, proveedor, obs...">
                            <div class="input-group-append">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>PROVEEDOR</th>
                                <th>FECHA PEDIDO</th>
                                <th>ESTADO</th>
                                <th>DESCRIPCIÓN</th>
                                <th style="width: 120px;" class="text-right">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pedidos as $p)
                            <tr>
                                <td class="font-weight-bold text-muted">#{{ $p->id }}</td>
                                <td>
                                    <span class="font-weight-bold text-dark">
                                        {{ $p->proveedores?->nombre_fantasia ?: (($p->proveedores?->perfiles?->personas?->nombre ?? '') . ' ' . ($p->proveedores?->perfiles?->personas?->apellido ?? '')) }}
                                    </span>
                                    @if($p->proveedores?->cuit)
                                        <small class="text-muted d-block">CUIT: {{ $p->proveedores->cuit }}</small>
                                    @endif
                                </td>
                                <td>
                                    {{ $p->fecha_ingreso ? \Carbon\Carbon::parse($p->fecha_ingreso)->format('d/m/Y') : ($p->created_at ? $p->created_at->format('d/m/Y') : '-') }}
                                </td>
                                <td>
                                    @if($p->estado == 2 || $p->estado === 'borrador')
                                        <span class="badge badge-secondary"><i class="fas fa-edit mr-1"></i> BORRADOR</span>
                                    @elseif($p->estado == 3 || $p->estado === 'pendiente' || $p->estado === 'enviado')
                                        <span class="badge badge-warning"><i class="far fa-clock mr-1"></i> PENDIENTE</span>
                                    @elseif($p->estado == 4 || $p->estado === 'recibido_total' || $p->estado === 'recibido' || $p->estado === 'cerrado')
                                        <span class="badge badge-success"><i class="fas fa-check mr-1"></i> RECIBIDO</span>
                                    @elseif($p->estado === 'recibido_parcial')
                                        <span class="badge badge-info"><i class="fas fa-boxes mr-1"></i> RECIBIDO PARCIAL</span>
                                    @elseif($p->estado === 'cancelado')
                                        <span class="badge badge-danger"><i class="fas fa-ban mr-1"></i> CANCELADO</span>
                                    @else
                                        <span class="badge badge-secondary">{{ strtoupper(str_replace('_', ' ', $p->estado)) }}</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $p->descripcion ?: ($p->observaciones ?: '-') }}
                                </td>
                                <td class="project-actions text-right">
                                    <a class="btn btn-info btn-sm" href="{{ route('pedidos.show', $p->id) }}" title="Ver / Gestionar pedido">
                                        <i class="fas fa-truck"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm" wire:click='delPedido({{ $p->id }})' wire:confirm="¿Está seguro de eliminar este pedido?" title="Eliminar pedido">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block text-secondary"></i>
                                    No se encontraron pedidos de proveedores.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($pedidos->hasPages())
                <div class="card-footer clearfix d-flex justify-content-end py-2">
                    {{ $pedidos->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
