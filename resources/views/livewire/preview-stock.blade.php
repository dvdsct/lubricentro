<div class="pt-3">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"> <strong> STOCK </strong> </h3>
            <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 300px;">
                    <input type="text" wire:model='query' wire:keydown='search' class="form-control float-right" placeholder="Buscar producto">
                    <div class="input-group-append">
                        <button  class="btn btn-default">
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
                        <th>Producto</th>
                        <th>Unidad</th>
                        <th>Cantidad</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stock as $p)
                        @if ($p->cantidad < $p->ideal)
                            <tr class="bg-warning">
                        @endif

                        <td>{{ $p->productos->descripcion }}</td>
                        <td>{{ $p->unidad }}</td>
                        <td>{{ $p->cantidad }}</td>
                        <td><a class="btn" href="{{ route('pedidos.index') }}">
                                PEDIR</a></td>


                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

    </div>
</div>
