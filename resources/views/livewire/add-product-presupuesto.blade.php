<div>
    <div class="card">
        @if ($presupuesto->estado == '100')
        <div class="card-header bg-danger">Facturado o Caducado
            @else
            <div class="card-header" style="display: flex; justify-content: space-between;">
                <button type="button" class="btn btn-success" wire:click='$dispatch("modal-presupuestos")'>
                    <i class="fas fa-plus-circle"></i> Agregar Item
                </button>

                <div class="input-group" style="width: 300px; margin-left: auto;">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-barcode"></i>
                        </span>
                    </div>
                    <input type="text" id="codigoBarrasInput" class="form-control" placeholder="Buscar producto por codigo" wire:model='codigoBarras' wire:keydown.enter='codeBar'>

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
                            <th>PRODUCTO</th>
                            <th style="width: 12px">CANTIDAD</th>
                            <th style="width: 160px">PRECIO UNITARIO</th>
                            <th style="width: 40px">SUBTOTAL</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $key => $producto)
                        {{-- @livewire('producto-view', ['producto' => $producto, key($producto->id)]) --}}
                        <div>
                            <div id="{{ $producto->id }}">
                                <tr>
                                    <td>{{ $producto->id }}</td>
                                    <td>{{ $producto->productos->descripcion . ' - ' . $producto->productos->codigo }}</td>
                                    @if ($producto->estado == 1)
                                    <td>
                                        <input type="text" class="form-control" style="width: 145px;" placeholder="Ingresar cantidad" wire:model='cantidad' wire:keydown.enter='addCantidad({{ $producto->id }})'>
                                        @error('cantidad')
                                        <strong> <span class="text-danger">{{ $message }}</span> </strong>
                                        @enderror
                                    </td>

                                    <td>
                                        @if ($editPrecio)
                                        <div class="input-group" style="width: 160px;">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control"  placeholder="Ingresar Precio" wire:model='precioPres' wire:keydown.enter='addCantidad({{ $producto->id }})'>
                                        </div>
                                        @error('precioPres')
                                        {{ $message }}
                                        @enderror

                                        @else
                                        $ {{ number_format($producto->precio ?? 0, 2) }}
                                        <button wire:click='editPrecioPres' class="btn btn-info btn-sm"> <i class="fas fa-edit"></i> </button>
                                        @endif

                                    </td>
                                    <td>

                                    </td>
                                    @else
                                    <td>
                                        {{ $producto->cantidad }}
                                    </td>
                                    <td>
                                        $ {{ $producto->productos->precio_presupuesto }}
                                    </td>
                                    <td>
                                        $ {{ $producto->subtotal }}
                                    </td>
                                    @endif



                                    @if ($producto->estado == 2)
                                    {{-- Si el producto es estado 2 aun no se a recibido --}}
                                    <td class="text-right project-actions d-flex" style="width: 200px;">
                                        <div>
                                            
                                            <a class="btn btn-info btn-sm" wire:click='editProd({{ $producto->id }})'>
                                                <i class="fas fa-pencil-alt">
                                                </i>
                                                Editar
                                            </a>
                                        </div>
                                        <div>
                                            
                                            <a class="btn btn-danger btn-sm" id="eliminar" wire:click='delProd({{ $producto->id }})' wire:confirm="Estas seguro de que desesas eliminar este articulo?">
                                                <i class="fas fa-trash">
                                                </i>
                                                Eliminar
                                            </a>
                                        </div>
                                    </td>
                                    @else
                                    <td class="text-right project-actions">
                                        <a class="btn btn-danger btn-sm" wire:click='delProd({{ $producto->id }})' wire:confirm="Estas seguro de que desesas eliminar este articulo?">
                                            <i class="fas fa-trash">
                                            </i>
                                            Eliminar
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <div class="card-header justify-content-end">
                <div class="text-right">
                    <h3><strong> TOTAL
                            ${{ $presupuesto->itemspres->isEmpty() ? '0.00' : $presupuesto->itemspres->sum('subtotal') }}</strong>
                    </h3>

                </div>
            </div>
        </div>
        <div class="col">
            @livewire('facturar-presupuesto', ['presupuesto' => $presupuesto])
        </div>


        <!-- MODAL PARA AGREGAR NUEVO ITEM  -->
        @if ($modalProductos)
        <div class="modal fade show" id="modal-lg" style="display: block; background-color: rgba(0, 0, 0, 0.5);" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h4 class="m-0"> <strong> AGREGAR ITEM </strong> </h4>
                        <button class="close" wire:click='$dispatch("modal-presupuestos")'>
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
                        <!-- BUSCADOR DE PRODUCTOS  -->
                        <div class="pb-2 input-group input-group-sm" style="width: 300px;">
                            <input type="text" class="float-right form-control" wire:model='query' wire:keydown='search' placeholder="Buscar">
                            <div class="input-group-append">
                                <button class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <th style="width: 10px">#</th>
                                <th>PRODUCTO</th>
                                <th style="width: 40px">STOCK</th>
                                <th>PRECIO</th>
                            </thead>
                            <tbody>

                                @foreach ($stock as $key => $s)
                                <tr style="cursor: pointer;" id="{{ $s->id }}" wire:click='addedProduct({{ $s->id }})'>
                                    <td>{{ $s->id }}</td>
                                    <td>{{ $s->productos->descripcion }} -
                                        {{ $s->productos->codigo }}
                                    </td>
                                    @if ($s->cantidad == 0)
                                    <td><span class="badge bg-danger">{{ $s->cantidad }}</span></td>
                                    @else
                                    <td><span class="badge bg-success">{{ $s->cantidad }}</span></td>
                                    @endif
                                    <td>$ {{ $s->precio_venta }}</td>

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