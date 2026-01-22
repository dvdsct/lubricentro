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
                        <input type="text" class="form-control" wire:model.debounce.400ms="q"
                               placeholder="{{ $filterBy === 'patente' ? 'Buscar por patente' : ($filterBy === 'apellido' ? 'Buscar por apellido' : ($filterBy === 'dni' ? 'Buscar por DNI' : 'Buscar por patente, apellido o DNI')) }}">
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
                                        <td>
                                            {{ $o->id }}
                                            <a href="{{ route('ordenes.show', $o->id) }}" class="btn btn-sm btn-primary ml-2" title="Ver orden">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        </td>
                                        <td>
                                            {{ optional(optional($o->clientes)->perfiles)->personas->nombre }}
                                            {{ optional(optional($o->clientes)->perfiles)->personas->apellido }}
                                            @php $dni = optional(optional($o->clientes)->perfiles)->personas->DNI ?? null; @endphp
                                            @if($dni)
                                                <span class="text-muted">(DNI: {{ $dni }})</span>
                                            @endif
                                        </td>
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
