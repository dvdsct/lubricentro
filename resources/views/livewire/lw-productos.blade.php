<div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <button type="button" class="btn btn-success" wire:click='modalOn'>
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
                <th>codigo</th>
                <th>Descripcion</th>
                <th>Precio de venta</th>
                <th>Costo</th>
                <th>Proveedor</th>
            </thead>
            <tbody>
                @foreach ($productos as $p)
                <tr>
                    <td>{{ $p->codigo }}</td>
                    <td>{{ $p->descripcion }}</td>
                    <td>
                        @if($p->costo != null)
                        ${{ $p->costo }}

                        @endif
                    </td>
                    <td></td>
                    <td>{{ $p->proveedores->perfiles->personas->nombre }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>