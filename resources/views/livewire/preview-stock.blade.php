<div class="pt-3">
    <h3> <strong> STOCK </strong> </h3>
    <div class="card">
        <div class="card-header">
            <div class="card-tools">
                <div class="input-group" style="width: 300px;">
                    <input type="text" wire:model='query' wire:keydown='search' class="form-control float-right" placeholder="Buscar producto">
                    <div class="input-group-append">
                        <button class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table ">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Unidad</th>
                        <th>Cantidad</th>


                    </tr>
                </thead>
                <tbody>
                    @foreach ($stock as $p)
                    @if ($p->cantidad == 0)
                    <tr style="background-color: #f8d7da;">
                        @elseif ($p->cantidad < $p->ideal)
                        <tr style="background-color: #fff3cd;">
                        @else
                    <tr class="">
                        @endif


                        <td>{{ $p->id }}</td>
                        <td>{{ $p->productos->descripcion }} - {{ $p->productos->codigo }}</td>
                        <td>{{ $p->unidad }}</td>

                        <td>
                          
                            {{ $p->cantidad }}

                        </td>

                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

    </div>

</div>