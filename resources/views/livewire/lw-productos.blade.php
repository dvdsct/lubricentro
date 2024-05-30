<div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <button type="button" class="btn btn-success" wire:click='dispatchTo("form-add-prod","modal-prod-on")'>
                        <i class="fas fa-plus-circle"></i> Agregar Producto
                    </button>
                </div>
                <div class="input-group" style="width: 300px;">
                    <input type="text" wire:model='query' wire:keydown='search' class="form-control" placeholder="Buscar producto">
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
                <th>CODIGO DE BARRAS</th>
                <th>PRECIO DE VENTA</th>
                <th>COSTO</th>
                <th>PROVEEDOR</th>
                <th></th>
            </thead>
            <tbody>

                @foreach ($productos as $p)

                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->codigo }} {{ $p->descripcion }} </td>
                    <td>{{ $p->categoria_productos }}</td>     <!-- COLOCAR LA CATEGORIA DEL PRODUCTO -->
                    <td>{{ $p->codigo_de_barras}}</td>
                    <td> $ {{ number_format($p->precio_venta ?? 0, 2) }}</td>
                    <td> ${{ number_format($p->costo  ?? 0, 2) }}</td>
                    <td>{{ $p->proveedores->perfiles->personas->nombre }}</td>
                    <td class="text-right project-actions">

                        <button class="btn btn-info btn-sm" >
                            <i class="fas fa-pencil-alt">
                            </i>

                        </button>
                        <a class="btn btn-danger btn-sm"  wire:confirm="¿Esta seguro de que desea eliminar este registro?">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div>
       {{$productos->links()}}
    </div>
</div>