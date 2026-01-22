<div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
                    <h4 class="m-0"><strong>Clientes</strong></h4>
                    <form wire:submit.prevent="search" class="input-group" style="width: 680px; margin-left: auto;">
                        <div class="input-group-prepend">
                            <select class="form-control" wire:model="filterBy" title="Filtro">
                                <option value="all">Todos</option>
                                <option value="patente">Patente</option>
                                <option value="apellido">Apellido</option>
                                <option value="dni">DNI</option>
                            </select>
                        </div>
                        <input type="text" class="form-control" wire:model.debounce.400ms="q" placeholder="Buscar...">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary" title="Buscar">
                                <i class="fas fa-search"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" wire:click="clear" title="Limpiar">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </form>
                </div>

                @if ($orders != null && $orders->count())
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Nro Orden</th>
                                    <th>Cliente</th>
                                    <th>Vehículo</th>
                                    <th>Patente</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $o)
                                    <tr>
                                        <td>{{ $o->id }}</td>
                                        <td>{{ optional(optional($o->clientes)->perfiles)->personas->nombre }} {{ optional(optional($o->clientes)->perfiles)->personas->apellido }}</td>
                                        <td>
                                            @php 
                                                $veh = $o->vehiculos; 
                                                $modelo = optional($veh)->modelos; 
                                                $marca = optional($modelo)->marcas;
                                            @endphp
                                            {{ optional($marca)->descripcion ?? '' }}
                                            {{ optional($modelo)->descripcion ?? '' }}
                                            {{ optional($veh)->año ?? '' }}
                                        </td>
                                        <td>{{ optional($o->vehiculos)->dominio ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="card-body">
                        <h3>No hay resultados</h3>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
