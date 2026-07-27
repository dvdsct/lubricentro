<div>
    @if (!$cliente)
        <div class="alert alert-warning text-center shadow-sm p-4 rounded-lg">
            <i class="fas fa-exclamation-triangle fa-2x mb-2 text-warning"></i>
            <h5><strong>Su cuenta aún no se encuentra vinculada a un perfil de cliente.</strong></h5>
            <p class="mb-0">Por favor, comuníquese con el personal del taller para que vinculen su correo con sus datos de cliente.</p>
        </div>
    @else
        <!-- CABECERA DEL CLIENTE -->
        <div class="card card-dark card-outline shadow-sm mb-3 mb-md-4">
            <div class="card-body p-3 p-md-4">
                <div class="row align-items-center text-center text-md-left">
                    <div class="col-12 col-md-auto mb-2 mb-md-0">
                        <i class="fas fa-user-circle fa-4x fa-md-5x text-secondary"></i>
                    </div>
                    <div class="col-12 col-md">
                        <h4 class="mb-1 font-weight-bold text-dark">
                            {{ optional(optional($cliente->perfiles)->personas)->nombre }}
                            {{ optional(optional($cliente->perfiles)->personas)->apellido }}
                        </h4>
                        <div class="d-flex flex-wrap justify-content-center justify-content-md-start text-muted small">
                            <span class="mr-3 mb-1">
                                <i class="fas fa-id-card text-warning mr-1"></i> DNI: <strong>{{ optional(optional($cliente->perfiles)->personas)->DNI ?? 'No registrado' }}</strong>
                            </span>
                            <span class="mb-1">
                                <i class="fas fa-phone text-warning mr-1"></i> Teléfono: <strong>{{ optional(optional($cliente->perfiles)->personas)->numero_telefono ?? 'No registrado' }}</strong>
                            </span>
                        </div>
                    </div>
                    <div class="col-12 col-md-auto mt-2 mt-md-0">
                        <span class="badge bg-warning text-dark p-2 w-100 w-md-auto text-uppercase" style="font-size: 0.85rem;">
                            <i class="fas fa-car mr-1"></i> {{ $cliente->vehiculos->count() }} Vehículo(s)
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTOR DE PESTAÑAS RESPONSIVE -->
        <div class="nav nav-pills nav-pills-custom mb-3" id="portalTabs" role="tablist">
            <a class="nav-link {{ $activeTab === 'turnos' ? 'active' : '' }}" 
               href="#" wire:click.prevent="setTab('turnos')">
                <i class="fas fa-calendar-check mr-1"></i> Mis Turnos y Órdenes ({{ $cliente->ordenes->count() }})
            </a>
            <a class="nav-link {{ $activeTab === 'presupuestos' ? 'active' : '' }}" 
               href="#" wire:click.prevent="setTab('presupuestos')">
                <i class="fas fa-file-invoice-dollar mr-1"></i> Mis Presupuestos ({{ $cliente->presupuestos->count() }})
            </a>
            <a class="nav-link {{ $activeTab === 'vehiculos' ? 'active' : '' }}" 
               href="#" wire:click.prevent="setTab('vehiculos')">
                <i class="fas fa-car mr-1"></i> Mis Vehículos ({{ $cliente->vehiculos->count() }})
            </a>
        </div>

        <!-- CONTENIDO DE LAS PESTAÑAS -->
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-body p-2 p-md-3">
                <!-- CONTENIDO PESTAÑA: MIS TURNOS Y ÓRDENES -->
                @if ($activeTab === 'turnos')
                    @if ($cliente->ordenes->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-calendar-times fa-3x mb-3 text-secondary"></i>
                            <p class="m-0">Aún no posee turnos ni órdenes registradas a su nombre.</p>
                        </div>
                    @else
                        <!-- VISTA ESCRITORIO / TABLET (TABLA) -->
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-hover table-striped align-middle">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>N° Orden</th>
                                        <th>Fecha y Hora</th>
                                        <th>Vehículo</th>
                                        <th>Servicio</th>
                                        <th>Estado</th>
                                        <th>Total de la Orden</th>
                                        <th class="text-center">Ítems Utilizados</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cliente->ordenes as $o)
                                        <tr>
                                            <td class="font-weight-bold align-middle">#{{ $o->id }}</td>
                                            <td class="align-middle">
                                                {{ $o->fecha_turno ? \Carbon\Carbon::parse($o->fecha_turno)->format('d/m/Y') : '-' }}
                                                @if ($o->horario)
                                                    <span class="text-muted small">({{ \Carbon\Carbon::parse($o->horario)->format('H:i') }} hs)</span>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                @if ($o->vehiculos)
                                                    <strong>{{ optional(optional($o->vehiculos->modelos)->marcas)->descripcion }}</strong> 
                                                    {{ optional($o->vehiculos->modelos)->descripcion }}
                                                    <span class="badge bg-warning text-dark font-weight-bold ml-1">{{ $o->vehiculos->dominio }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge {{ $o->motivo === '1' ? 'bg-primary' : 'bg-orange text-white' }}">
                                                    {{ $o->motivo === '1' ? 'Lavadero' : 'Lubricentro' }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                @switch((string)$o->estado)
                                                    @case('100')
                                                        <span class="badge bg-success"><i class="fas fa-check-circle mr-1"></i> Atendido</span>
                                                        @break
                                                    @case('700')
                                                        <span class="badge bg-danger"><i class="fas fa-times-circle mr-1"></i> Cancelado</span>
                                                        @break
                                                    @case('10')
                                                        <span class="badge bg-info"><i class="fas fa-clock mr-1"></i> En Proceso</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-warning text-dark"><i class="fas fa-calendar mr-1"></i> Pendiente</span>
                                                @endswitch
                                            </td>
                                            <td class="font-weight-bold text-success align-middle">
                                                ${{ number_format($o->items->sum('subtotal'), 2, ',', '.') }}
                                            </td>
                                            <td class="align-middle text-center">
                                                <button class="btn btn-sm btn-outline-info" type="button" data-toggle="collapse" data-target="#itemsOrderDesktop{{ $o->id }}" aria-expanded="false">
                                                    <i class="fas fa-list mr-1"></i> Ver {{ $o->items->count() }} ítem(s)
                                                </button>
                                            </td>
                                        </tr>
                                        <!-- DESGLOSE DE ÍTEMS EN COLLAPSE DESKTOP -->
                                        <tr class="collapse" id="itemsOrderDesktop{{ $o->id }}">
                                            <td colspan="7" class="bg-light p-3">
                                                <div class="card card-body m-0 shadow-sm border">
                                                    <h6 class="font-weight-bold text-dark border-bottom pb-2">
                                                        <i class="fas fa-tools mr-1"></i> Productos y Servicios utilizados en la Orden #{{ $o->id }}
                                                    </h6>
                                                    @if ($o->items->isEmpty())
                                                        <p class="text-muted small m-0">No hay productos ni servicios detallados en esta orden.</p>
                                                    @else
                                                        <table class="table table-sm table-bordered m-0">
                                                            <thead>
                                                                <tr class="bg-secondary text-white">
                                                                    <th>Producto / Servicio</th>
                                                                    <th class="text-center">Cantidad</th>
                                                                    <th class="text-right">Precio Unitario</th>
                                                                    <th class="text-right">Subtotal</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($o->items as $i)
                                                                    <tr>
                                                                        <td>{{ optional($i->productos)->descripcion ?? 'Servicio / Producto' }}</td>
                                                                        <td class="text-center">{{ $i->cantidad ?? 1 }}</td>
                                                                        <td class="text-right">${{ number_format($i->precio, 2, ',', '.') }}</td>
                                                                        <td class="text-right font-weight-bold">${{ number_format($i->subtotal, 2, ',', '.') }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- VISTA MÓVIL (TARJETAS) -->
                        <div class="d-block d-md-none">
                            @foreach ($cliente->ordenes as $o)
                                <div class="card shadow-sm border mb-3">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center p-2">
                                        <span class="font-weight-bold text-dark">Orden #{{ $o->id }}</span>
                                        <div>
                                            <span class="badge {{ $o->motivo === '1' ? 'bg-primary' : 'bg-orange text-white' }} mr-1">
                                                {{ $o->motivo === '1' ? 'Lavadero' : 'Lubricentro' }}
                                            </span>
                                            @switch((string)$o->estado)
                                                @case('100')
                                                    <span class="badge bg-success">Atendido</span>
                                                    @break
                                                @case('700')
                                                    <span class="badge bg-danger">Cancelado</span>
                                                    @break
                                                @case('10')
                                                    <span class="badge bg-info">En Proceso</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                            @endswitch
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <p class="mb-1 text-muted small">
                                            <i class="fas fa-calendar-alt text-warning mr-1"></i> Fecha: 
                                            <strong>{{ $o->fecha_turno ? \Carbon\Carbon::parse($o->fecha_turno)->format('d/m/Y') : '-' }}</strong>
                                            @if ($o->horario)
                                                ({{ \Carbon\Carbon::parse($o->horario)->format('H:i') }} hs)
                                            @endif
                                        </p>
                                        <p class="mb-2 text-muted small">
                                            <i class="fas fa-car text-warning mr-1"></i> Vehículo: 
                                            @if ($o->vehiculos)
                                                <strong>{{ optional(optional($o->vehiculos->modelos)->marcas)->descripcion }} {{ optional($o->vehiculos->modelos)->descripcion }}</strong>
                                                <span class="badge bg-warning text-dark ml-1">{{ $o->vehiculos->dominio }}</span>
                                            @else
                                                -
                                            @endif
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                            <div>
                                                <small class="text-muted d-block">Total de la Orden</small>
                                                <strong class="text-success h6 mb-0">${{ number_format($o->items->sum('subtotal'), 2, ',', '.') }}</strong>
                                            </div>
                                            <button class="btn btn-sm btn-outline-info" type="button" data-toggle="collapse" data-target="#itemsOrderMobile{{ $o->id }}" aria-expanded="false">
                                                <i class="fas fa-list mr-1"></i> {{ $o->items->count() }} Ítems
                                            </button>
                                        </div>

                                        <!-- DESGLOSE DE ÍTEMS EN MOBILE COLLAPSE -->
                                        <div class="collapse mt-3" id="itemsOrderMobile{{ $o->id }}">
                                            <div class="bg-light p-2 rounded border">
                                                <h6 class="font-weight-bold text-dark border-bottom pb-1 small">
                                                    <i class="fas fa-tools mr-1"></i> Productos / Servicios
                                                </h6>
                                                @if ($o->items->isEmpty())
                                                    <p class="text-muted small m-0">No hay detalles disponibles.</p>
                                                @else
                                                    <ul class="list-group list-group-flush small">
                                                        @foreach ($o->items as $i)
                                                            <li class="list-group-item bg-transparent px-0 py-1 d-flex justify-content-between">
                                                                <span>{{ $i->cantidad ?? 1 }}x {{ optional($i->productos)->descripcion ?? 'Servicio' }}</span>
                                                                <strong class="text-dark">${{ number_format($i->subtotal, 2, ',', '.') }}</strong>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif

                <!-- CONTENIDO PESTAÑA: MIS PRESUPUESTOS -->
                @if ($activeTab === 'presupuestos')
                    @if ($cliente->presupuestos->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-file-invoice fa-3x mb-3 text-secondary"></i>
                            <p class="m-0">No posee presupuestos registrados a su nombre.</p>
                        </div>
                    @else
                        <!-- VISTA ESCRITORIO / TABLET -->
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-hover table-striped align-middle">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>N° Presupuesto</th>
                                        <th>Fecha</th>
                                        <th>Cant. Ítems</th>
                                        <th>Monto Total</th>
                                        <th class="text-center">Comprobante</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cliente->presupuestos as $p)
                                        <tr>
                                            <td class="font-weight-bold">#{{ $p->id }}</td>
                                            <td>{{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y H:i') }} hs</td>
                                            <td>{{ $p->itemspres->count() }} ítem(s)</td>
                                            <td class="font-weight-bold text-success">
                                                ${{ number_format($p->itemspres->sum('subtotal'), 2, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ url('pdfpres/' . $p->id) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-file-pdf mr-1"></i> Descargar PDF
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- VISTA MÓVIL -->
                        <div class="d-block d-md-none">
                            @foreach ($cliente->presupuestos as $p)
                                <div class="card shadow-sm border mb-3">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="font-weight-bold m-0 text-dark">Presupuesto #{{ $p->id }}</h6>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y') }}</small>
                                        </div>
                                        <p class="text-muted small mb-2">
                                            Ítems detallados: <strong>{{ $p->itemspres->count() }}</strong>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                            <div>
                                                <small class="text-muted d-block">Monto Total</small>
                                                <strong class="text-success h6 mb-0">${{ number_format($p->itemspres->sum('subtotal'), 2, ',', '.') }}</strong>
                                            </div>
                                            <a href="{{ url('pdfpres/' . $p->id) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-file-pdf mr-1"></i> PDF
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif

                <!-- CONTENIDO PESTAÑA: MIS VEHÍCULOS -->
                @if ($activeTab === 'vehiculos')
                    @if ($cliente->vehiculos->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-car-side fa-3x mb-3 text-secondary"></i>
                            <p class="m-0">No posee vehículos registrados a su nombre.</p>
                        </div>
                    @else
                        <div class="row">
                            @foreach ($cliente->vehiculos as $v)
                                <div class="col-12 col-sm-6 col-md-4 mb-3">
                                    <div class="card card-outline card-warning shadow-sm h-100">
                                        <div class="card-body text-center p-3">
                                            <i class="fas fa-car fa-3x text-warning mb-2"></i>
                                            <h6 class="card-title font-weight-bold w-100 mb-1">
                                                {{ optional(optional($v->modelos)->marcas)->descripcion }} {{ optional($v->modelos)->descripcion }}
                                            </h6>
                                            <p class="text-muted small mb-2">Año: <strong>{{ $v->año ?? '-' }}</strong></p>
                                            <span class="badge bg-warning text-dark font-weight-bold p-2 px-3" style="font-size: 0.95rem;">
                                                {{ $v->dominio }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endif
</div>
