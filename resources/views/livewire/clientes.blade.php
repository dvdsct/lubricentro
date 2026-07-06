<div>
    <!-- LISTA PRINCIPAL (BÚSQUEDA DE ÓRDENES) -->
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
                                    <th>Cliente</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $o)
                                    <tr>
                                        <td class="align-middle">
                                            @php
                                                $persona = optional(optional($o->clientes)->perfiles)->personas;
                                                $nombre = $persona->nombre ?? '';
                                                $apellido = $persona->apellido ?? '';
                                            @endphp
                                            {{ trim($nombre . ' ' . $apellido) ?: '-' }}
                                        </td>
                                        <td class="align-middle">
                                            @if ($o->cliente_id)
                                                <a href="{{ route('clientes.perfil', $o->cliente_id) }}" class="btn btn-sm btn-primary">INFO</a>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled>INFO</button>
                                            @endif
                                        </td>
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
