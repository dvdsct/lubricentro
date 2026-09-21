<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3" role="alert">
            <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-header d-flex align-items-center justify-content-between py-2">
            <div>
                <button type="button" class="btn btn-success px-3 shadow-sm font-weight-bold mr-2" wire:click='modalProdOn'
                        @if($pedido->estado === 'cerrado' || $pedido->estado === 'recibido_total') disabled @endif>
                    <i class="fas fa-plus-circle mr-1"></i> Agregar Ítem
                </button>
                <button type="button" class="btn btn-primary px-3 shadow-sm font-weight-bold" wire:click='recibirItems'
                        @if($pedido->estado === 'cerrado' || $pedido->estado === 'recibido_total') disabled @endif>
                    <i class="fas fa-arrow-circle-down mr-1"></i> Recibir
                </button>
            </div>
            <div>
                @if($pedido->estado === 'cerrado')
                    <span class="badge badge-danger px-3 py-2 font-weight-bold"><i class="fas fa-lock mr-1"></i> Cerrado</span>
                @elseif($pedido->estado === 'recibido_total')
                    <span class="badge badge-success px-3 py-2 font-weight-bold"><i class="fas fa-check-double mr-1"></i> Recibido Total</span>
                @elseif($pedido->estado === 'recibido_parcial')
                    <span class="badge badge-warning px-3 py-2 font-weight-bold"><i class="fas fa-hourglass-half mr-1"></i> Recibido Parcial</span>
                @elseif($pedido->estado === 'enviado')
                    <span class="badge badge-info px-3 py-2 font-weight-bold"><i class="fas fa-paper-plane mr-1"></i> Enviado</span>
                @else
                    <span class="badge badge-secondary px-3 py-2 font-weight-bold"><i class="fas fa-clock mr-1"></i> Pendiente</span>
                @endif
            </div>
        </div>

        <!-- TABLA ITEMS CARGADOS  -->
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover table-striped table-bordered align-middle mb-0 text-sm">
                <thead class="thead-light">
                    <tr class="text-center">
                        <th style="width: 70px;">#</th>
                        <th class="text-left">Producto</th>
                        <th style="width: 130px;">Precio Compra</th>
                        <th style="width: 120px;">Cant. Pedida</th>
                        <th style="width: 130px;">Subtotal</th>
                        <th style="width: 140px;">Cant. que deja</th>
                        <th style="width: 110px;">Estado</th>
                        <th style="width: 100px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pedido->items as $i)
                    @php
                        $ppi = $ppiByProduct->get($i->producto_id);
                        $pedida = $ppi->cantidad_pedida ?? 0;
                        $recibida = $ppi->cantidad_recibida ?? 0;
                        $pendiente = max(0, intval($pedida) - intval($recibida));
                        $pedidoCerrado = in_array($pedido->estado, ['cerrado','recibido_total']);
                        $yaRecibio = intval($recibida) > 0;
                        $est = $ppi->estado_item ?? 'pendiente';
                    @endphp
                    <tr>
                        <td class="text-center font-weight-bold text-muted">{{ $i->productos->id }}</td>
                        <td>
                            <strong>{{ $i->productos->descripcion }}</strong>
                            @if($i->productos->codigo && $i->productos->codigo !== '-')
                                <small class="text-muted d-block"><i class="fas fa-barcode mr-1"></i>{{ $i->productos->codigo }}</small>
                            @endif
                        </td>

                        @if ($i->estado == 1)
                        <td class="text-right">
                            <div class="input-group input-group-sm" style="max-width: 130px; margin-left: auto;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number" step="0.01" min="0" class="form-control text-right @error('precio') is-invalid @enderror"
                                       placeholder="0.00" wire:model='precio' wire:keydown.enter='addCantidad({{ $i->id }})'>
                            </div>
                            @error('precio')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </td>
                        <td class="text-center">
                            <div class="input-group input-group-sm" style="max-width: 130px; margin: 0 auto;">
                                <input type="number" min="1" class="form-control text-center @error('cantidad') is-invalid @enderror"
                                       placeholder="Cant." wire:model='cantidad' wire:keydown.enter='addCantidad({{ $i->id }})' autofocus>
                                <div class="input-group-append">
                                    <button class="btn btn-success" wire:click='addCantidad({{ $i->id }})' title="Confirmar">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </div>
                            </div>
                            @error('cantidad')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </td>
                        <td class="text-center text-muted">-</td>
                        @else
                        <td class="text-right">
                            $ {{ number_format((float)($i->precio ?? 0), 2, '.', ',') }}
                        </td>
                        <td class="text-center font-weight-bold">
                            {{ $i->cantidad }}
                        </td>
                        <td class="text-right font-weight-bold text-dark">
                            $ {{ number_format((float)($i->subtotal ?? 0), 2, '.', ',') }}
                        </td>
                        @endif

                        <td class="text-center">
                            @if ($pendiente > 0 && !$pedidoCerrado)
                                <input type="number" min="1" max="{{ $pendiente }}" class="form-control form-control-sm text-center mx-auto" style="max-width: 90px;"
                                       wire:model="receiveQty.{{ $i->producto_id }}" placeholder="0">
                            @else
                                <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Completo</span>
                            @endif
                        </td>

                        <td class="text-center">
                            @if ($est === 'recibido_total')
                                <span class="badge badge-success">Recibido</span>
                            @elseif ($est === 'recibido_parcial')
                                <span class="badge badge-warning">Parcial</span>
                            @else
                                <span class="badge badge-secondary">Pendiente</span>
                            @endif
                        </td>

                        <td class="text-center">
                            @if(!$pedidoCerrado && !$yaRecibio)
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-secondary" wire:click='editProd({{ $i->id }})' title="Editar">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" wire:click='delProd({{ $i->id }})'
                                            wire:confirm="¿Estás seguro de eliminar este ítem?" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-box-open fa-2x d-block mb-2 text-secondary"></i>
                            No hay ítems cargados en esta orden. Haz clic en <strong>Agregar Ítem</strong> para comenzar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-light d-flex justify-content-between align-items-center py-3 flex-wrap">
            <div class="text-muted small mb-2 mb-sm-0">
                <span>Estado actual: <strong>{{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}</strong></span>
            </div>
            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-primary px-4 shadow-sm font-weight-bold mr-4" wire:click='recibirItems'
                        @if($pedido->estado === 'cerrado' || $pedido->estado === 'recibido_total') disabled @endif>
                    <i class="fas fa-arrow-circle-down mr-1"></i> Recibir
                </button>
                <h4 class="mb-0 text-dark">
                    <strong>TOTAL:</strong>
                    <span class="text-success ml-2 font-weight-bold">${{ number_format((float)($total ?? 0), 2, '.', ',') }}</span>
                </h4>
            </div>
        </div>
    </div>

    <!-- ACCIONES DE LA ORDEN DE COMPRA -->
    <div class="row">
        <!-- BOTON DE RECIBIR ORDEN DE COMPRA COMPLETA -->
        <div class="col-md-4 mb-3">
            <div class="card card-outline card-primary shadow-sm h-100 mb-0" style="cursor: pointer; transition: transform .15s ease;"
                 wire:click='$dispatchTo("form-pago","formPago",{ tipo: "proveedor" })'
                 onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                <div class="card-body d-flex align-items-center">
                    <div class="mr-3 text-primary">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="font-weight-bold mb-0 text-dark">Recibir Orden Completa</h6>
                        <small class="text-muted">Marcar todo lo detallado como recibido e ingresar stock</small>
                    </div>
                </div>
            </div>
        </div>

        @php
            $allDone = true;
            foreach ($ppiByProduct as $ppi) {
                $ped = intval($ppi->cantidad_pedida ?? 0);
                $rec = intval($ppi->cantidad_recibida ?? 0);
                if ($rec < $ped) { $allDone = false; break; }
            }
        @endphp

        <!-- BOTON DE CERRAR ORDEN -->
        <div class="col-md-4 mb-3">
            @if ($allDone && $pedido->estado !== 'cerrado')
                <div class="card card-outline card-success shadow-sm h-100 mb-0" style="cursor: pointer; transition: transform .15s ease;"
                     wire:click='closePedido' onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                    <div class="card-body d-flex align-items-center">
                        <div class="mr-3 text-success">
                            <i class="fas fa-lock fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="font-weight-bold mb-0 text-dark">Cerrar Orden</h6>
                            <small class="text-muted">Sin pendientes. Finalizar orden</small>
                        </div>
                    </div>
                </div>
            @elseif($pedido->estado === 'cerrado')
                <div class="card card-outline card-secondary shadow-sm h-100 mb-0 bg-light">
                    <div class="card-body d-flex align-items-center text-muted">
                        <div class="mr-3">
                            <i class="fas fa-lock fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="font-weight-bold mb-0">Orden Cerrada</h6>
                            <small>Ya no se pueden realizar modificaciones</small>
                        </div>
                    </div>
                </div>
            @else
                <div class="card card-outline card-secondary shadow-sm h-100 mb-0 bg-light">
                    <div class="card-body d-flex align-items-center text-muted">
                        <div class="mr-3">
                            <i class="fas fa-lock fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="font-weight-bold mb-0">Cerrar Orden</h6>
                            <small>Disponible una vez recibidos todos los ítems</small>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- BOTON DE IMPRIMIR ORDEN DE COMPRA -->
        <div class="col-md-4 mb-3">
            <a href="{{ route('pdf.pedido', $pedido->id) }}" target="_blank" class="text-decoration-none">
                <div class="card card-outline card-warning shadow-sm h-100 mb-0" style="transition: transform .15s ease;"
                     onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                    <div class="card-body d-flex align-items-center">
                        <div class="mr-3 text-warning">
                            <i class="fas fa-print fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="font-weight-bold mb-0 text-dark">Imprimir Orden de Compra</h6>
                            <small class="text-muted">Descargar comprobante en PDF</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    @livewire('form-pago',['orden' => $pedido])

    <!-- MODAL PARA AGREGAR NUEVO ITEM  -->
    @if ($modal == true)
    <div class="modal fade show" id="modal-lg" style="display: block; background-color: rgba(0, 0, 0, 0.5);" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title font-weight-bold mb-0">
                        <i class="fas fa-search-plus mr-2"></i> AGREGAR ÍTEM
                    </h5>
                    <button type="button" class="close text-white" wire:click='modalProdOff'>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-3" style="max-height: 75vh; overflow-y: auto;">
                    <!-- BUSCADOR DE PRODUCTOS  -->
                    <div class="row mb-3">
                        <div class="col-md-8 col-sm-8 mb-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" wire:model='query' wire:keydown='search' class="form-control" placeholder="Buscar por código o descripción...">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 mb-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text small">Mostrar</span>
                                </div>
                                <select class="form-control" wire:model='perPage'>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped text-sm mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 60px" class="text-center">#</th>
                                    <th>Producto</th>
                                    <th style="width: 80px" class="text-center">Stock</th>
                                    <th style="width: 120px" class="text-right">Precio Costo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stock as $i)
                                <tr wire:click='addedProduct({{ $i->producto_id }})' wire:loading.attr="disabled" style="cursor: pointer;">
                                    <td class="text-center text-muted font-weight-bold">{{ $i->producto_id }}</td>
                                    <td>
                                        <strong>{{ $i->descripcion }}</strong>
                                        @if($i->codigo && $i->codigo !== '-')
                                            <small class="text-muted d-block"><i class="fas fa-barcode mr-1"></i>{{ $i->codigo }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($i->cantidad == 0)
                                            <span class="badge badge-danger px-2 py-1">{{ $i->cantidad }}</span>
                                        @else
                                            <span class="badge badge-success px-2 py-1">{{ $i->cantidad }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right font-weight-bold text-dark">$ {{ number_format((float)($i->costo ?? 0), 2, '.', ',') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted">No se encontraron productos coincidentes.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 d-flex justify-content-center">
                        {{ $stock->links() }}
                    </div>
                </div>
                <div class="modal-footer py-2 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" wire:click='modalProdOff'>
                        <i class="fas fa-times mr-1"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
