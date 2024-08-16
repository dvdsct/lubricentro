<div class="pt-3">
    <h3> <strong> STOCK </strong> </h3>
    <div class="card">
        <div class="card-header">
            <div class="card-tools">
                <div class="input-group" style="width: 300px;">
                    <input type="text" wire:model='query' wire:keydown='search' class="float-right form-control" placeholder="Buscar producto">
                    <div class="input-group-append">
                        <button class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-0 card-body">
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
    <a href="{{ route('pdf.stock') }}" target="_blank">
        <div class="small-box bg-warning" style="cursor: pointer;">
            <div class="inner">
                <h3 class="m-0">Imprimir</h3>
                <p>Planilla Sotck</p>
            </div>
            <div class="icon">
                <i class="fas fa-print"></i>
            </div>
        </div>
    </a>
</div>