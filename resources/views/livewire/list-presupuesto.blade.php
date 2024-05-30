<div>

    <!-- TABLA QUE MUESTRA LISTADO DE PRESUPUESTOS, CREAR Y BUSCAR  -->

    @php
    Carbon\Carbon::setLocale('es');
    @endphp
    <div class="row">
        @if ($presupuestos != null)
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between;" >
                    <button class="btn btn-success" wire:click='$dispatchTo("add-presupuesto", "addPresupuesto")'>
                        <i class="fas fa-plus-circle"></i> Crear nuevo
                    </button>


                        <div class="input-group" style="width: 300px; margin-left: auto;">
                            <input type="text" name="table_search" class="form-control" placeholder="Buscar presupuesto">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>FECHA</th>
                                <th>CLIENTE</th>
                                <th>MONTO</th>
                                <th>ESTADO</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($presupuestos as $pre)
                            @if ($pre->estado == '1')
                            <tr>
                                <td>{{ $pre->id }}</td>
                                <td>{{ Carbon\Carbon::parse($pre->created_at)->diffForHumans() }}</td>
                                <td>{{ $pre->clientes->perfiles->personas->nombre . ' ' . $pre->clientes->perfiles->personas->apellido }}</td>
                                <td> ${{ $pre->itemspres->isEmpty() ? '0.00' : $pre->itemspres->sum('subtotal') }}</td>
                                <td>
                                @if($pre->estado==1)
                                <span class="text">
                                        <small class="badge badge-danger"><i class="far fa-clock"></i> PENDIENTE</small>
                                    </span>
                                @elseif($pre->estado==2)
                                <small class="badge badge-success"><i class="far fa-check"></i> COBRADO </small>
                            @endif
                            </td>
                                <td style="text-align: center;">
                                    <a href="{{ route('presupuesto.show', $pre->id) }}" class="btn btn-info"> <i class="fas fa-eye"></i> Ver detalle</a>
                                </td>
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