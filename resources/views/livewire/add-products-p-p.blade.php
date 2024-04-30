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
                            <th style="width: 10px">#</th>
                            <th>Producto</th>
                            <th>Codigo</th>
                            <th style="width: 100px">Cantidad</th>
                            <th style="width: 250px">Precio unitario de compra</th>
                            <th style="width: 40px">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pedido->items as $i)
                        <tr>
                            <td>{{ $i->id }}</td>
                            <td>{{ $i->productos->descripcion }}</td>
                            <td>
                                WO-059

                            </td>
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
                    <h3><strong>TOTAL ${{ $total ?? '0' }}</strong></h3>
                </div>
            </div>

        </div>
        <div style="display: flex; justify-content: end;">
            <button class="info-box bg-primary d-flex align-items-center justify-content-center" wire:click='$dispatchTo("form-pago","formPago",{ tipo: "proveedor" })' style="width: 25%;">
                <span class="info-box-icon"> <i class="fas fa-check-circle"></i> </span>
                <div class="info-box-content">
                    <h4 class="info-box-text m-0" style="display: inline;"><strong>Recibir pedido</strong></h4>
                    <span class="info-box-number"></span>
                </div>
            </button>


            <!-- AGREGAR EL WIRECKICK APUNTANDO AL PDF CORRESPONDIENTE -->
            <div class="info-box bg-warning d-flex align-items-center justify-content-end ml-3" style="width: 25%; cursor: pointer;">
                <span class="info-box-icon"><i class="fas fa-print"></i></span>
                <div class="info-box-content">
                    <h4 class="info-box-text m-0"> <strong> Imprimir </strong> </h4>
                    <span class="info-box-number"></span>
                </div>
            </div>
        </div>









        @livewire('form-pago',['orden' => $pedido])


        <!-- MODAL PARA AGREGAR NUEVO ITEM  -->
        @if ($modal == true)
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
                                    <th>Codigo</th>
                                    <th style="width: 40px">Stock</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stock as $i)
                                <tr wire:click='addedProduct({{ $i->id }})' wire:loading.attr="disabled">
                                    <td style="cursor: pointer;">{{ $i->id }}</td>
                                    <td style="cursor: pointer;">{{ $i->descripcion }}</td>
                                    <td style="cursor: pointer;">{{ $i->codigo }}</td>
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