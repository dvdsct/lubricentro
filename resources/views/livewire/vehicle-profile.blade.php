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
                <i class="fas fa-arrow-left mr-1"></i> Volver a Clientes
            </a>
        </div>
        
        <div class="col-md-4">
            <div class="card card-info card-outline shadow-sm">
                <div class="card-body box-profile">
                    <div class="text-center mb-3">
                        <i class="fas fa-car fa-5x text-info"></i>
                    </div>
                    <h3 class="profile-username text-center font-weight-bold">
                        {{ optional(optional($vehiculo->modelos)->marcas)->descripcion ?? '' }}
                        {{ optional($vehiculo->modelos)->descripcion ?? '' }}
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
                                        <a href="{{ route('clientes.perfil', $c->id) }}" class="btn btn-xs btn-link p-0 text-primary font-weight-bold">
                                            {{ optional(optional($c->perfiles)->personas)->nombre }} {{ optional(optional($c->perfiles)->personas)->apellido }}
                                        </a>
                                        @if(!$loop->last), @endif
                                    @endforeach
                                </span>
                            </li>
                        @endif
                    </ul>

                    <!-- BOTÓN PRINCIPAL: NUEVA ORDEN DE TRABAJO (MECÁNICO) -->
                    <button wire:click="openOrdenTrabajoModal" class="btn btn-success btn-block font-weight-bold shadow-sm mb-2">
                        <i class="fas fa-file-signature mr-1"></i> Nueva Orden de Trabajo
                    </button>

                    <button wire:click="openEditModal" class="btn btn-outline-info btn-block btn-sm">
                        <i class="fas fa-edit mr-1"></i> Modificar Datos del Auto
                    </button>

                    <a href="{{ route('pdf.orden.limpia') }}" target="_blank" class="btn btn-outline-secondary btn-block btn-xs mt-2">
                        <i class="fas fa-print mr-1"></i> Imprimir Orden en Blanco
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <!-- HISTORIAL DE ÓRDENES DE TRABAJO (MECÁNICA) -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title m-0">
                        <i class="fas fa-clipboard-check mr-2"></i><strong>Órdenes de Trabajo (Mecánica y Service)</strong>
                    </h4>
                    <button wire:click="openOrdenTrabajoModal" class="btn btn-xs btn-light text-primary font-weight-bold">
                        <i class="fas fa-plus mr-1"></i> Generar Orden
                    </button>
                </div>
                <div class="card-body p-0">
                    @if ($ordenesTrabajo && $ordenesTrabajo->count())
                        <div class="table-responsive">
                            <table class="table table-hover table-striped m-0 align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th># ID</th>
                                        <th>Fecha</th>
                                        <th>Km Entrada / Próx</th>
                                        <th>Aceite</th>
                                        <th>Mecánico</th>
                                        <th class="text-right">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ordenesTrabajo as $ot)
                                        <tr>
                                            <td class="align-middle font-weight-bold">#{{ $ot->id }}</td>
                                            <td class="align-middle">
                                                {{ $ot->fecha ? $ot->fecha->format('d/m/Y') : '-' }}
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge bg-secondary">{{ $ot->km_entrada ? number_format((float)str_replace(['.', ','], '', $ot->km_entrada), 0, '', '.') . ' km' : '-' }}</span>
                                                @if($ot->km_proximo_service)
                                                    <i class="fas fa-arrow-right text-muted mx-1" style="font-size: 0.75rem;"></i>
                                                    <span class="badge bg-danger">{{ number_format((float)str_replace(['.', ','], '', $ot->km_proximo_service), 0, '', '.') . ' km' }}</span>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <small class="font-weight-bold">{{ $ot->tipo_aceite ?: '-' }}</small>
                                                @if($ot->litros_aceite)
                                                    <span class="badge badge-light border">{{ $ot->litros_aceite }} Lts</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-muted small">
                                                {{ $ot->operador_responsable ?: (optional($ot->user)->name ?? '-') }}
                                            </td>
                                            <td class="text-right align-middle">
                                                <a href="{{ route('pdf.orden-trabajo', $ot->id) }}" target="_blank" class="btn btn-sm btn-danger font-weight-bold" title="Abrir PDF">
                                                    <i class="fas fa-file-pdf mr-1"></i> Ver PDF
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-file-alt fa-3x mb-2 text-gray-300"></i>
                            <p class="m-0 mb-2">No hay órdenes de trabajo cargadas para este vehículo.</p>
                            <button wire:click="openOrdenTrabajoModal" class="btn btn-sm btn-success">
                                <i class="fas fa-plus mr-1"></i> Cargar la primera Orden de Trabajo
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- HISTORIAL DE TURNOS Y ÓRDENES -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h4 class="card-title m-0"><strong>Historial de Turnos y Facturación</strong></h4>
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
                                                {{ $o->horario ? ' - ' . $o->horario : '' }}
                                            </td>
                                            <td class="align-middle">
                                                {{ $o->motivo == '1' ? 'Lavadero' : 'Lubricentro' }}
                                            </td>
                                            <td class="align-middle">
                                                @switch((string)$o->estado)
                                                    @case('1')
                                                        <span class="badge bg-warning">Pendiente</span>
                                                        @break
                                                    @case('2')
                                                        <span class="badge bg-primary">Cobrado Parcial</span>
                                                        @break
                                                    @case('3')
                                                        <span class="badge bg-success">Cobrado Total</span>
                                                        @break
                                                    @case('4')
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
            <div class="card shadow-sm">
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

    <!-- MODAL NUEVA ORDEN DE TRABAJO (MECÁNICO) -->
    @if ($showOrdenTrabajoModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0, 0, 0, 0.6); z-index: 1050;" wire:keydown.escape="closeOrdenTrabajoModal">
            <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header bg-primary text-white py-2">
                        <h5 class="modal-title font-weight-bold">
                            <i class="fas fa-file-signature mr-2"></i>Nueva Orden de Trabajo
                        </h5>
                        <button type="button" class="close text-white" wire:click="closeOrdenTrabajoModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-3" style="max-height: 80vh; overflow-y: auto;">
                        <!-- BANNER VEHÍCULO Y CLIENTE -->
                        <div class="card bg-light border-0 mb-3 shadow-none">
                            <div class="card-body p-2">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <small class="text-muted font-weight-bold d-block">VEHÍCULO</small>
                                        <strong class="text-dark">
                                            {{ optional(optional($vehiculo->modelos)->marcas)->descripcion ?? '' }}
                                            {{ optional($vehiculo->modelos)->descripcion ?? '' }}
                                            {{ $vehiculo->version ? '(' . $vehiculo->version . ')' : '' }}
                                        </strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted font-weight-bold d-block">PATENTE (DOMINIO)</small>
                                        <span class="badge bg-orange text-white font-weight-bold" style="font-size: 1rem;">
                                            {{ $vehiculo->dominio }}
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted font-weight-bold d-block">CLIENTE</small>
                                        <span class="font-weight-bold text-dark">{{ $ot_cliente_nombre }}</span>
                                    </div>
                                    <div class="col-md-2">
                                        <small class="text-muted font-weight-bold d-block">FECHA</small>
                                        <input type="date" class="form-control form-control-sm font-weight-bold" wire:model="ot_fecha">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 1. KILOMETRAJE (ENTRADA Y PRÓXIMO SERVICE) -->
                        <div class="card border-info mb-3">
                            <div class="card-header bg-info py-1 px-3 text-white">
                                <strong class="small"><i class="fas fa-tachometer-alt mr-1"></i> Kilometraje del Vehículo</strong>
                            </div>
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-md-6 form-group mb-0">
                                        <label class="font-weight-bold mb-1">
                                            Km de Entrada <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" 
                                                   class="form-control font-weight-bold text-primary @error('ot_km_entrada') is-invalid @enderror" 
                                                   placeholder="Ej: 145000" 
                                                   wire:model.live.debounce.400ms="ot_km_entrada"
                                                   style="font-size: 1.1rem;">
                                            <div class="input-group-append">
                                                <span class="input-group-text font-weight-bold">km</span>
                                            </div>
                                        </div>
                                        <small class="text-muted">Calcula automáticamente +10.000 km para el próximo service.</small>
                                    </div>

                                    <div class="col-md-6 form-group mb-0">
                                        <label class="font-weight-bold mb-1">
                                            Km Próximo Service
                                        </label>
                                        <div class="input-group">
                                            <input type="number" 
                                                   class="form-control font-weight-bold text-danger" 
                                                   placeholder="Ej: 155000" 
                                                   wire:model="ot_km_proximo_service"
                                                   style="font-size: 1.1rem;">
                                            <div class="input-group-append">
                                                <span class="input-group-text font-weight-bold">km</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. TRABAJO A REALIZAR -->
                        <div class="form-group mb-3">
                            <label class="font-weight-bold mb-1">Trabajo a Realizar</label>
                            <input type="text" class="form-control" wire:model="ot_trabajo_realizar" placeholder="Ej: Service general / Cambio de aceite y filtros">
                        </div>

                        <!-- 3. ACEITE DE MOTOR -->
                        <div class="card border-secondary mb-3">
                            <div class="card-header bg-secondary py-1 px-3 text-white">
                                <strong class="small"><i class="fas fa-oil-can mr-1"></i> Aceite de Motor</strong>
                            </div>
                            <div class="card-body p-3">
                                <div class="row align-items-center">
                                    <div class="col-md-5 form-group mb-2">
                                        <label class="font-weight-bold mb-1">Tipo de Aceite</label>
                                        <input type="text" class="form-control" wire:model="ot_tipo_aceite" placeholder="Ej: 10W-40 Semi-sintético, 5W-30 Sintético...">
                                    </div>
                                    <div class="col-md-3 form-group mb-2">
                                        <label class="font-weight-bold mb-1">Litros (Lts)</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control font-weight-bold" wire:model="ot_litros_aceite" placeholder="Ej: 4.5">
                                            <div class="input-group-append">
                                                <span class="input-group-text">Lts</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group mb-2 pt-3">
                                        <div class="d-flex justify-content-between">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="aceite_envasado" wire:model="ot_aceite_envasado">
                                                <label class="custom-control-label font-weight-bold" for="aceite_envasado">Envasado</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="aceite_suelto" wire:model="ot_aceite_suelto">
                                                <label class="custom-control-label font-weight-bold" for="aceite_suelto">Suelto</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="aceite_lavado" wire:model="ot_aceite_lavado">
                                                <label class="custom-control-label font-weight-bold" for="aceite_lavado">Lavado</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. FILTROS -->
                        <div class="card border-secondary mb-3">
                            <div class="card-header bg-secondary py-1 px-3 text-white">
                                <strong class="small"><i class="fas fa-filter mr-1"></i> Filtros</strong>
                            </div>
                            <div class="card-body p-3">
                                <div class="row">
                                    <!-- Filtro de Aceite -->
                                    <div class="col-md-6 form-group mb-3">
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input type="checkbox" class="custom-control-input" id="f_aceite" wire:model="ot_filtro_aceite">
                                            <label class="custom-control-label font-weight-bold" for="f_aceite">Filtro de Aceite</label>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" placeholder="Código / Marca / Detalle del filtro..." wire:model="ot_filtro_aceite_detalle">
                                    </div>

                                    <!-- Filtro Combustible -->
                                    <div class="col-md-6 form-group mb-3">
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input type="checkbox" class="custom-control-input" id="f_comb" wire:model="ot_filtro_combustible">
                                            <label class="custom-control-label font-weight-bold" for="f_comb">Filtro de Combustible</label>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" placeholder="Código / Marca / Detalle..." wire:model="ot_filtro_combustible_detalle">
                                    </div>

                                    <!-- Filtro de Aire -->
                                    <div class="col-md-6 form-group mb-2">
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input type="checkbox" class="custom-control-input" id="f_aire" wire:model="ot_filtro_aire">
                                            <label class="custom-control-label font-weight-bold" for="f_aire">Filtro de Aire</label>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" placeholder="Código / Marca / Detalle..." wire:model="ot_filtro_aire_detalle">
                                    </div>

                                    <!-- Filtro Habitáculo -->
                                    <div class="col-md-6 form-group mb-2">
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input type="checkbox" class="custom-control-input" id="f_hab" wire:model="ot_filtro_habitaculo">
                                            <label class="custom-control-label font-weight-bold" for="f_hab">Filtro de Habitáculo</label>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" placeholder="Código / Marca / Detalle..." wire:model="ot_filtro_habitaculo_detalle">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 5. CONTROL / INSPECCIÓN GENERAL -->
                        <div class="card border-secondary mb-3">
                            <div class="card-header bg-secondary py-1 px-3 text-white">
                                <strong class="small"><i class="fas fa-check-double mr-1"></i> Control e Inspección del Vehículo</strong>
                            </div>
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-6 col-md-3 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="c_escaneo" wire:model="ot_controles.escaneo">
                                            <label class="custom-control-label" for="c_escaneo">Escaneo</label>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="c_reseteo" wire:model="ot_controles.reseteo">
                                            <label class="custom-control-label" for="c_reseteo">Reseteo</label>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="c_frenos" wire:model="ot_controles.frenos">
                                            <label class="custom-control-label" for="c_frenos">Frenos</label>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="c_tdel" wire:model="ot_controles.t_delantero">
                                            <label class="custom-control-label" for="c_tdel">Tren Delantero</label>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="c_escobillas" wire:model="ot_controles.escobillas">
                                            <label class="custom-control-label" for="c_escobillas">Escobillas</label>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="c_luces" wire:model="ot_controles.luces">
                                            <label class="custom-control-label" for="c_luces">Luces</label>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="c_liq_frenos" wire:model="ot_controles.liq_frenos">
                                            <label class="custom-control-label" for="c_liq_frenos">Liq. Frenos</label>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="c_liq_refrig" wire:model="ot_controles.liq_refrig">
                                            <label class="custom-control-label" for="c_liq_refrig">Liq. Refrigerante</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 6. OBSERVACIONES Y CIERRE -->
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class="font-weight-bold mb-1">Observaciones</label>
                                <textarea class="form-control" rows="2" placeholder="Detalles mecánicos, recomendaciones..." wire:model="ot_observaciones"></textarea>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label class="font-weight-bold mb-1">Recibir Producto para cambio</label>
                                <input type="text" class="form-control mb-2" placeholder="Repuestos traídos por el cliente..." wire:model="ot_recibir_producto">

                                <div class="d-flex justify-content-between align-items-center">
                                    <div style="flex-grow: 1;" class="mr-3">
                                        <label class="font-weight-bold mb-0 small">Operador Responsable</label>
                                        <input type="text" class="form-control form-control-sm" wire:model="ot_operador_responsable">
                                    </div>
                                    <div class="custom-control custom-checkbox mt-3">
                                        <input type="checkbox" class="custom-control-input" id="control_final" wire:model="ot_control_final">
                                        <label class="custom-control-label font-weight-bold text-success" for="control_final">Control final OK</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light justify-content-between py-2">
                        <button type="button" class="btn btn-secondary font-weight-bold" wire:click="closeOrdenTrabajoModal">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </button>
                        <button type="button" class="btn btn-success font-weight-bold shadow-sm" wire:click="saveOrdenTrabajo">
                            <i class="fas fa-file-pdf mr-1"></i> Guardar y Generar PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @script
    <script>
        $wire.on('abrir-pdf', (event) => {
            const data = Array.isArray(event) ? event[0] : event;
            if (data && data.url) {
                window.open(data.url, '_blank');
            }
        });
    </script>
    @endscript
</div>
