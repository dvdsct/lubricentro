<div class="pt-3">
    <h3> <strong> STOCK </strong> </h3>
    <div class="card">
        <div class="card-header">
            <div class="card-tools">
                <div class="input-group" style="width: 300px;">
                    <input type="text" wire:model='query' wire:keydown='search' class="form-control float-right"
                        placeholder="Buscar producto">
                    <div class="input-group-append">
                        <button class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Unidad</th>
                        <th>Cantidad</th>
                        @can('stock')
                            <th></th>
                        @endcan

                    </tr>
                </thead>
                <tbody>
                    @foreach ($stock as $p)
                        @if ($p->cantidad < $p->ideal)
                            <tr class="bg-warning">
                            @else
                            <tr class="">
                        @endif

                        <td>{{ $p->id }}</td>
                        <td>{{ $p->productos->descripcion }} - {{ $p->productos->codigo }}</td>
                        <td>{{ $p->unidad }}</td>

                        <td>
                            @if ($p->estado == '1')
                                {{ $p->cantidad }}
                            @else
                                <input type="text" class="form-control" style="width: 145px;"
                                    placeholder="Ingresar cantidad" wire:model='cantidad'
                                    wire:keydown.enter='addCantidad({{ $p->id }})'>
                                @error('cantidad')
                                    {{ $message }}
                                @enderror
                            @endif
                        </td>
                        @can('stock')
                            <td><a class="btn btn-info" wire:click='editPStock({{ $p->id }})'>
                                    Editar</a></td>
                        @endcan



                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

    </div>

</div>
