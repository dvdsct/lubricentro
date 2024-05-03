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
                        <input type="text" class="form-control" placeholder="Buscar producto por codigo">
                        <div class="input-group-append">
                            <button class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
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
                    {{-- <th>Codigo</th> --}}
                    <th style="width: 12px">Cantidad</th>
                    <th style="width: 150px">Precio unitario</th>
                    <th style="width: 40px">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($presupuesto->itemspres as $i)
                    <tr>
                        <td>{{ $i->id }}</td>
                        <td>{{ $i->productos->descripcion . ' - ' . $i->productos->codigo }}</td>

                        @if ($i->estado == 1)
                            <td><input type="text" class="form-control" style="width: 145px;"
                                    placeholder="Ingresar cantidad" wire:model='cantidad'
                                    wire:keydown.enter='addCantidad({{ $i->id }})'>
                                @error('cantidad')
                                    {{ $message }}
                                @enderror
                            </td>

                            <td>{{ $i->precio }}

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
                                <a class="btn btn-danger btn-sm" wire:click='delProd({{ $i->id }})'
                                    wire:confirm="Si borras este articulo tendras que volver a agregarlo?">
                                    <i class="fas fa-trash">
                                    </i>
                                    Eliminar
                                </a>
                            </td>
                        @else
                            <td class="project-actions text-right">
                                <a class="btn btn-danger btn-sm" wire:click='delProd({{ $i->id }})'
                                    wire:confirm="Si borras este articulo tendras que volver a agregarlo?">
                                    <i class="fas fa-trash">
                                    </i>
                                    Eliminar
                                </a>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    <!-- AGREGAR VARIABLE DE TOTAL  -->
    <div class="card-header justify-content-end">
        <div class="text-right">
            <h3><strong> TOTAL ${{ $presupuesto->itemspres->sum('subtotal') }}</strong></h3>
        </div>
    </div>




    <!-- MODAL PARA AGREGAR NUEVO ITEM  -->
    @if ($modalProductos)
        <div class="modal fade show" id="modal-lg" style="display: block; padding-right: 17px;" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h4 class="m-0"> <strong> AGREGAR ITEM </strong> </h4>
                        <button  class="close" wire:click='modalProdOff'>
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
                        <!-- BUSCADOR DE PRODUCTOS  -->
                        <div class="input-group input-group-sm pb-2" style="width: 300px;">
                            <input type="text" class="form-control float-right" placeholder="Buscar">
                            <div class="input-group-append">
                                <button class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <table class="table table-bordered  table-hover">
                            <thead>
                                <th style="width: 10px">#</th>
                                <th>Producto</th>
                                <th style="width: 40px">Stock</th>
                                <th>Precio</th>
                            </thead>
                            <tbody>
                                @if ($stock->isEmpty())
                                    <h3>NO HAY</h3>
                                @else
                                    @foreach ($stock as $s)
                                        <tr wire:click='addedProduct({{ $s->id }})'>
                                            <td style="cursor: pointer;">{{ $s->id }}</td>
                                            <td style="cursor: pointer;">{{ $s->productos->descripcion }} -
                                                {{ $s->productos->codigo }}</td>
                                            @if ($s->cantidad == 0)
                                                <td style="cursor: pointer;"><span
                                                        class="badge bg-danger">{{ $s->cantidad }}</span></td>
                                            @else
                                                <td style="cursor: pointer;"><span
                                                        class="badge bg-success">{{ $s->cantidad }}</span></td>
                                            @endif
                                            <td style="cursor: pointer;">$ {{ $s->costo }}</td>

                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    @endif


</div>
