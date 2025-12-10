<!-- TABLA DE ITEMS AGREGADOS EN LA SECCION DE NUEVA ORDEN -->

<div>
    <div class="card">
        @if ($orden->estado == 100)
            <div class="card-header bg-danger">
                <h5 class="m-0"> <strong> PAGADO </strong> </h5>
            @elseif ($orden->estado == 10)
            <div class="card-header bg-warning">
                <h5 class="m-0"> <strong> PENDIENTE A SER COBRADO </strong> </h5>
            @else
                <div class="card-header" style="display: flex; justify-content: space-between;">


                    <button type="button" class="btn btn-success" wire:click='modalProdOn'>
                        <i class="fas fa-plus-circle"></i> Agregar Item
                    </button>
                    <div class="input-group" style="width: 300px; margin-left: auto;">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-barcode"></i>
                            </span>
                        </div>
    @if($showHistory)
    <div class="modal fade show" style="display:block; background-color: rgba(0,0,0,.5);" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h5 class="modal-title">Historial de stock @if($historyProductoDesc) - {{ $historyProductoDesc }} @endif</h5>
                    <button type="button" class="close" aria-label="Close" wire:click="closeHistory">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Delta</th>
                                <th>Anterior</th>
                                <th>Nuevo</th>
                                <th>Motivo</th>
                                <th>Ref</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($historyMovements as $m)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($m['created_at'])->format('d/m/Y H:i') }}</td>
                                    <td>{{ $m['delta'] }}</td>
                                    <td>{{ $m['cantidad_anterior'] }}</td>
                                    <td>{{ $m['cantidad_nueva'] }}</td>
                                    <td>{{ $m['motivo'] ?? '-' }}</td>
                                    <td>{{ ($m['referencia_type'] ?? '-') }} {{ ($m['referencia_id'] ?? '') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center">Sin movimientos</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" wire:click="closeHistory">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    @endif
                        <input type="text" id="codigoBarrasInput" class="form-control"
                            placeholder="Buscar producto por codigo" wire:model='codigoBarras'
                            wire:keydown.enter='codeBar'>

                        <div class="input-group-append">
                            <button class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        @error('codigoBarras')
                            <span class="danger"> ****</span>
                        @enderror
                    </div>
        @endif


    </div>

    <!-- TABLA ITEMS CARGADOS  -->
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Producto</th>
                    <th style="width: 60px">Precio Unitario</th>
                    <th style="width: 40px">Cantidad</th>
                    <th style="width: 40px">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orden->items as $i)
                    <tr>
                        <td>{{ $i->productos->id }}</td>
                        <td>{{ $i->productos->descripcion }} - {{ $i->productos->codigo }}</td>

                        <td>
                            $ {{ $i->productos->precio_venta }}
                        </td>
                        @if ($i->estado == 1)
                            <td><input type="text" class="form-control" wire:model='cantidad'
                                    wire:keydown.enter='addCantidad({{ $i->id }})'></td>
                        @else
                            <td>
                                {{ $i->cantidad }}
                            </td>
                        @endif
                        <td>
                            $ {{ $i->subtotal }}
                        </td>
                        @if ($orden->estado == 100)
                            <td class="text-right project-actions">
                                <a class="btn btn-info btn-sm" wire:click='openHistory({{ $i->productos->id }})' title="Historial de stock">
                                    <i class="fas fa-history"></i>
                                </a>
                            @else
                            <td class="pl-0 text-right project-actions">
                                {{-- <a class="btn btn-info btn-sm" wire:click='editProd({{ $i->id }})'>
                                <i class="fas fa-pencil-alt">
                                </i>

                                </a> --}}
                                <a class="btn btn-info btn-sm" wire:click='openHistory({{ $i->productos->id }})' title="Historial de stock">
                                    <i class="fas fa-history"></i>
                                </a>
                                <a class="btn btn-danger btn-sm" wire:click='delProd({{ $i->id }})'
                                    wire:confirm="Si borras este articulo tendras que volver a agregarlo">
                                    <i class="fas fa-trash">
                                    </i>

                                </a>
                            </td>
                        @endif
                    </tr>
                @endforeach
                

            </tbody>

        </table>



    </div>
    <div class="card-header justify-content-end">
        <div class="text-right">
            <h3><strong> TOTAL ${{ $total ?? '0.00' }}</strong></h3>
        </div>
    </div>

</div>


<!-- MODAL PARA AGREGAR NUEVO ITEM  -->
@if ($modal == true)
    <div class="modal fade show" id="modal-lg"
        style="display: block; background-color: rgba(0, 0, 0, 0.5); overflow-y: auto;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="m-0"> <strong> AGREGAR ITEM </strong> </h4>
                    <button type="button" class="close" wire:click='modalProdOff'>
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
                    <!-- BUSCADOR DE PRODUCTOS  -->
                    <div class="pb-2 input-group input-group-sm" style="width: 300px;">
                        <input type="text" wire:model='query' wire:keydown='search' class="float-right form-control"
                            placeholder="Buscar">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Producto</th>
                                <th style="width: 40px">Stock</th>
                                <th>Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stock as $i)
                                <tr wire:click='addedProduct({{ $i->producto_id }})' wire:loading.attr="disabled">
                                    <td style="cursor: pointer;">{{ $i->producto_id }}</td>
                                    <td style="cursor: pointer;">{{ $i->descripcion }} - {{ $i->codigo }}</td>
                                    @if ($i->cantidad == 0)
                                        <td><span class="badge bg-danger">{{ $i->cantidad }}</span></td>
                                    @else
                                        <td><span class="badge bg-success">{{ $i->cantidad }}</span></td>
                                    @endif
                                    <td style="cursor: pointer;">$ {{ $i->precio_venta }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif




@script
    <script>
        $wire.on('nonstock', (event) => {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "La cantidad ingresada supera a el Stock actual!",
            });
        });
    </script>
@endscript
</div>
