<div>
    <div class="card">
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



                            @if ($i->estado == 2)
                            {{-- Si el producto es estado 2 aun no se a recibido --}}
                            <td class="project-actions text-right" style="width: 200px;">
                                <a class="btn btn-info btn-sm" wire:click='editProd({{ $i->id }})'>
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                    Editar
                                </a>
                                <a class="btn btn-danger btn-sm" wire:click='delProd({{ $i->id }})' wire:confirm="Si borras este articulo tendras que volver a agregarlo, estas seguro?">
                                    <i class="fas fa-trash">
                                    </i>
                                    Eliminar
                                </a>
                                @else
                            <td class="project-actions text-right" style="width: 150px;">
                                <a class="btn btn-danger btn-sm" wire:click='delProd({{ $i->id }})' wire:confirm="Si borras este articulo tendras que volver a agregarlo, estas seguro">
                                    <i class="fas fa-trash">
                                    </i>
                                    Eliminar
                                </a>
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


            <div class="row" style="display: flex; justify-content: end;">
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
                        <div class="input-group input-group pb-2" style="width: 300px;">
                            <input type="text" wire:model='query' wire:keydown='search' class="form-control float-right" placeholder="Buscar">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <table class="table table-bordered  table-hover">
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
