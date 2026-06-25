<div>
    <div class="row">
        <div class="col-12">
            @php
                $firstClient = $vehiculo->clientes->first();
                $backUrl = $firstClient ? route('clientes.perfil', $firstClient->id) : route('clientes.index');
            @endphp
            <a href="{{ $backUrl }}" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
        </div>
        
        <div class="col-md-4">
            <div class="card card-info card-outline">
                <div class="card-body box-profile">
                    <div class="text-center mb-3">
                        <i class="fas fa-car fa-5x text-info"></i>
                    </div>
                    <h3 class="profile-username text-center">
                        <strong>
                            {{ optional(optional($vehiculo->modelos)->marcas)->descripcion ?? '' }}
                            {{ optional($vehiculo->modelos)->descripcion ?? '' }}
                        </strong>
                    </h3>
                    <p class="text-muted text-center">Año {{ $vehiculo->año ?? '-' }}</p>
                    
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Patente</b> 
                            <span class="float-right badge bg-orange text-white" style="font-size: 0.95rem;">
                                {{ $vehiculo->dominio }}
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Color</b> <a class="float-right text-dark">{{ $vehiculo->color ?? '-' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Versión</b> <a class="float-right text-dark">{{ $vehiculo->version ?? '-' }}</a>
                        </li>
                        @if ($vehiculo->clientes->count())
                            <li class="list-group-item">
                                <b>Propietario</b>
                                <span class="float-right">
                                    @foreach ($vehiculo->clientes as $c)
                                        <a href="{{ route('clientes.perfil', $c->id) }}" class="btn btn-xs btn-link p-0 text-primary">
                                            {{ optional(optional($c->perfiles)->personas)->nombre }} {{ optional(optional($c->perfiles)->personas)->apellido }}
                                        </a>
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
            <!-- HISTORIAL DE TURNOS Y ÓRDENES -->
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

            <!-- HISTORIAL DE PRESUPUESTOS -->
            <div class="card mt-4">
                <div class="card-header bg-warning text-dark">
                    <h4 class="card-title m-0"><strong>Historial de Presupuestos</strong></h4>
                </div>
                <div class="card-body p-0">
                    @if ($vehiclePresupuestos && $vehiclePresupuestos->count())
                        <div class="table-responsive">
                            <table class="table table-hover table-striped m-0">
                                <thead>
                                    <tr>
                                        <th>Nro Presupuesto</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th class="text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vehiclePresupuestos as $p)
                                        <tr>
                                            <td class="align-middle"><strong>#{{ $p->id }}</strong></td>
                                            <td class="align-middle">
                                                {{ $p->created_at ? $p->created_at->format('d/m/Y H:i') : '-' }} hs
                                            </td>
                                            <td class="align-middle">
                                                @switch((string)$p->estado)
                                                    @case('1')
                                                        <span class="badge bg-warning">Generado / Pendiente</span>
                                                        @break
                                                    @case('4')
                                                        <span class="badge bg-success">Cobrado / Facturado</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ $p->estado }}</span>
                                                @endswitch
                                            </td>
                                            <td class="text-right align-middle">
                                                <a href="{{ route('presupuesto.show', $p->id) }}" class="btn btn-sm btn-warning text-dark" title="Ver presupuesto detallado">
                                                    <i class="fas fa-external-link-alt mr-1"></i> Ir a Presupuesto
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-file-invoice-dollar fa-3x mb-3 text-gray-300"></i>
                            <p class="m-0">Este vehículo aún no cuenta con presupuestos registrados.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
