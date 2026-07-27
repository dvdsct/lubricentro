<div>
    @if (!$cliente)
        <div class="alert alert-warning text-center shadow-sm p-4">
            <i class="fas fa-exclamation-triangle fa-2x mb-2 text-warning"></i>
            <h5><strong>Su cuenta aún no se encuentra vinculada a un perfil de cliente.</strong></h5>
            <p class="mb-0">Por favor, comuníquese con el personal del taller para que vinculen su correo con sus datos de cliente.</p>
        </div>
    @else
        <!-- CABECERA DEL CLIENTE -->
        <div class="card card-dark card-outline shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center mb-3 mb-md-0">
                        <i class="fas fa-user-circle fa-5x text-secondary"></i>
                    </div>
                    <div class="col-md-6">
                        <h3 class="mb-1 font-weight-bold text-dark">
                            {{ optional(optional($cliente->perfiles)->personas)->nombre }}
                            {{ optional(optional($cliente->perfiles)->personas)->apellido }}
                        </h3>
                        <p class="text-muted mb-1">
                            <i class="fas fa-id-card mr-1"></i> DNI: <strong>{{ optional(optional($cliente->perfiles)->personas)->DNI ?? 'No registrado' }}</strong>
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-phone mr-1"></i> Teléfono: <strong>{{ optional(optional($cliente->perfiles)->personas)->numero_telefono ?? 'No registrado' }}</strong>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-right mt-3 mt-md-0">
                        <span class="badge bg-warning text-dark p-2 text-uppercase" style="font-size: 0.9rem;">
                            <i class="fas fa-car mr-1"></i> {{ $cliente->vehiculos->count() }} Vehículo(s) registrado(s)
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTOR DE PESTAÑAS -->
        <div class="card card-primary card-outline card-outline-tabs shadow-sm">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="portalTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'turnos' ? 'active font-weight-bold' : '' }}" 
                           href="#" wire:click.prevent="setTab('turnos')">
                            <i class="fas fa-calendar-check mr-1"></i> Mis Turnos y Órdenes ({{ $cliente->ordenes->count() }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'presupuestos' ? 'active font-weight-bold' : '' }}" 
                           href="#" wire:click.prevent="setTab('presupuestos')">
                            <i class="fas fa-file-invoice-dollar mr-1"></i> Mis Presupuestos ({{ $cliente->presupuestos->count() }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'vehiculos' ? 'active font-weight-bold' : '' }}" 
                           href="#" wire:click.prevent="setTab('vehiculos')">
                            <i class="fas fa-car mr-1"></i> Mis Vehículos ({{ $cliente->vehiculos->count() }})
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <!-- CONTENIDO PESTAÑA: MIS TURNOS Y ÓRDENES -->
                @if ($activeTab === 'turnos')
                    @if ($cliente->ordenes->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-calendar-times fa-3x mb-3 text-secondary"></i>
                            <p class="m-0">Aún no posee turnos ni órdenes registradas a su nombre.</p>
                        </div>
                    @else
                        <div class="table-responsive">
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
                                                    <span class="badge bg-warning text-dark font-weight-bold">{{ $o->vehiculos->dominio }}</span>
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
                                                        <span class="badge bg-success"><i class="fas fa-check-circle mr-1"></i> Atendido / Finalizado</span>
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
                                                <button class="btn btn-sm btn-outline-info" type="button" data-toggle="collapse" data-target="#itemsOrder{{ $o->id }}" aria-expanded="false">
                                                    <i class="fas fa-list mr-1"></i> Ver {{ $o->items->count() }} ítem(s)
                                                </button>
                                            </td>
                                        </tr>
                                        <!-- DESGLOSE DE ÍTEMS EN COLLAPSE -->
                                        <tr class="collapse" id="itemsOrder{{ $o->id }}">
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
                        <div class="table-responsive">
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
                                <div class="col-md-4 mb-3">
                                    <div class="card card-outline card-warning shadow-sm h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-car fa-4x text-warning mb-3"></i>
                                            <h5 class="card-title font-weight-bold w-100 mb-2">
                                                {{ optional(optional($v->modelos)->marcas)->descripcion }} {{ optional($v->modelos)->descripcion }}
                                            </h5>
                                            <p class="text-muted small mb-2">Año: <strong>{{ $v->año ?? '-' }}</strong></p>
                                            <span class="badge bg-warning text-dark font-weight-bold p-2" style="font-size: 1rem;">
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
