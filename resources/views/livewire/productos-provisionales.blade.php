<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card card-outline card-warning shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap py-2">
            <div>
                <button type="button" class="btn btn-warning font-weight-bold shadow-sm" wire:click="openModal">
                    <i class="fas fa-plus-circle mr-1"></i> Nuevo Producto Provisional
                </button>
            </div>
            <div class="input-group input-group-sm mt-2 mt-md-0" style="max-width: 320px;">
                <input type="text" class="form-control" placeholder="Buscar por nombre o código..."
                       wire:model="query" wire:keydown.enter="search">
                <div class="input-group-append">
                    <button class="btn btn-default" type="button" wire:click="search">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-0 table-responsive">
            <table class="table table-hover table-striped table-bordered align-middle mb-0 text-sm">
                <thead class="thead-light">
                    <tr class="text-center">
                        <th style="width: 70px;">#</th>
                        <th style="width: 150px;">Código</th>
                        <th class="text-left">Descripción / Producto</th>
                        <th style="width: 140px;">Precio Venta</th>
                        <th style="width: 120px;">Costo</th>
                        <th style="width: 150px;">Tipo</th>
                        <th style="width: 110px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($productos as $p)
                        <tr>
                            <td class="text-center font-weight-bold text-muted">{{ $p->id }}</td>
                            <td class="text-center font-weight-bold">
                                <span class="badge badge-light border px-2 py-1">{{ $p->codigo }}</span>
                            </td>
                            <td>
                                <strong>{{ $p->descripcion }}</strong>
                                @if($p->codigo_de_barras)
                                    <small class="text-muted d-block">
                                        <i class="fas fa-barcode mr-1"></i>{{ $p->codigo_de_barras }}
                                    </small>
                                @endif
                            </td>
                            <td class="text-right font-weight-bold text-success" style="font-size: 1.05rem;">
                                $ {{ number_format((float)$p->precio_venta, 2, '.', ',') }}
                            </td>
                            <td class="text-right text-muted">
                                $ {{ number_format((float)($p->costo ?? 0), 2, '.', ',') }}
                            </td>
                            <td class="text-center">
                                <span class="badge badge-warning px-2 py-1 font-weight-bold" title="No controla stock">
                                    <i class="fas fa-tag mr-1"></i> Provisional
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-secondary" wire:click="openModal({{ $p->id }})" title="Editar">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" wire:click="delProd({{ $p->id }})"
                                            wire:confirm="¿Estás seguro de eliminar este producto provisional?" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-tags fa-2x d-block mb-2 text-warning"></i>
                                No hay productos provisionales registrados actualmente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($productos->hasPages())
            <div class="card-footer py-2">
                {{ $productos->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL CREAR / EDITAR PRODUCTO PROVISIONAL -->
    @if ($modal)
        <div class="modal fade show" style="display: block; background-color: rgba(0, 0, 0, 0.5); overflow-y: auto;" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title font-weight-bold">
                            <i class="fas fa-tag mr-2"></i> {{ $producto_id ? 'Editar Producto Provisional' : 'Nuevo Producto Provisional' }}
                        </h5>
                        <button type="button" class="close" wire:click="closeModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-info py-2 px-3 small mb-3">
                            <i class="fas fa-info-circle mr-1"></i> Este producto <strong>no controlará stock</strong> y podrá ser vendido o facturado inmediatamente en las órdenes de venta.
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold small">Nombre o Descripción (*):</label>
                            <input type="text" class="form-control @error('descripcion') is-invalid @enderror"
                                   placeholder="Ej: Lámpara halógena suelta, Aditivo especial..."
                                   wire:model="descripcion" autofocus>
                            @error('descripcion') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold small">Código de Identificación (*):</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('codigo') is-invalid @enderror"
                                       placeholder="Código único" wire:model="codigo">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" wire:click="generarCodigo" title="Generar código automático">
                                        <i class="fas fa-magic mr-1"></i> Auto
                                    </button>
                                </div>
                            </div>
                            @error('codigo') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class="font-weight-bold small">Precio de Venta (*) ($):</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text font-weight-bold">$</span></div>
                                    <input type="number" step="0.01" min="0" class="form-control font-weight-bold @error('precio_venta') is-invalid @enderror"
                                           placeholder="0.00" wire:model="precio_venta">
                                </div>
                                @error('precio_venta') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label class="font-weight-bold small">Costo ($) (Opcional):</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                    <input type="number" step="0.01" min="0" class="form-control @error('costo') is-invalid @enderror"
                                           placeholder="0.00" wire:model="costo">
                                </div>
                                @error('costo') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold small">Categoría:</label>
                            <select class="form-control" wire:model="categoria_id">
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold small">Código de Barras (Opcional):</label>
                            <input type="text" class="form-control" placeholder="Escanear o escribir..." wire:model="codigo_de_barras">
                        </div>
                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancelar</button>
                        <button type="button" class="btn btn-warning font-weight-bold" wire:click="save">
                            <i class="fas fa-save mr-1"></i> {{ $producto_id ? 'Actualizar Producto' : 'Guardar Producto Provisional' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
