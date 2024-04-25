<div>

    <div class="card">
        @if ($presupuesto->estado == '100')
            <div class="card-header bg-danger">Facturado o Caducado
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
                    <th style="width: 10px">#</th>
                    <th>Producto</th>
                    {{-- <th>Codigo</th> --}}
                    <th>Cantidad</th>
                    <th>Precio Costo</th>
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
                                {{ $i->precio }}
                            </td>
                            <td>
                                {{ $i->subtotal }}
                            </td>
                        @endif



                        @if ($i->estado == 2)
                            {{-- Si el producto es estado 2 aun no se a recibido --}}
                            <td class="project-actions text-right">
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






    <!-- MODAL PARA AGREGAR NUEVO ITEM  -->
    @if ($modalProductos == true)
        <div class="modal fade show" id="modal-lg" style="display: block; padding-right: 17px;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h4 class="m-0"> <strong> AGREGAR ITEM </strong> </h4>
                        <button type="button" class="close" wire:click='modalProdOff'>
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- BUSCADOR DE PRODUCTOS  -->
                        <div class="input-group input-group-sm pb-2" style="width: 300px;">
                            <input type="text" wire:model='query' wire:keydown='search'
                                class="form-control float-right" placeholder="Buscar">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
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
                                @foreach ($stock as $s)
                                    <tr wire:click='addedProduct({{ $s->id }})' wire:loading.attr="disabled">
                                        <td>{{ $s->id }}</td>
                                        <td>{{ $s->productos->descripcion }} - {{ $s->productos->codigo }}</td>
                                        @if ($s->cantidad == 0)
                                            <td><span class="badge bg-danger">{{ $s->cantidad }}</span></td>
                                        @else
                                            <td><span class="badge bg-success">{{ $s->cantidad }}</span></td>
                                        @endif
                                        <td>$ {{ $s->costo }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>


                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" wire:click='modalProdOff'>Cancelar</button>
                        <button type="button" class="btn btn-success">Aceptar</button>
                    </div>
                </div>

            </div>

        </div>
    @endif


</div>
