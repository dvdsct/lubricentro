<div>
    <div class="card">
        @if (session()->has('success'))
            <div class="alert alert-success m-3">{{ session('success') }}</div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger m-3">{{ session('error') }}</div>
        @endif
        @if ($pedido->estado == 100)
        <div class="card-header bg-danger">Recibido
            @else
            <div class="card-header">

                <button type="button" class="btn btn-success" wire:click='modalProdOn'>
                    <i class="fas fa-plus-circle"></i> Agregar Item
                </button>
                @endif
            </div>
            <!-- TABLA ITEMS CARGADOS  -->
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>

                            <th style="width: 100px">Codigo</th>
                            <th>Producto</th>
                            <th style="width: 100px">Cantidad</th>
                            <th style="width: 250px">Precio unitario de compra</th>
                            <th style="width: 40px">Subtotal</th>
                            <th style="width: 110px">Pedida</th>
                            <th style="width: 110px">Recibida</th>
                            <th style="width: 110px">Pendiente</th>
                            <th style="width: 220px">Recibir ahora</th>
                            <th style="width: 120px">Estado ítem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pedido->items as $i)
                        <tr>
                            <td>{{ $i->id }}</td>
                            <td>{{ $i->productos->descripcion . ' - ' . $i->productos->codigo }}</td>

                            @if ($i->estado == 1)
                            <td><input type="text" class="form-control" style="width: 145px;" placeholder="Ingresar cantidad" wire:model='cantidad' wire:keydown.enter='addCantidad({{ $i->id }})'>
                                @error('cantidad')
                                {{ $message }}
                                @enderror
                            </td>

                            <td><input type="text" class="form-control" placeholder="Ingresar costo" wire:model='precio' wire:keydown.enter='addCantidad({{ $i->id }})'>
                                @error('precio')
                                {{ $message }}
                                @enderror
                            </td>
                            <td></td>
                            @else
                            <td>
                                {{ $i->cantidad }}
                            </td>
                            <td>
                                $ {{ $i->precio }}
                            </td>
                            <td>
                                $ {{ $i->subtotal }}
                            </td>
                            @endif



                            @php
                                $ppi = $ppiByProduct->get($i->producto_id);
                                $pedida = $ppi->cantidad_pedida ?? 0;
                                $recibida = $ppi->cantidad_recibida ?? 0;
                                $pendiente = max(0, intval($pedida) - intval($recibida));
                            @endphp

                            <td>{{ $pedida }}</td>
                            <td>{{ $recibida }}</td>
                            <td>{{ $pendiente }}</td>

                            <td>
                                @if ($pendiente > 0)
                                    <div class="input-group">
                                        <input type="number" min="1" class="form-control" style="max-width: 100px;"
                                               wire:model.defer='receiveQty.{{ $i->producto_id }}' placeholder="Cant.">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" wire:click='recibirItem({{ $i->producto_id }})'>
                                                Recibir
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <span class="badge bg-success">Completado</span>
                                @endif
                            </td>

                            @if ($i->estado == 2)
                            {{-- Si el producto es estado 2 aun no se a recibido --}}
                            <td class="text-right project-actions" style="width: 240px;">
                                <a class="btn btn-secondary btn-sm" wire:click='editProd({{ $i->id }})'>
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                    Editar
                                </a>
                                <a class="btn btn-info btn-sm" wire:click='openHistory({{ $i->producto_id }})' title="Historial de stock">
                                    <i class="fas fa-history"></i>
                                </a>
                                <a class="btn btn-danger btn-sm" wire:click='delProd({{ $i->id }})' wire:confirm="Si borras este articulo tendras que volver a agregarlo, estas seguro?">
                                    <i class="fas fa-trash">
                                    </i>
                                    Eliminar
                                </a>
                            </td>
                            @else
                            <td class="text-right project-actions" style="width: 200px;">
                                <a class="btn btn-info btn-sm" wire:click='openHistory({{ $i->producto_id }})' title="Historial de stock">
                                    <i class="fas fa-history"></i>
                                </a>
                                <a class="btn btn-danger btn-sm" wire:click='delProd({{ $i->id }})' wire:confirm="Si borras este articulo tendras que volver a agregarlo, estas seguro">
                                    <i class="fas fa-trash">
                                    </i>
                                    Eliminar
                                </a>
                            </td>
                            @endif

                            <td>
                                @php
                                    $est = $ppiByProduct->get($i->producto_id)->estado_item ?? 'pendiente';
                                @endphp
                                @if ($est === 'recibido_total')
                                    <span class="badge bg-success">Recibido</span>
                                @elseif ($est === 'recibido_parcial')
                                    <span class="badge bg-warning">Parcial</span>
                                @else
                                    <span class="badge bg-secondary">Pendiente</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            <div class="card-header justify-content-end">
                <div class="text-right">
                    <h3><strong>TOTAL ${{ $total ?? '0.00' }}</strong></h3>
                </div>
            </div>

        </div>


            <div class="row" style="display: flex; justify-content: space-between; align-items: center;">
            <div class="col-md-3">
                <span>Estado del pedido:
                    @if ($pedido->estado === 'cerrado')
                        <span class="badge bg-secondary">Cerrado</span>
                    @elseif ($pedido->estado === 'recibido_total')
                        <span class="badge bg-success">Recibido</span>
                    @elseif ($pedido->estado === 'recibido_parcial')
                        <span class="badge bg-warning">Parcial</span>
                    @else
                        <span class="badge bg-info">Enviado</span>
                    @endif
                </span>
            </div>
            <div class="col-md-9" style="display:flex; justify-content: end;">
            <!-- BOTON DE RECIBIR PEDIDO DE PROVEEDOR -->
            <div class="col-md-3">
                <div class="small-box bg-primary" style="cursor: pointer;" wire:click='$dispatchTo("form-pago","formPago",{ tipo: "proveedor" })'>
                    <div class="inner">
                        <h3 class="m-0">Recibir Pedido</h3>
                        <p> Todo lo detallado llegó</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
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

            <!-- BOTON DE CERRAR PEDIDO -->
            <div class="col-md-3">
                @if ($allDone && $pedido->estado !== 'cerrado')
                <div class="small-box bg-success" style="cursor: pointer;" wire:click='closePedido'>
                    <div class="inner">
                        <h3 class="m-0">Cerrar Pedido</h3>
                        <p> Sin pendientes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-lock"></i>
                    </div>
                </div>
                @elseif($pedido->estado === 'cerrado')
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3 class="m-0">Pedido cerrado</h3>
                            <p> Ya no se puede modificar</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                @endif
            </div>

            <!-- BOTON DE IMPRIMIR PEDIDO A PROVEEDOR -->
                <div class="col-md-3">
                    <a href="{{ route('pdf.pedido', $pedido->id) }}" target="_blank">
                        <div class="small-box bg-warning" style="cursor: pointer;">
                            <div class="inner">
                                <h3 class="m-0">Imprimir</h3>
                                <p>Pedido a proveedor</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-print"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            </div>


        @livewire('form-pago',['orden' => $pedido])


        <!-- MODAL PARA AGREGAR NUEVO ITEM  -->
        @if ($modal == true)
        <div class="modal fade show" id="modal-lg" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
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
                        <div class="pb-2 input-group" style="width: 420px;">
                            <input type="text" wire:model='query' wire:keydown='search' class="float-right form-control" placeholder="Buscar">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <select class="form-control ml-2" style="max-width: 100px;" wire:model='perPage'>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
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
                                <tr wire:click='addedProduct({{ $i->id }})' wire:loading.attr="disabled">
                                    <td style="cursor: pointer;">{{ $i->id }}</td>
                                    <td style="cursor: pointer;">{{ $i->descripcion }} - {{ $i->codigo }}</td>
                                    <td style="cursor: pointer;"></td>
                                    @if ($i->cantidad == 0)
                                    <td><span class="badge bg-danger">{{ $i->cantidad }}</span></td>
                                    @else
                                    <td><span class="badge bg-success">{{ $i->cantidad }}</span></td>
                                    @endif
                                    <td style="cursor: pointer;">$ {{ $i->costo }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        @endif

    </div>


</div>
