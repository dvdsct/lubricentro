<div>

    <!-- TABLA QUE MUESTRA LISTADO DE PRESUPUESTOS, CREAR Y BUSCAR  -->

    @php
        Carbon\Carbon::setLocale('es');
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between;">
                    <button class="btn btn-success" wire:click='$dispatchTo("add-presupuesto", "addPresupuesto")'>
                        <i class="fas fa-plus-circle"></i> Crear nuevo
                    </button>

                    <form wire:submit.prevent="search" class="input-group" style="width: 480px; margin-left: auto;">
                        <input type="text" name="table_search" class="form-control" wire:model.debounce.400ms="q"
                            placeholder="Buscar por cliente (nombre, apellido o DNI) o patente">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default" title="Buscar">
                                <i class="fas fa-search"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" title="Limpiar"
                                wire:click="clearSearch">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </form>
                </div>
                @if ($presupuestos != null && $presupuestos->count())
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>FECHA</th>
                                    <th>CLIENTE</th>
                                    <th>PATENTE</th>
                                    <th>MONTO</th>
                                    <th>ESTADO</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($presupuestos as $pre)
                                    <tr>
                                        <td>{{ $pre->id }}</td>
                                        <td>{{ Carbon\Carbon::parse($pre->created_at)->diffForHumans() }}</td>
                                        <td>{{ $pre->clientes->perfiles->personas->nombre . ' ' . $pre->clientes->perfiles->personas->apellido }}</td>
                                        <td>{{ optional($pre->vehiculos)->dominio ?? '-' }}</td>
                                        <td> ${{ $pre->itemspres->isEmpty() ? '0.00' : $pre->itemspres->sum('subtotal') }}
                                        </td>
                                        <td>
                                            @if ($pre->estado == 1)
                                                <span class="text">
                                                    <small class="badge badge-danger"><i class="far fa-clock"></i>
                                                        PENDIENTE</small>
                                                </span>
                                            @elseif($pre->estado == 4)
                                                <small class="badge badge-success"><i class="far fa-check"></i> COBRADO
                                                </small>
                                            @endif
                                        </td>
                                        <td style="text-align: center;">
                                            <a href="{{ route('presupuesto.show', $pre->id) }}" class="btn btn-info"> <i
                                                    class="fas fa-eye"></i> Ver detalle</a>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        {{ $presupuestos->links() }}
                    </div>
                @else
                    <div class="card-body">
                        <h3>NO HAY PRESUPUESTOS</h3>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
