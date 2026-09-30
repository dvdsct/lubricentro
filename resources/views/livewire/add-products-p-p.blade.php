<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3" role="alert">
            <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- FLUJO VISUAL DE LA ORDEN DE COMPRA -->
    <div class="card card-outline card-secondary shadow-sm mb-3">
        <div class="card-body py-2 px-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="d-flex align-items-center mb-1">
                    <span class="badge {{ in_array($pedido->estado, ['borrador', 'pendiente', '2', '3']) ? 'badge-primary' : 'badge-success' }} mr-2 p-2">
                        1. ORDEN DE COMPRA
                    </span>
                    <i class="fas fa-arrow-right text-muted mx-2"></i>
                    <span class="badge {{ in_array($pedido->estado, ['autorizada', 'solicitada']) ? 'badge-primary' : (in_array($pedido->estado, ['recibido_total', 'recibido_incompleto_con_factura_total', 'recibido_parcial', 'cerrado']) ? 'badge-success' : ($pedido->estado === 'rechazada' ? 'badge-danger' : 'badge-secondary')) }} mr-2 p-2">
                        2. CONTROL DE ENTREGA
                    </span>
                    <i class="fas fa-arrow-right text-muted mx-2"></i>
                    <span class="badge {{ in_array($pedido->estado, ['recibido_total', 'recibido_incompleto_con_factura_total', 'recibido_parcial', 'cerrado']) ? 'badge-success' : 'badge-secondary' }} p-2">
                        3. CTA. CTE. & PAGOS
                    </span>
                </div>
                <div>
                    @if($pedido->estado === 'rechazada')
                        <span class="badge badge-danger px-3 py-2 font-weight-bold"><i class="fas fa-ban mr-1"></i> ENTREGA RECHAZADA</span>
                    @elseif($pedido->estado === 'recibido_total' || $pedido->estado === 'cerrado')
                        <span class="badge badge-success px-3 py-2 font-weight-bold"><i class="fas fa-check-double mr-1"></i> RECIBIDO TOTAL</span>
                    @elseif($pedido->estado === 'recibido_incompleto_con_factura_total')
                        <span class="badge badge-warning px-3 py-2 font-weight-bold"><i class="fas fa-exclamation-circle mr-1"></i> ESCENARIO 2: NC PENDIENTE</span>
                    @elseif($pedido->estado === 'recibido_parcial')
                        <span class="badge badge-info px-3 py-2 font-weight-bold"><i class="fas fa-boxes mr-1"></i> ESCENARIO 3: RECIBIDO PARCIAL</span>
                    @elseif($pedido->estado === 'solicitada')
                        <span class="badge badge-info px-3 py-2 font-weight-bold"><i class="fab fa-whatsapp mr-1"></i> SOLICITADA AL PROVEEDOR</span>
                    @elseif($pedido->estado === 'autorizada')
                        <span class="badge badge-primary px-3 py-2 font-weight-bold"><i class="fas fa-check mr-1"></i> AUTORIZADA POR DUEÑO</span>
                    @else
                        <span class="badge badge-secondary px-3 py-2 font-weight-bold"><i class="fas fa-edit mr-1"></i> BORRADOR / PENDIENTE AUTORIZACIÓN</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- TABLA DE ITEMS Y DETALLE -->
    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-header d-flex align-items-center justify-content-between py-2">
            <div>
                <button type="button" class="btn btn-success px-3 shadow-sm font-weight-bold mr-2" wire:click='modalProdOn'
                        @if(in_array($pedido->estado, ['recibido_total', 'cerrado', 'rechazada'])) disabled @endif>
                    <i class="fas fa-plus-circle mr-1"></i> Agregar Ítem
                </button>
                <a href="{{ route('pdf.pedido', $pedido->id) }}" target="_blank" class="btn btn-outline-secondary px-3 shadow-sm font-weight-bold">
                    <i class="fas fa-print mr-1"></i> Imprimir OC
                </a>
            </div>
            <div class="text-right">
                <span class="text-muted small">Fecha OC: <strong>{{ $pedido->fecha_ingreso ? \Carbon\Carbon::parse($pedido->fecha_ingreso)->format('d/m/Y') : $pedido->created_at->format('d/m/Y') }}</strong></span>
            </div>
        </div>

        <div class="card-body p-0 table-responsive">
            <table class="table table-hover table-striped table-bordered align-middle mb-0 text-sm">
                <thead class="thead-light">
                    <tr class="text-center">
                        <th style="width: 70px;">#</th>
                        <th class="text-left">Producto</th>
                        <th style="width: 140px;">Costo Unit.</th>
                        <th style="width: 120px;">Cant. Pedida</th>
                        <th style="width: 140px;">Subtotal</th>
                        <th style="width: 120px;">Cant. Recibida</th>
                        <th style="width: 130px;">Estado Ítem</th>
                        <th style="width: 100px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pedido->items as $i)
                    @php
                        $ppi = $ppiByProduct->get($i->producto_id);
                        $pedida = $ppi->cantidad_pedida ?? $i->cantidad ?? 0;
                        $recibida = $ppi->cantidad_recibida ?? 0;
                        $pendiente = max(0, intval($pedida) - intval($recibida));
                        $est = $ppi->estado_item ?? 'pendiente';
                        $bloqueadoEdicion = in_array($pedido->estado, ['recibido_total', 'cerrado', 'rechazada']) || intval($recibida) > 0;
                    @endphp
                    <tr>
                        <td class="text-center font-weight-bold text-muted">{{ $i->productos->id }}</td>
                        <td>
                            <strong>{{ $i->productos->descripcion }}</strong>
                            @if($i->productos->codigo && $i->productos->codigo !== '-')
                                <small class="text-muted d-block"><i class="fas fa-barcode mr-1"></i>{{ $i->productos->codigo }}</small>
                            @endif
                        </td>

                        @if ($i->estado == 1)
                        <td class="text-right">
                            <div class="input-group input-group-sm" style="max-width: 140px; margin-left: auto;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number" step="0.01" min="0" class="form-control text-right"
                                       placeholder="0.00" wire:model='precio' wire:keydown.enter='addCantidad({{ $i->id }})'>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="input-group input-group-sm" style="max-width: 120px; margin: 0 auto;">
                                <input type="number" min="1" class="form-control text-center"
                                       placeholder="Cant." wire:model='cantidad' wire:keydown.enter='addCantidad({{ $i->id }})' autofocus>
                                <div class="input-group-append">
                                    <button class="btn btn-success" wire:click='addCantidad({{ $i->id }})' title="Confirmar">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td class="text-center text-muted">-</td>
                        @else
                        <td class="text-right">
                            $ {{ number_format((float)($i->precio ?? 0), 2, '.', ',') }}
                        </td>
                        <td class="text-center font-weight-bold">
                            {{ $pedida }}
                        </td>
                        <td class="text-right font-weight-bold text-dark">
                            $ {{ number_format((float)($i->subtotal ?? 0), 2, '.', ',') }}
                        </td>
                        @endif

                        <td class="text-center font-weight-bold {{ $recibida >= $pedida && $pedida > 0 ? 'text-success' : ($recibida > 0 ? 'text-warning' : 'text-muted') }}">
                            {{ $recibida }} / {{ $pedida }}
                        </td>

                        <td class="text-center">
                            @if ($est === 'recibido_total' || ($recibida >= $pedida && $pedida > 0))
                                <span class="badge badge-success"><i class="fas fa-check mr-1"></i> Completo</span>
                            @elseif ($est === 'recibido_parcial' || $recibida > 0)
                                <span class="badge badge-warning"><i class="fas fa-hourglass-half mr-1"></i> Parcial</span>
                            @else
                                <span class="badge badge-secondary">Pendiente</span>
                            @endif
                        </td>

                        <td class="text-center">
                            @if(!$bloqueadoEdicion)
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-secondary" wire:click='editProd({{ $i->id }})' title="Editar">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" wire:click='delProd({{ $i->id }})'
                                            wire:confirm="¿Estás seguro de eliminar este ítem?" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @else
                                <span class="text-muted small"><i class="fas fa-lock text-secondary"></i></span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-box-open fa-2x d-block mb-2 text-secondary"></i>
                            No hay ítems cargados en esta orden. Haz clic en <strong>Agregar Ítem</strong> para comenzar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-light d-flex justify-content-between align-items-center py-3 flex-wrap">
            <div class="text-muted small mb-2 mb-sm-0">
                @if($pedido->condiciones_pago)
                    <span class="mr-3">Condiciones de pago: <strong>{{ $pedido->condiciones_pago }}</strong></span>
                @endif
                @if($pedido->autorizado_por)
                    <span class="text-success"><i class="fas fa-check-circle mr-1"></i> Autorizado ({{ $pedido->autorizacion_tipo }}) por {{ $pedido->autorizadoPor?->name ?? 'Dueño' }}</span>
                @endif
            </div>
            <div>
                <h4 class="mb-0 text-dark">
                    <strong>TOTAL OC:</strong>
                    <span class="text-success ml-2 font-weight-bold">${{ number_format((float)($total ?? 0), 2, '.', ',') }}</span>
                </h4>
            </div>
        </div>
    </div>

    <!-- PANEL DE ACCIONES SEGÚN EL FLUJO DE 5 FASES -->
    <div class="row">
        <!-- FASE 1: AUTORIZACIÓN DEL DUEÑO -->
        <div class="col-md-4 mb-3">
            <div class="card card-outline {{ $pedido->autorizado_por ? 'card-success' : 'card-warning' }} shadow-sm h-100 mb-0">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="mr-3 {{ $pedido->autorizado_por ? 'text-success' : 'text-warning' }}">
                                <i class="fas fa-user-check fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold mb-0 text-dark">1. Autorización del Dueño</h6>
                                <small class="text-muted">Aprobación digital o firma física</small>
                            </div>
                        </div>
                        @if($pedido->autorizado_por)
                            <div class="alert alert-light border py-1 px-2 text-xs mb-0">
                                <strong>Autorizada:</strong> {{ $pedido->fecha_autorizacion?->format('d/m/Y H:i') }}<br>
                                <strong>Tipo:</strong> {{ ucfirst($pedido->autorizacion_tipo) }} {{ $pedido->autorizacion_notas ? ' - ' . $pedido->autorizacion_notas : '' }}
                            </div>
                        @else
                            <p class="text-muted small mb-0">Requiere autorización previa para enviar la solicitud al proveedor.</p>
                        @endif
                    </div>
                    @if(!$pedido->autorizado_por && !in_array($pedido->estado, ['rechazada', 'cerrado', 'recibido_total']))
                        <div class="mt-3">
                            <button type="button" class="btn btn-warning btn-block font-weight-bold" wire:click="openModalAutorizacion">
                                <i class="fas fa-pen-nib mr-1"></i> Autorizar Orden de Compra
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- FASE 1B: SOLICITAR AL PROVEEDOR -->
        <div class="col-md-4 mb-3">
            <div class="card card-outline {{ $pedido->fecha_solicitud ? 'card-success' : 'card-info' }} shadow-sm h-100 mb-0">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="mr-3 {{ $pedido->fecha_solicitud ? 'text-success' : 'text-info' }}">
                                <i class="fab fa-whatsapp fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold mb-0 text-dark">Solicitud al Proveedor</h6>
                                <small class="text-muted">Pedido por WhatsApp, teléfono o email</small>
                            </div>
                        </div>
                        @if($pedido->fecha_solicitud)
                            <div class="alert alert-light border py-1 px-2 text-xs mb-0">
                                <strong>Solicitado:</strong> {{ $pedido->fecha_solicitud?->format('d/m/Y H:i') }}<br>
                                <strong>Medio:</strong> {{ strtoupper($pedido->solicitado_medio) }}
                            </div>
                        @else
                            <p class="text-muted small mb-0">Marcar como enviado una vez solicitado el pedido al proveedor.</p>
                        @endif
                    </div>
                    @if(!$pedido->fecha_solicitud && !in_array($pedido->estado, ['rechazada', 'cerrado', 'recibido_total']))
                        <div class="mt-3 d-flex">
                            <select class="form-control form-control-sm mr-2" wire:model="solicitudMedio" style="max-width: 120px;">
                                <option value="whatsapp">WhatsApp</option>
                                <option value="telefono">Teléfono</option>
                                <option value="email">Email</option>
                            </select>
                            <button type="button" class="btn btn-info btn-block font-weight-bold" wire:click="solicitarProveedor"
                                    @if(!$pedido->autorizado_por) disabled title="Primero debe autorizarse la OC" @endif>
                                <i class="fas fa-paper-plane mr-1"></i> Marcar Solicitado
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- FASE 2: CONTROL DE ENTREGA Y FACTURA -->
        <div class="col-md-4 mb-3">
            <div class="card card-outline {{ in_array($pedido->estado, ['recibido_total', 'recibido_incompleto_con_factura_total', 'recibido_parcial', 'cerrado']) ? 'card-success' : ($pedido->estado === 'rechazada' ? 'card-danger' : 'card-primary') }} shadow-sm h-100 mb-0">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="mr-3 {{ in_array($pedido->estado, ['recibido_total', 'recibido_incompleto_con_factura_total', 'recibido_parcial', 'cerrado']) ? 'text-success' : ($pedido->estado === 'rechazada' ? 'text-danger' : 'text-primary') }}">
                                <i class="fas fa-boxes fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold mb-0 text-dark">2. Control de Entrega</h6>
                                <small class="text-muted">Cotejo OC, mercadería y factura</small>
                            </div>
                        </div>
                        @if($pedido->estado === 'rechazada')
                            <div class="alert alert-danger py-1 px-2 text-xs mb-0">
                                <strong>Entrega Rechazada:</strong> {{ $pedido->motivo_rechazo }}
                            </div>
                        @elseif(in_array($pedido->estado, ['recibido_total', 'recibido_incompleto_con_factura_total', 'recibido_parcial', 'cerrado']))
                            <div class="alert alert-light border py-1 px-2 text-xs mb-0">
                                <strong>Escenario:</strong> {{ strtoupper(str_replace('_', ' ', $pedido->escenario_recepcion ?? 'Recibido')) }}<br>
                                <strong>Fecha recepción:</strong> {{ $pedido->fecha_recepcion?->format('d/m/Y H:i') }}
                            </div>
                        @else
                            <p class="text-muted small mb-0">Cotejar mercadería recibida contra factura y registrar en Cta. Cte.</p>
                        @endif
                    </div>
                    @if(!in_array($pedido->estado, ['recibido_total', 'cerrado', 'rechazada']))
                        <div class="mt-3 d-flex">
                            <button type="button" class="btn btn-primary btn-block font-weight-bold mr-2" wire:click="openModalRecepcion">
                                <i class="fas fa-truck-loading mr-1"></i> Control Recepción
                            </button>
                            <button type="button" class="btn btn-outline-danger" wire:click="openModalRechazo" title="Rechazar Entrega">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @else
                        <div class="mt-3">
                            <a href="{{ route('proveedores.index') }}" class="btn btn-outline-primary btn-block font-weight-bold">
                                <i class="fas fa-file-invoice-dollar mr-1"></i> Ver Cuenta Corriente
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN DE FACTURAS Y NOTAS DE CRÉDITO GENERADAS PARA ESTA OC -->
    @if($facturasOC->isNotEmpty() || $notasCreditoOC->isNotEmpty())
    <div class="card card-outline card-info shadow-sm mb-4">
        <div class="card-header py-2">
            <h5 class="card-title font-weight-bold mb-0">
                <i class="fas fa-file-invoice mr-2 text-info"></i> Facturas y Notas de Crédito Asociadas en Cuenta Corriente
            </h5>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-bordered table-hover mb-0 text-sm">
                <thead class="thead-light">
                    <tr>
                        <th>Tipo Comprobante</th>
                        <th>Número</th>
                        <th>Fecha Emisión</th>
                        <th class="text-right">Total Facturado</th>
                        <th class="text-right">Monto Bloqueado (NC)</th>
                        <th class="text-right">Disponible para Pago</th>
                        <th>Estado</th>
                        <th class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($facturasOC as $f)
                    <tr>
                        <td><strong>Factura {{ $f->tipoFactura?->descripcion ?? '' }}</strong></td>
                        <td>{{ $f->numero_factura ?: ('FAC-' . $f->id) }}</td>
                        <td>{{ $f->fecha_emision ? \Carbon\Carbon::parse($f->fecha_emision)->format('d/m/Y') : '-' }}</td>
                        <td class="text-right font-weight-bold">$ {{ number_format((float)$f->total, 2, '.', ',') }}</td>
                        <td class="text-right font-weight-bold text-danger">
                            @if($f->monto_bloqueado > 0)
                                <span class="badge badge-warning p-1">$ {{ number_format((float)$f->monto_bloqueado, 2, '.', ',') }}</span>
                            @else
                                $ 0.00
                            @endif
                        </td>
                        <td class="text-right font-weight-bold text-success">
                            $ {{ number_format((float)$f->monto_disponible_pago, 2, '.', ',') }}
                        </td>
                        <td>
                            @if($f->estado === 'pagada')
                                <span class="badge badge-success"><i class="fas fa-check mr-1"></i> PAGADA</span>
                            @elseif($f->estado === 'bloqueada_parcial')
                                <span class="badge badge-warning"><i class="fas fa-lock mr-1"></i> BLOQUEO POR NC</span>
                            @elseif($f->estado === 'parcial')
                                <span class="badge badge-info">PAGO PARCIAL</span>
                            @else
                                <span class="badge badge-primary">PENDIENTE PAGO</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($f->monto_disponible_pago > 0 && $f->estado !== 'pagada')
                                <a href="{{ route('ordenes-pago.create', ['proveedor_id' => $pedido->proveedor_id, 'factura_id' => $f->id]) }}" class="btn btn-success btn-xs font-weight-bold">
                                    <i class="fas fa-bolt mr-1"></i> Pago Inmediato (Ruta A)
                                </a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach

                    @foreach($notasCreditoOC as $nc)
                    <tr class="bg-light">
                        <td><strong class="text-danger">Nota de Crédito</strong></td>
                        <td>{{ $nc->numero ?: 'Pendiente de emisión' }}</td>
                        <td>{{ $nc->fecha_emision ? \Carbon\Carbon::parse($nc->fecha_emision)->format('d/m/Y') : '-' }}</td>
                        <td class="text-right font-weight-bold text-danger">- $ {{ number_format((float)$nc->monto, 2, '.', ',') }}</td>
                        <td class="text-right">-</td>
                        <td class="text-right">-</td>
                        <td>
                            @if($nc->estado === 'aplicada')
                                <span class="badge badge-success">APLICADA</span>
                            @else
                                <span class="badge badge-warning">RECLAMADA / PENDIENTE EMISIÓN</span>
                            @endif
                        </td>
                        <td class="text-center small text-muted">{{ $nc->motivo }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- MODAL 1: AUTORIZACIÓN DEL DUEÑO -->
    @if($modalAutorizacion)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-user-check mr-2"></i> Autorizar Orden de Compra #{{ $pedido->id }}</h5>
                    <button type="button" class="close" wire:click="closeModalAutorizacion"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info text-sm mb-3">
                        Total a autorizar: <strong>${{ number_format((float)$total, 2, '.', ',') }}</strong> para el proveedor <strong>{{ $proveedor?->nombre_completo ?? '' }}</strong>.
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Tipo de Autorización:</label>
                        <select class="form-control" wire:model="authTipo">
                            <option value="digital">Aprobación Digital (en el sistema)</option>
                            <option value="fisica">Firma Física Documentada</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Observaciones / Nota de Autorización:</label>
                        <textarea class="form-control" rows="2" wire:model="authNotas" placeholder="Ej: Aprobado conforme presupuesto telefónico / OK dueño"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" wire:click="closeModalAutorizacion">Cancelar</button>
                    <button type="button" class="btn btn-warning font-weight-bold" wire:click="autorizarOC">
                        <i class="fas fa-check mr-1"></i> Confirmar Autorización
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL 2: CONTROL DE RECEPCIÓN Y COTEJO (ESCENARIOS 1, 2, 3) -->
    @if($modalRecepcion)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5); overflow-y: auto;">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fas fa-truck-loading mr-2"></i> Control de Entrega: OC, Mercadería y Factura
                    </h5>
                    <button type="button" class="close text-white" wire:click="closeModalRecepcion"><span>&times;</span></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted text-sm mb-3">
                        Verifique artículos, cantidades, importe y condiciones. Registre solamente la mercadería aceptada.
                    </p>

                    <!-- DATOS DE LA FACTURA DEL PROVEEDOR -->
                    <div class="card card-outline card-info mb-3">
                        <div class="card-header py-2">
                            <strong class="text-sm"><i class="fas fa-file-invoice mr-1"></i> Datos de la Factura del Proveedor</strong>
                        </div>
                        <div class="card-body py-2">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <label class="small font-weight-bold">Tipo Factura:</label>
                                    <select class="form-control form-control-sm" wire:model="tipoFacturaId">
                                        @foreach($tiposFactura as $tf)
                                            <option value="{{ $tf->id }}">{{ $tf->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="small font-weight-bold">Nro. Factura (*):</label>
                                    <input type="text" class="form-control form-control-sm @error('numeroFactura') is-invalid @enderror"
                                           wire:model="numeroFactura" placeholder="Ej: 0001-00045231">
                                    @error('numeroFactura') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="small font-weight-bold">Fecha Factura (*):</label>
                                    <input type="date" class="form-control form-control-sm @error('fechaFactura') is-invalid @enderror"
                                           wire:model="fechaFactura">
                                    @error('fechaFactura') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="small font-weight-bold">Importe Total Facturado (*):</label>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                        <input type="number" step="0.01" class="form-control form-control-sm text-right font-weight-bold @error('totalFactura') is-invalid @enderror"
                                               wire:model.live.debounce.300ms="totalFactura" placeholder="0.00">
                                    </div>
                                    @error('totalFactura') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CANTIDADES RECIBIDAS POR ITEM -->
                    <div class="card card-outline card-secondary mb-3">
                        <div class="card-header py-2">
                            <strong class="text-sm"><i class="fas fa-clipboard-check mr-1"></i> Cantidad de Mercadería Aceptada por Producto</strong>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <table class="table table-sm table-bordered table-striped mb-0 text-sm">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th style="width: 100px;" class="text-right">Costo Unit.</th>
                                        <th style="width: 100px;" class="text-center">Cant. Pedida</th>
                                        <th style="width: 130px;" class="text-center">Cant. Aceptada</th>
                                        <th style="width: 120px;" class="text-right">Subtotal Aceptado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $subtotalAceptadoModal = 0; @endphp
                                    @foreach($pedido->items as $item)
                                    @php
                                        $ppi = $ppiByProduct->get($item->producto_id);
                                        $cantPed = intval($ppi->cantidad_pedida ?? $item->cantidad ?? 0);
                                        $costo = floatval($ppi->costo_unitario ?? $item->precio ?? 0);
                                        $cantRec = intval($receiveQty[$item->producto_id] ?? 0);
                                        $subAceptado = $cantRec * $costo;
                                        $subtotalAceptadoModal += $subAceptado;
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $item->productos->descripcion }}</strong></td>
                                        <td class="text-right">$ {{ number_format($costo, 2, '.', ',') }}</td>
                                        <td class="text-center font-weight-bold">{{ $cantPed }}</td>
                                        <td class="text-center">
                                            <input type="number" min="0" max="{{ $cantPed }}" class="form-control form-control-sm text-center font-weight-bold mx-auto"
                                                   style="max-width: 90px;" wire:model.live.debounce.300ms="receiveQty.{{ $item->producto_id }}">
                                        </td>
                                        <td class="text-right font-weight-bold">$ {{ number_format($subAceptado, 2, '.', ',') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light font-weight-bold">
                                        <td colspan="4" class="text-right">TOTAL MERCADERÍA ACEPTADA:</td>
                                        <td class="text-right text-success font-weight-bold">$ {{ number_format($subtotalAceptadoModal, 2, '.', ',') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- SELECTOR / GUÍA DEL ESCENARIO DETECTADO -->
                    <div class="card border mb-0">
                        <div class="card-header py-2 font-weight-bold bg-light">
                            <span>Escenario de Recepción Aplicado:</span>
                        </div>
                        <div class="card-body py-2">
                            <div class="custom-control custom-radio mb-2">
                                <input type="radio" id="esc1" value="escenario_1" wire:model.live="escenarioModal" class="custom-control-input">
                                <label class="custom-control-label font-weight-bold text-success" for="esc1">
                                    ESCENARIO 1: Entrega completa
                                </label>
                                <small class="d-block text-muted">Factura = OC = mercadería entregada. Confirmar recepción y validar factura. Ingresar stock de lo aceptado.</small>
                            </div>
                            <div class="custom-control custom-radio mb-2">
                                <input type="radio" id="esc2" value="escenario_2" wire:model.live="escenarioModal" class="custom-control-input">
                                <label class="custom-control-label font-weight-bold text-warning" for="esc2">
                                    ESCENARIO 2: Entrega incompleta; factura total
                                </label>
                                <small class="d-block text-muted">Factura = OC, pero faltan productos. Se acepta lo recibido: reclamar nota de crédito por faltante y <strong>bloquear ese importe para pago</strong> en Cta. Cte.</small>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="esc3" value="escenario_3" wire:model.live="escenarioModal" class="custom-control-input">
                                <label class="custom-control-label font-weight-bold text-info" for="esc3">
                                    ESCENARIO 3: Entrega y factura parciales
                                </label>
                                <small class="d-block text-muted">La factura coincide con lo entregado. Recibir parcialmente, ingresar stock aceptado y dejar saldo de OC pendiente. No requiere NC.</small>
                            </div>

                            @if($escenarioModal === 'escenario_2')
                                @php $difFaltante = max(0, floatval($totalFactura) - $subtotalAceptadoModal); @endphp
                                <div class="alert alert-warning mt-3 mb-0 p-2 text-xs">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    <strong>Control Escenario 2:</strong> Se registrará una Nota de Crédito pendiente por <strong>${{ number_format($difFaltante, 2, '.', ',') }}</strong> y se bloqueará dicho importe de la factura para pago hasta que el proveedor emita la NC.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" wire:click="closeModalRecepcion">Cancelar</button>
                    <button type="button" class="btn btn-primary font-weight-bold" wire:click="procesarRecepcion">
                        <i class="fas fa-check-circle mr-1"></i> Confirmar y Validar Recepción
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL 3: RECHAZO DE ENTREGA -->
    @if($modalRechazo)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-ban mr-2"></i> Rechazar Entrega de Orden de Compra</h5>
                    <button type="button" class="close text-white" wire:click="closeModalRechazo"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning text-sm mb-3">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Al registrar el rechazo de la entrega: <strong>no se ingresará stock ni se habilitará la factura para pago</strong>.
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Motivo del Rechazo (*):</label>
                        <textarea class="form-control @error('motivoRechazoText') is-invalid @enderror" rows="3" wire:model="motivoRechazoText"
                                  placeholder="Indique detalladamente el motivo (mercadería dañada, productos erróneos, etc.)..."></textarea>
                        @error('motivoRechazoText') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" wire:click="closeModalRechazo">Cancelar</button>
                    <button type="button" class="btn btn-danger font-weight-bold" wire:click="rechazarEntrega">
                        <i class="fas fa-ban mr-1"></i> Confirmar Rechazo
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL 4: AGREGAR ITEM A LA OC -->
    @if ($modal == true)
    <div class="modal fade show" id="modal-lg" style="display: block; background-color: rgba(0, 0, 0, 0.5);" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title font-weight-bold mb-0">
                        <i class="fas fa-search-plus mr-2"></i> AGREGAR ÍTEM A LA ORDEN DE COMPRA
                    </h5>
                    <button type="button" class="close text-white" wire:click='modalProdOff'>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-3" style="max-height: 75vh; overflow-y: auto;">
                    <div class="row mb-3">
                        <div class="col-md-8 col-sm-8 mb-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" wire:model='query' wire:keydown='search' class="form-control" placeholder="Buscar por código o descripción...">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 mb-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text small">Mostrar</span>
                                </div>
                                <select class="form-control" wire:model='perPage'>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped text-sm mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 60px" class="text-center">#</th>
                                    <th>Producto</th>
                                    <th style="width: 80px" class="text-center">Stock</th>
                                    <th style="width: 120px" class="text-right">Precio Costo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stock as $i)
                                <tr wire:click='addedProduct({{ $i->producto_id }})' wire:loading.attr="disabled" style="cursor: pointer;">
                                    <td class="text-center text-muted font-weight-bold">{{ $i->producto_id }}</td>
                                    <td>
                                        <strong>{{ $i->descripcion }}</strong>
                                        @if($i->codigo && $i->codigo !== '-')
                                            <small class="text-muted d-block"><i class="fas fa-barcode mr-1"></i>{{ $i->codigo }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($i->cantidad == 0)
                                            <span class="badge badge-danger px-2 py-1">{{ $i->cantidad }}</span>
                                        @else
                                            <span class="badge badge-success px-2 py-1">{{ $i->cantidad }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right font-weight-bold text-dark">$ {{ number_format((float)($i->costo ?? 0), 2, '.', ',') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted">No se encontraron productos coincidentes.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 d-flex justify-content-center">
                        {{ $stock->links() }}
                    </div>
                </div>
                <div class="modal-footer py-2 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" wire:click='modalProdOff'>
                        <i class="fas fa-times mr-1"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
