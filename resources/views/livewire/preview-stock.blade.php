<div class="pt-3">
    <h3> <strong> STOCK </strong> </h3>
    @if ($showHistory)
    <div class="modal fade show" id="modal-stock-history" style="display:block; background-color: rgba(0,0,0,0.5);" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h5 class="modal-title">Historial de stock @if($historyProductoDesc) - {{ $historyProductoDesc }} @endif</h5>
                    <button type="button" class="close" aria-label="Close" wire:click="closeHistory">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Cajero</th>
                                <th>Tipo de movimiento</th>
                                <th>Delta</th>
                                <th>(antes → nuevo)</th>
                                <th>Precio Unit.</th>
                                <th>Monto</th>
                                <th>Referencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($historyMovements as $m)
                                @php
                                    $isIngreso = intval($m['delta']) > 0;
                                    $color = $isIngreso ? 'text-success' : 'text-danger';
                                    $signo = $isIngreso ? '+' : '';
                                    $tipo = $m['operacion'] ?? ($m['motivo'] ?? ($isIngreso ? 'Ingreso' : 'Egreso'));
                                @endphp
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($m['created_at'])->format('d/m/Y H:i') }}</td>
                                    <td>{{ $m['user']['name'] ?? '-' }}</td>
                                    <td>{{ $tipo }}</td>
                                    <td class="{{ $color }}">{{ $signo }}{{ $m['delta'] }}</td>
                                    <td>{{ $m['cantidad_anterior'] }} → {{ $m['cantidad_nueva'] }}</td>
                                    <td>{{ isset($m['precio_unitario']) ? number_format($m['precio_unitario'], 2) : '-' }}</td>
                                    <td class="{{ $color }}">{{ isset($m['monto_total']) ? number_format($m['monto_total'], 2) : '-' }}</td>
                                    <td>{{ ($m['referencia_type'] ?? '-') }} {{ ($m['referencia_id'] ?? '') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center">Sin movimientos</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $historyMovements->links() }}
                    </div>
                </div>
                <div class="modal-footer clearfix">
                    <button type="button" class="btn btn-default" wire:click="closeHistory">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($showFractionModal)
    <div class="modal fade show" id="modal-stock-fraction" style="display:block; background-color: rgba(0,0,0,0.5);" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-box-open mr-2"></i> Fraccionar / Desarmar a Granel</h5>
                    <button type="button" class="close" aria-label="Close" wire:click="closeFractionModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">
                        Permite desarmar o abrir envases (ej. Tambor 205L, Bidón 20L o 4L) e ingresarlos como litros fraccionados al stock de aceite a granel.
                    </p>

                    <div class="row">
                        <!-- PRODUCTO ORIGEN (ENVASES) -->
                        <div class="col-md-6 border-right">
                            <h6 class="font-weight-bold text-danger"><i class="fas fa-arrow-circle-up mr-1"></i> 1. Producto Origen (A descontar)</h6>
                            
                            <div class="form-group mb-2">
                                <label class="mb-1 text-sm">Buscar envase / tambor:</label>
                                <input type="text" class="form-control form-control-sm" placeholder="Filtrar por nombre o código..." wire:model.live.debounce.300ms="searchSource">
                            </div>

                            <div class="form-group mb-2">
                                <label class="mb-1 text-sm font-weight-bold">Seleccionar producto:</label>
                                <select class="form-control" wire:model.live="sourceProductoId">
                                    <option value="">-- Seleccionar producto origen --</option>
                                    @foreach ($sourceProducts as $sp)
                                        <option value="{{ $sp->id }}">{{ $sp->descripcion }} ({{ $sp->codigo }})</option>
                                    @endforeach
                                </select>
                                @error('sourceProductoId') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                            </div>

                            @if($sourceProductoId)
                                <div class="alert alert-info py-1 px-2 text-sm mb-2">
                                    <strong>Stock disponible:</strong> {{ $sourceStock }} u.
                                </div>
                            @endif

                            <div class="form-group mb-2">
                                <label class="mb-1 text-sm font-weight-bold">Cantidad de envases a fraccionar:</label>
                                <input type="number" step="0.001" min="0.001" class="form-control" wire:model.live="sourceQty" placeholder="Ej: 1">
                                @error('sourceQty') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- PRODUCTO DESTINO (GRANEL) -->
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-success"><i class="fas fa-arrow-circle-down mr-1"></i> 2. Producto Destino (A ingresar)</h6>

                            <div class="form-group mb-2">
                                <label class="mb-1 text-sm">Buscar aceite granel:</label>
                                <input type="text" class="form-control form-control-sm" placeholder="Filtrar por nombre o código..." wire:model.live.debounce.300ms="searchDest">
                            </div>

                            <div class="form-group mb-2">
                                <label class="mb-1 text-sm font-weight-bold">Seleccionar producto a granel:</label>
                                <select class="form-control" wire:model.live="destProductoId">
                                    <option value="">-- Seleccionar producto destino --</option>
                                    @foreach ($destProducts as $dp)
                                        <option value="{{ $dp->id }}">{{ $dp->descripcion }} ({{ $dp->codigo }})</option>
                                    @endforeach
                                </select>
                                @error('destProductoId') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                            </div>

                            @if($destProductoId)
                                <div class="alert alert-info py-1 px-2 text-sm mb-2">
                                    <strong>Stock actual en granel:</strong> {{ $destStock }} L
                                </div>
                            @endif

                            <div class="form-group mb-2">
                                <label class="mb-1 text-sm font-weight-bold">Litros por envase:</label>
                                <input type="number" step="0.001" min="0.001" class="form-control" wire:model.live="litrosPorEnvase" placeholder="Ej: 205">
                                @error('litrosPorEnvase') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- MOTIVO & RESUMEN -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-2">
                                <label class="mb-1 text-sm">Motivo / Observaciones:</label>
                                <input type="text" class="form-control form-control-sm" wire:model="fractionMotivo" placeholder="Ej: Apertura de tambor a granel">
                                @error('fractionMotivo') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    @if($sourceProductoId && $destProductoId && floatval($sourceQty) > 0 && floatval($litrosPorEnvase) > 0)
                        @php
                            $totLitros = floatval(str_replace(',', '.', (string)$sourceQty)) * floatval(str_replace(',', '.', (string)$litrosPorEnvase));
                        @endphp
                        <div class="callout callout-warning mt-2 mb-0">
                            <h6 class="font-weight-bold mb-1"><i class="fas fa-calculator mr-1"></i> Resumen del movimiento:</h6>
                            <ul class="mb-0 pl-3">
                                <li>Se restarán <strong>{{ $sourceQty }}</strong> envase(s) del stock de origen.</li>
                                <li>Se sumarán <strong>+{{ number_format($totLitros, 3, '.', '') }} Litros</strong> al stock de destino.</li>
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeFractionModal">Cancelar</button>
                    <button type="button" class="btn btn-warning font-weight-bold" wire:click="confirmFraction">
                        <i class="fas fa-check mr-1"></i> Confirmar Fraccionamiento
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-4 mb-2 mb-md-0">
                    <button type="button" class="btn btn-warning font-weight-bold mr-2" wire:click="openFractionModal">
                        <i class="fas fa-box-open mr-1"></i> Fraccionar a Granel
                    </button>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <form action="{{ route('pdf.stock') }}" method="GET" target="_blank" class="d-inline">
                        <input type="hidden" name="subcategoria_id" value="{{ $subcategoriaId }}">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-print mr-1"></i> Imprimir Planilla Stock
                        </button>
                    </form>
                </div>
                <div class="col-md-4 text-md-right">
                    <div class="input-group">
                        <input type="text" wire:model='query' wire:keydown='search' class="form-control" placeholder="Buscar producto">
                        <div class="input-group-append">
                            <button class="btn btn-default" type="button" wire:click="search">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-2" style="max-width: 300px;">
                <label for="subcategoriaId" class="mb-1">Filtrar por subcategoría</label>
                <select id="subcategoriaId" class="form-control" wire:model="subcategoriaId" wire:change="subcategoriaChanged($event.target.value)">
                    <option value="">Todas las subcategorías</option>
                    @foreach($subcategorias as $s)
                        <option value="{{ $s->id }}">{{ $s->descripcion }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="p-0 card-body">
            <table class="table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Unidad</th>
                        <th>Cantidad</th>
                        <th>Traza</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($stock as $p)
                    @php
                        $rowStyle = '';
                        if ($p->cantidad == 0) {
                            $rowStyle = 'background-color: #f8d7da;';
                        } elseif ($p->cantidad < $p->ideal) {
                            $rowStyle = 'background-color: #fff3cd;';
                        }
                    @endphp
                    <tr style="{{ $rowStyle }}" wire:key="stock-row-{{ $p->id }}">


                        <td>{{ $p->id }}</td>
                        <td>{{ $p->productos->descripcion }} - {{ $p->productos->codigo }}</td>
                        <td>{{ $p->unidad }}</td>

                        <td>
                            @if ($p->estado == '2')
                                <div class="input-group input-group-sm" style="width: 140px;">
                                    <input type="number" step="0.001" class="form-control" 
                                           wire:model="cantidad" 
                                           wire:keydown.enter="addCantidad({{ $p->id }})">
                                    <div class="input-group-append">
                                        <button class="btn btn-success" type="button" wire:click="addCantidad({{ $p->id }})">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <span style="cursor: pointer;" class="badge badge-secondary p-2" wire:click="editPStock({{ $p->id }})" title="Editar stock">
                                    {{ $p->cantidad }} <i class="fas fa-edit ml-1 text-xs"></i>
                                </span>
                            @endif
                        </td>

                        <td>
                            <button class="btn btn-info btn-sm" wire:click="openHistory({{ $p->id }})" title="Ver trazabilidad">
                                <i class="fas fa-stream"></i>
                            </button>
                        </td>

                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $stock->links() }}
        </div>

    </div>

    @script
    <script>
        $wire.on('stock-fractioned', () => {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Fraccionamiento exitoso!',
                    text: 'El stock fue actualizado y registrado en la trazabilidad.',
                    timer: 2500,
                    showConfirmButton: false
                });
            }
        });
    </script>
    @endscript
</div>