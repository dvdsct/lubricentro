<div>
    <button class="btn btn-success" wire:click='$dispatchTo("add-presupuesto", "addPresupuesto")'>
        Nuevo Presupuesto
    </button>
    @php
        Carbon\Carbon::setLocale('es');

    @endphp
    <div class="row">
        @if ($presupuestos != null)

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"></h3>
                        <div class="card-tools">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control float-right"
                                    placeholder="Search">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Precio</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($presupuestos as $pre)
                                    @if ($pre->estado == '1')
                                        <tr>
                                            <td>{{ $pre->id }}</td>
                                            <td>{{ Carbon\Carbon::parse($pre->created_at)->diffForHumans() }}</td>
                                            <td>{{ $pre->clientes->perfiles->personas->nombre . ' ' . $pre->clientes->perfiles->personas->apellido }}
                                            </td>

                                            <td>{{ $pre->total }}</td>
                                            <td><a href="{{ route('presupuesto.show', $pre->id) }}"
                                                    class="btn btn-info">Ver</a></td>
                                        </tr>
                                    @endif
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        @else
            <h3>NO HAY PRESUPUESTOS</h3>

        @endif
    </div>
</div>
