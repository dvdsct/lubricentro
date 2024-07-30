<div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <button type="button" class="btn btn-success"
                        wire:click='dispatchTo("form-add-prod","modal-prod-on")'>
                        <i class="fas fa-plus-circle"></i> Agregar Producto
                    </button>
                </div>
                <div class="input-group" style="width: 300px;">
                    <input type="text" wire:model='query' wire:keydown='search' class="form-control"
                        placeholder="Buscar producto">
                    <div class="input-group-append">
                        <button class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-striped">
            <thead>
                <th>ID</th>
                <th>PRODUCTO</th>
                <th>CATEGORIA</th>
                <th>SUBCATEGORIA</th>
                <th>COSTO</th>
                <th>PRECIO DE VENTA</th>
                <th>PROVEEDOR</th>
                <th></th>
            </thead>
            <tbody>

                @foreach ($productos as $p)

                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->codigo }} {{ $p->descripcion }} </td>
                    <td>{{ $p->categoria_nombre }}</td> <!-- COLOCAR LA CATEGORIA DEL PRODUCTO -->
                    <td>{{ $p->subcategoria_nombre }}</td> <!-- COLOCAR LA CATEGORIA DEL PRODUCTO -->

                    <td> ${{ number_format($p->costo ?? 0, 2) }}</td>
                    <td> $ {{$p->precio_venta }}</td>
                    <td>{{ $p->nombre }}</td>
                    <td class="text-right project-actions">

                        <button class="btn btn-info btn-sm"
                            wire:click='dispatchTo("form-add-prod","modal-prod-edit",{id:{{$p->id}}})'>
                            <i class="fas fa-pencil-alt">
                            </i>

                        </button>
                        @can('stock')

                        <a class="btn btn-danger btn-sm" wire:click='delProd({{$p->id}})'
                            wire:confirm="¿Esta seguro de que desea eliminar este registro?">
                            <i class="fas fa-trash"></i>
                        </a>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>


    </div>


</div>