<div>
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

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
            <div class="card card-info card-outline shadow-sm">
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

                    <button wire:click="openEditModal" class="btn btn-info btn-block">
                        <i class="fas fa-edit mr-1"></i> Modificar Datos
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <!-- HISTORIAL DE TURNOS Y ÓRDENES -->
            <div class="card shadow-sm">
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
            <div class="card mt-4 shadow-sm">
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

    <!-- MODAL EDITAR VEHÍCULO -->
    @if ($showEditModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0, 0, 0, 0.5);" wire:keydown.escape="closeEditModal">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-car mr-2"></i>Modificar Datos del Vehículo
                        </h5>
                        <button type="button" class="close text-white" wire:click="closeEditModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form wire:submit.prevent="updateVehicle">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="dominio" class="font-weight-bold">Patente / Dominio <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control text-uppercase @error('dominio') is-invalid @enderror" id="dominio" wire:model="dominio" placeholder="Ej: AA123CD">
                                    @error('dominio')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="año" class="font-weight-bold">Año</label>
                                    <input type="number" class="form-control @error('año') is-invalid @enderror" id="año" wire:model="año" placeholder="Ej: 2022">
                                    @error('año')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="marca_vehiculo_id" class="font-weight-bold">Marca</label>
                                    <select class="form-control @error('marca_vehiculo_id') is-invalid @enderror" id="marca_vehiculo_id" wire:model.live="marca_vehiculo_id">
                                        <option value="">-- Seleccionar Marca --</option>
                                        @foreach ($marcas as $m)
                                            <option value="{{ $m->id }}">{{ $m->descripcion }}</option>
                                        @endforeach
                                    </select>
                                    @error('marca_vehiculo_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="modelo_vehiculo_id" class="font-weight-bold">Modelo</label>
                                    <select class="form-control @error('modelo_vehiculo_id') is-invalid @enderror" id="modelo_vehiculo_id" wire:model="modelo_vehiculo_id">
                                        <option value="">-- Seleccionar Modelo --</option>
                                        @foreach ($modelos as $mod)
                                            <option value="{{ $mod->id }}">{{ $mod->descripcion }}</option>
                                        @endforeach
                                    </select>
                                    @error('modelo_vehiculo_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="color" class="font-weight-bold">Color</label>
                                    <input type="text" class="form-control @error('color') is-invalid @enderror" id="color" wire:model="color" placeholder="Ej: Blanco, Gris Plata">
                                    @error('color')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="version" class="font-weight-bold">Versión</label>
                                    <input type="text" class="form-control @error('version') is-invalid @enderror" id="version" wire:model="version" placeholder="Ej: 1.6 MSI Highline">
                                    @error('version')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-secondary" wire:click="closeEditModal">
                                <i class="fas fa-times mr-1"></i> Cancelar
                            </button>
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-save mr-1"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
