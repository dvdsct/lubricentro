<div>
    @if ($viewState === 'client_profile')
        <!-- PERFIL DE CLIENTE -->
        <div class="row">
            <div class="col-12">
                <button class="btn btn-outline-secondary mb-3" wire:click="goBack">
                    <i class="fas fa-arrow-left mr-1"></i> Volver
                </button>
            </div>
            
            <div class="col-md-4">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center mb-3">
                            <i class="fas fa-user-circle fa-5x text-muted"></i>
                        </div>
                        <h3 class="profile-username text-center">
                            <strong>
                                {{ optional(optional(optional($client)->perfiles)->personas)->nombre }}
                                {{ optional(optional(optional($client)->perfiles)->personas)->apellido }}
                            </strong>
                        </h3>
                        <p class="text-muted text-center">Cliente registrado</p>
                        
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>DNI</b> <a class="float-right text-dark">{{ optional(optional(optional($client)->perfiles)->personas)->DNI ?? '-' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Teléfono</b> <a class="float-right text-dark">{{ optional(optional(optional($client)->perfiles)->personas)->numero_telefono ?? 'No registrado' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Fecha de Nacimiento</b> 
                                <a class="float-right text-dark">
                                    {{ optional(optional(optional($client)->perfiles)->personas)->fecha_nac ? \Carbon\Carbon::parse($client->perfiles->personas->fecha_nac)->format('d/m/Y') : 'No registrada' }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title m-0"><strong>Vehículos Registrados</strong></h4>
                    </div>
                    <div class="card-body p-0">
                        @if ($client && $client->vehiculos->count())
                            <div class="table-responsive">
                                <table class="table table-hover table-striped m-0">
                                    <thead>
                                        <tr>
                                            <th>Vehículo</th>
                                            <th>Año</th>
                                            <th>Patente (Dominio)</th>
                                            <th class="text-right">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($client->vehiculos as $v)
                                            <tr>
                                                <td class="align-middle">
                                                    <strong>{{ optional(optional($v->modelos)->marcas)->descripcion ?? '' }}</strong>
                                                    {{ optional($v->modelos)->descripcion ?? '' }}
                                                </td>
                                                <td class="align-middle">{{ $v->año ?? '-' }}</td>
                                                <td class="align-middle">
                                                    <span class="badge bg-orange text-white" style="font-size: 0.9rem; padding: 5px 10px;">
                                                        {{ $v->dominio }}
                                                    </span>
                                                </td>
                                                <td class="text-right align-middle">
                                                    <button wire:click="showVehicleProfile({{ $v->id }})" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-history mr-1"></i> Historial y Detalles
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-4 text-center text-muted">
                                <i class="fas fa-car-crash fa-3x mb-3 text-gray-300"></i>
                                <p class="m-0">No se encontraron vehículos registrados para este cliente.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    @elseif ($viewState === 'vehicle_profile')
        <!-- PERFIL DE VEHICULO -->
        <div class="row">
            <div class="col-12">
                <button class="btn btn-outline-secondary mb-3" wire:click="goBack">
                    <i class="fas fa-arrow-left mr-1"></i> Volver
                </button>
            </div>
            
            <div class="col-md-4">
                <div class="card card-info card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center mb-3">
                            <i class="fas fa-car fa-5x text-info"></i>
                        </div>
                        <h3 class="profile-username text-center">
                            <strong>
                                {{ optional(optional(optional($vehicle)->modelos)->marcas)->descripcion ?? '' }}
                                {{ optional(optional($vehicle)->modelos)->descripcion ?? '' }}
                            </strong>
                        </h3>
                        <p class="text-muted text-center">Año {{ optional($vehicle)->año ?? '-' }}</p>
                        
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Patente</b> 
                                <span class="float-right badge bg-orange text-white" style="font-size: 0.95rem;">
                                    {{ optional($vehicle)->dominio }}
                                </span>
                            </li>
                            <li class="list-group-item">
                                <b>Color</b> <a class="float-right text-dark">{{ optional($vehicle)->color ?? '-' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Versión</b> <a class="float-right text-dark">{{ optional($vehicle)->version ?? '-' }}</a>
                            </li>
                            @if ($vehicle && $vehicle->clientes->count())
                                <li class="list-group-item">
                                    <b>Propietario</b>
                                    <span class="float-right">
                                        @foreach ($vehicle->clientes as $c)
                                            <button wire:click="showClientProfile({{ $c->id }})" class="btn btn-xs btn-link p-0 text-primary">
                                                {{ optional(optional($c->perfiles)->personas)->nombre }} {{ optional(optional($c->perfiles)->personas)->apellido }}
                                            </button>
                                            @if(!$loop->last), @endif
                                        @endforeach
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h4 class="card-title m-0"><strong>Historial de Turnos y Órdenes</strong></h4>
                    </div>
                    <div class="card-body p-0">
                        @if ($vehicleOrders && $vehicleOrders->count())
                            <div class="table-responsive">
                                <table class="table table-hover table-striped m-0">
                                    <thead>
                                        <tr>
                                            <th>Nro Orden</th>
                                            <th>Fecha y Hora</th>
                                            <th>Servicio / Motivo</th>
                                            <th>Estado</th>
                                            <th class="text-right">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($vehicleOrders as $o)
                                            <tr>
                                                <td class="align-middle"><strong>#{{ $o->id }}</strong></td>
                                                <td class="align-middle">
                                                    {{ $o->fecha_turno ? \Carbon\Carbon::parse($o->fecha_turno)->format('d/m/Y') : '-' }}
                                                    @if($o->horario)
                                                        <span class="text-muted">({{ \Carbon\Carbon::parse($o->horario)->format('H:i') }} hs)</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle">
                                                    <span class="badge {{ $o->motivo === '1' ? 'bg-primary' : ($o->motivo === '2' ? 'bg-orange' : 'bg-secondary') }} text-white">
                                                        {{ $o->motivo === '1' ? 'Lavadero' : ($o->motivo === '2' ? 'Lubricentro' : $o->motivo) }}
                                                    </span>
                                                </td>
                                                <td class="align-middle">
                                                    @switch((string)$o->estado)
                                                        @case('1')
                                                            <span class="badge bg-warning">Pendiente</span>
                                                            @break
                                                        @case('2')
                                                            <span class="badge bg-info text-white">En proceso</span>
                                                            @break
                                                        @case('4')
                                                            <span class="badge bg-primary text-white">Facturado</span>
                                                            @break
                                                        @case('100')
                                                            <span class="badge bg-success">Atendido</span>
                                                            @break
                                                        @case('700')
                                                            <span class="badge bg-danger">Cancelado</span>
                                                            @break
                                                        @case('555')
                                                            <span class="badge bg-dark">Eliminado</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-secondary">{{ $o->estado }}</span>
                                                    @endswitch
                                                </td>
                                                <td class="text-right align-middle">
                                                    <a href="{{ route('ordenes.show', $o->id) }}" class="btn btn-sm btn-info" title="Ver orden detallada">
                                                        <i class="fas fa-external-link-alt mr-1"></i> Ir a Orden
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-4 text-center text-muted">
                                <i class="fas fa-clipboard-list fa-3x mb-3 text-gray-300"></i>
                                <p class="m-0">Este vehículo aún no cuenta con turnos ni órdenes registradas.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    @else
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
                                        <th>Nro Orden</th>
                                        <th>Cliente</th>
                                        <th>Vehículo</th>
                                        <th>Patente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $o)
                                        <tr>
                                            <td class="align-middle">
                                                {{ $o->id }}
                                                <a href="{{ route('ordenes.show', $o->id) }}" class="btn btn-xs btn-primary ml-2" title="Ver orden">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            </td>
                                            <td class="align-middle">
                                                @if ($o->cliente_id)
                                                    <button wire:click="showClientProfile({{ $o->cliente_id }})" class="btn btn-sm btn-link p-0 mr-1 text-primary" title="Ver perfil de cliente">
                                                        <i class="fas fa-user-circle text-md"></i>
                                                    </button>
                                                @endif
                                                {{ optional(optional($o->clientes)->perfiles)->personas->nombre }}
                                                {{ optional(optional($o->clientes)->perfiles)->personas->apellido }}
                                                @php $dni = optional(optional($o->clientes)->perfiles)->personas->DNI ?? null; @endphp
                                                @if($dni)
                                                    <span class="text-muted">(DNI: {{ $dni }})</span>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                @if ($o->vehiculo_id)
                                                    <button wire:click="showVehicleProfile({{ $o->vehiculo_id }})" class="btn btn-sm btn-link p-0 mr-1 text-info" title="Ver perfil de vehículo">
                                                        <i class="fas fa-car text-md"></i>
                                                    </button>
                                                @endif
                                                @php 
                                                    $veh = $o->vehiculos; 
                                                    $modelo = optional($veh)->modelos; 
                                                    $marca = optional($modelo)->marcas;
                                                @endphp
                                                {{ optional($marca)->descripcion ?? '' }}
                                                {{ optional($modelo)->descripcion ?? '' }}
                                                {{ optional($veh)->año ?? '' }}
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge bg-orange text-white">
                                                    {{ optional($o->vehiculos)->dominio ?? '-' }}
                                                </span>
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
    @endif
</div>
