<!-- TABLA DE ITEMS AGREGADOS EN LA SECCION DE NUEVA ORDEN -->

<div>
    <div class="card">
        @if ($orden->estado == 100)
        <div class="card-header bg-danger">PAGADO
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
                            <th style="width: 60px">Precio Unitario</th>
                            <th style="width: 40px">Cantidad</th>
                            <th style="width: 40px">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orden->items as $i)
                        <tr>
                            <td>{{ $i->id }}</td>
                            <td>{{ $i->productos->descripcion }} - {{ $i->productos->codigo }}</td>

                            <td>
                                $ {{ $i->productos->costo }}
                            </td>
                            @if ($i->estado == 1)
                            <td><input type="text" class="form-control" wire:model='cantidad' wire:keydown.enter='addCantidad({{ $i->id }})'></td>
                            @else
                            <td>
                                {{ $i->cantidad }}
                            </td>
                            @endif
                            <td>
                              $  {{ $i->subtotal }}
                            </td>
                            @if ($orden->estado == 100)
                            <td class="project-actions text-right">
                                @else
                            <td class="project-actions text-right pl-0">
                                {{-- <a class="btn btn-info btn-sm" wire:click='editProd({{ $i->id }})'>
                                <i class="fas fa-pencil-alt">
                                </i>
                              
                                </a> --}}
                                <a class="btn btn-danger btn-sm" wire:click='delProd({{ $i->id }})' wire:confirm="Si borras este articulo tendras que volver a agregarlo">
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
                    @if($total != null)
                    <h3> <strong> TOTAL ${{ $total }} </strong> </h3>
                    @else
                    <h3> <strong> TOTAL $0 </strong> </h3>
                    @endif
                    <!-- <h3> <strong>  </strong> </h3> -->
                </div>
            </div>

        </div>


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
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" wire:click='modalProdOff'>Cancelar</button>
                        <button type="button" class="btn btn-primary">Aceptar</button>
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