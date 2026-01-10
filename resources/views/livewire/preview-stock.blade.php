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
                </div>
                <div class="modal-footer clearfix">
                    <button type="button" class="btn btn-default" wire:click="closeHistory">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="card">
        <div class="card-header">
            <form action="{{ route('pdf.stock') }}" method="GET" target="_blank" class="d-inline w-100">
                <input type="hidden" name="subcategoria_id" value="{{ $subcategoriaId }}">
                <button type="submit" class="small-box bg-warning border-0" style="cursor: pointer; display:block; width:100%;">
                    <div class="inner">
                        <h3 class="m-0">Imprimir</h3>
                        <p>Planilla Sotck</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-print"></i>
                    </div>
                </button>
            </form>
            <div class="card-tools">
                <div class="input-group" style="width: 300px;">
                    <input type="text" wire:model='query' wire:keydown='search' class="float-right form-control" placeholder="Buscar producto">
                    <div class="input-group-append">
                        <button class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="mt-2" style="max-width: 300px;">
                <label for="subcategoriaId" class="mb-1">Filtrar por subcategoría</label>
                <select id="subcategoriaId" class="form-control" wire:model="subcategoriaId">
                    <option value="">Todas las subcategorías</option>
                    @foreach($subcategorias as $s)
                        <option value="{{ $s->id }}">{{ $s->descripcion }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="p-0 card-body">
            <table class="table ">
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
                    @if ($p->cantidad == 0)
                    <tr style="background-color: #f8d7da;">
                        @elseif ($p->cantidad < $p->ideal)
                        <tr style="background-color: #fff3cd;">
                        @else
                    <tr class="">
                        @endif


                        <td>{{ $p->id }}</td>
                        <td>{{ $p->productos->descripcion }} - {{ $p->productos->codigo }}</td>
                        <td>{{ $p->unidad }}</td>

                        <td>
                          
                            {{ $p->cantidad }}

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

</div>