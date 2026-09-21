<div>
    <!-- MODAL DE PAGAR FACTURA -->
    @if ($modal == true)
        <div class="modal fade show" id="modal-lg" style="display: block; background-color: rgba(0, 0, 0, 0.5);"
            aria-modal="true" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title font-weight-bold mb-0">
                            <i class="fas fa-file-invoice-dollar mr-2"></i>
                            @if($pagoDe === 'pedido')
                                PAGAR ORDEN DE COMPRA
                            @else
                                PAGAR FACTURA
                            @endif
                        </h5>
                        <button type="button" class="close text-white" wire:click='closeModal'>
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-3" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                        <!-- TIPO DE FACTURA Y TIPO DE PAGO -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipoFactura" class="form-label font-weight-bold">Tipo de Factura</label>
                                <select wire:model.live="tipoFactura" id="tipoFactura" class="form-control">
                                    <option value="">Seleccione factura</option>
                                    @foreach ($tiposFactura as $tf)
                                        <option value="{{ $tf->id }}">{{ $tf->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tipo_pago" class="form-label font-weight-bold">Tipo de Pago</label>
                                <select wire:model.live="tipoPago" id="tipo_pago" class="form-control">
                                    @foreach ($tiposPago as $t)
                                        <option value="{{ $t->id }}">{{ $t->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- CLIENTE O PROVEEDOR -->
                        <div class="mb-3">
                            @if($pagoDe === 'pedido')
                                <label class="form-label font-weight-bold">Proveedor</label>
                                <input type="text" class="form-control bg-light" readonly
                                       value="{{ $pedido?->proveedores?->nombre_fantasia ?: ($pedido?->proveedores?->perfiles?->personas?->nombre ?? 'Proveedor') }}">
                            @else
                                <label for="cliente_select" class="form-label font-weight-bold">Cliente</label>
                                <select wire:model.live="cliente" id="cliente_select" class="form-control">
                                    <option value="">Seleccionar cliente</option>
                                    @foreach ($clientes ?? [] as $c)
                                        <option value="{{ $c->id }}">
                                            {{ ($c->perfiles?->personas?->nombre ?? '') . ' ' . ($c->perfiles?->personas?->apellido ?? '') . ' ' . ($c->perfiles?->personas?->dni ?? '') }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <!-- SELECCIÓN DE MEDIOS Y MONTOS DE PAGO -->
                        @if ($this->isDiferido())
                            <div class="alert alert-secondary py-2 mb-3 small">
                                <i class="fas fa-info-circle mr-1"></i>
                                <strong>Pago diferido:</strong> Indique los dos métodos de pago y el monto correspondiente a cada uno.
                            </div>

                            <!-- MÉTODO 1 -->
                            <div class="card card-outline card-primary mb-3 shadow-sm">
                                <div class="card-header py-2 bg-light">
                                    <h6 class="card-title font-weight-bold mb-0 text-primary small">
                                        <i class="fas fa-money-check-alt mr-1"></i> Primer Método de Pago
                                    </h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label for="medio_pago_1" class="form-label font-weight-bold small">Medio de Pago 1</label>
                                            <select class="form-control form-control-sm" wire:model.live="medioPago" id="medio_pago_1">
                                                <option value="">Seleccione el medio</option>
                                                @foreach ($mediosPago as $m)
                                                    <option value="{{ $m->id }}">{{ $m->descripcion }}</option>
                                                @endforeach
                                            </select>
                                            @error('medioPago')
                                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="monto1" class="form-label font-weight-bold small">Monto 1</label>
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="number" step="0.01" class="form-control" id="monto1" wire:model.live="monto1" placeholder="0.00">
                                            </div>
                                            @error('monto1')
                                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    @if ($medioPago == 3)
                                        <!-- CHEQUE 1 -->
                                        <div class="row mt-2">
                                            <div class="col-md-4 mb-2">
                                                <label for="banco_1" class="form-label font-weight-bold small">Banco</label>
                                                <select class="form-control form-control-sm" wire:model='banco' id="banco_1">
                                                    <option value="">Seleccione banco</option>
                                                    @foreach ($bancos as $b)
                                                        <option value="{{ $b->id }}">{{ $b->descripcion }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label for="fechaCheque_1" class="form-label font-weight-bold small">Vencimiento</label>
                                                <input type="date" wire:model="fechaCheque" id="fechaCheque_1" class="form-control form-control-sm">
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label for="nroCheque_1" class="form-label font-weight-bold small">N° de Cheque</label>
                                                <input type="text" class="form-control form-control-sm" wire:model="nroCheque" id="nroCheque_1">
                                            </div>
                                        </div>
                                    @elseif ($medioPago == 5)
                                        <!-- TRANSFERENCIA 1 -->
                                        <div class="mt-2">
                                            <label for="cupon_1" class="form-label font-weight-bold small">N° Operación / Comprobante</label>
                                            <input type="text" class="form-control form-control-sm" wire:model="cupon" id="cupon_1">
                                        </div>
                                    @elseif ($medioPago == 4)
                                        <!-- CUENTA CORRIENTE 1 -->
                                        <div class="alert alert-warning py-1 px-2 mt-2 mb-0 small">
                                            <strong>Cuenta Corriente:</strong> se imputa en cuenta corriente para conciliar posteriormente.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- MÉTODO 2 -->
                            <div class="card card-outline card-info mb-3 shadow-sm">
                                <div class="card-header py-2 bg-light">
                                    <h6 class="card-title font-weight-bold mb-0 text-info small">
                                        <i class="fas fa-money-check mr-1"></i> Segundo Método de Pago
                                    </h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label for="medio_pago_2" class="form-label font-weight-bold small">Medio de Pago 2</label>
                                            <select class="form-control form-control-sm" wire:model.live="medioPago2" id="medio_pago_2">
                                                <option value="">Seleccione el medio</option>
                                                @foreach ($mediosPago as $m)
                                                    <option value="{{ $m->id }}">{{ $m->descripcion }}</option>
                                                @endforeach
                                            </select>
                                            @error('medioPago2')
                                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="monto2" class="form-label font-weight-bold small">Monto 2</label>
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="number" step="0.01" class="form-control" id="monto2" wire:model.live="monto2" placeholder="0.00">
                                            </div>
                                            @error('monto2')
                                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    @if ($medioPago2 == 3)
                                        <!-- CHEQUE 2 -->
                                        <div class="row mt-2">
                                            <div class="col-md-4 mb-2">
                                                <label for="banco_2" class="form-label font-weight-bold small">Banco</label>
                                                <select class="form-control form-control-sm" wire:model='banco2' id="banco_2">
                                                    <option value="">Seleccione banco</option>
                                                    @foreach ($bancos as $b)
                                                        <option value="{{ $b->id }}">{{ $b->descripcion }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label for="fechaCheque_2" class="form-label font-weight-bold small">Vencimiento</label>
                                                <input type="date" wire:model="fechaCheque2" id="fechaCheque_2" class="form-control form-control-sm">
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label for="nroCheque_2" class="form-label font-weight-bold small">N° de Cheque</label>
                                                <input type="text" class="form-control form-control-sm" wire:model="nroCheque2" id="nroCheque_2">
                                            </div>
                                        </div>
                                    @elseif ($medioPago2 == 5)
                                        <!-- TRANSFERENCIA 2 -->
                                        <div class="mt-2">
                                            <label for="codeOp2" class="form-label font-weight-bold small">N° Operación / Comprobante</label>
                                            <input type="text" class="form-control form-control-sm" wire:model="codeOp2" id="codeOp2">
                                        </div>
                                    @elseif ($medioPago2 == 4)
                                        <!-- CUENTA CORRIENTE 2 -->
                                        <div class="alert alert-warning py-1 px-2 mt-2 mb-0 small">
                                            <strong>Cuenta Corriente:</strong> se imputa en cuenta corriente para conciliar posteriormente.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <!-- MEDIO DE PAGO SIMPLE -->
                            <div class="mb-3">
                                <label for="medio_pago" class="form-label font-weight-bold">Medio de Pago</label>
                                <select class="form-control" wire:model.live="medioPago" id="medio_pago">
                                    <option value="">Seleccione el medio</option>
                                    @foreach ($mediosPago as $m)
                                        <option value="{{ $m->id }}">{{ $m->descripcion }}</option>
                                    @endforeach
                                </select>
                                @error('medioPago')
                                    <span class="text-danger small mt-1 d-block">** Seleccione un medio de pago</span>
                                @enderror
                            </div>

                            @if ($medioPago == 4)
                                <div class="alert alert-warning py-2 text-sm" role="alert">
                                    <strong>Cuenta Corriente seleccionada:</strong> no se registra movimiento en Caja física en este momento. Se imputa en Cuenta Corriente para conciliar posteriormente.
                                </div>
                            @endif

                            @if ($debitoId && (string)$medioPago === (string)$debitoId)
                                <div class="alert alert-info py-2 text-sm" role="alert">
                                    <strong>Tarjeta Débito seleccionada:</strong> se aplica un recargo del 5% sobre el monto luego de aplicar descuentos.
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="debitoCupon" class="form-label font-weight-bold small">N° de cupón</label>
                                        <input type="text" id="debitoCupon" class="form-control form-control-sm" wire:model.live="debitoCupon">
                                        @error('debitoCupon')<span class="text-danger small">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="debitoLote" class="form-label font-weight-bold small">N° de lote</label>
                                        <input type="text" id="debitoLote" class="form-control form-control-sm" wire:model.live="debitoLote">
                                        @error('debitoLote')<span class="text-danger small">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="debitoAutorizacion" class="form-label font-weight-bold small">N° autorización</label>
                                        <input type="text" id="debitoAutorizacion" class="form-control form-control-sm" wire:model.live="debitoAutorizacion">
                                        @error('debitoAutorizacion')<span class="text-danger small">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            @endif

                            <!-- DESCUENTOS (solo para ventas / ordenes) -->
                            @if ($pagoDe === 'orden')
                                <div class="mb-3">
                                    <label for="descuentoId" class="form-label font-weight-bold">Descuento</label>
                                    <select id="descuentoId" class="form-control" wire:model.live="descuentoId">
                                        <option value="">Sin descuento</option>
                                        @foreach($descuentos as $d)
                                            <option value="{{ $d->id }}">{{ $d->descripcion }} ({{ $d->porcentaje }}%)</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <!-- PAGO DIVIDIDO (solo en pago total de orden) -->
                            @if ($tipoPago == 2 && $pagoDe === 'orden')
                                <div class="mb-3 p-3 bg-light border rounded">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="splitSecond" wire:model.live="splitSecond">
                                        <label class="custom-control-label font-weight-bold" for="splitSecond">
                                            Pagar con dos medios diferentes (un solo pago dividido)
                                        </label>
                                    </div>

                                    @if($splitSecond)
                                    <div class="row mt-3">
                                        <div class="col-md-6 mb-2">
                                            <label for="medio_pago2" class="form-label font-weight-bold small">Segundo medio</label>
                                            <select class="form-control form-control-sm" id="medio_pago2" wire:model.live="medioPago2">
                                                <option value="">Seleccione el medio</option>
                                                @foreach ($mediosPago as $m)
                                                    <option value="{{ $m->id }}">{{ $m->descripcion }}</option>
                                                @endforeach
                                            </select>
                                            @error('medioPago2')<span class="text-danger small">* Seleccione el segundo medio</span>@enderror
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="monto2" class="form-label font-weight-bold small">Monto segundo medio</label>
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="number" step="0.01" class="form-control" id="monto2" wire:model.live="monto2">
                                            </div>
                                            @error('monto2')<span class="text-danger small">{{ $message }}</span>@enderror
                                        </div>
                                    </div>

                                    @if (intval($medioPago2) === 5)
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label for="codeOp2" class="form-label font-weight-bold small">N° operación transferencia (2)</label>
                                            <input type="text" id="codeOp2" class="form-control form-control-sm" wire:model.live="codeOp2">
                                            @error('codeOp2')<span class="text-danger small">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    @endif

                                    <div class="mt-2 small text-muted">
                                        El resto del total se imputará automáticamente al primer medio seleccionado.
                                    </div>
                                    @endif
                                </div>
                            @endif

                            <!-- DETALLE ESPECÍFICO SEGÚN MEDIO DE PAGO -->
                            @if ($medioPago == 3)
                                <!-- CHEQUE -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="banco_select" class="form-label font-weight-bold">Banco</label>
                                        <select class="form-control" wire:model='banco' id="banco_select">
                                            <option value="">Seleccione banco</option>
                                            @foreach ($bancos as $b)
                                                <option value="{{ $b->id }}">{{ $b->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="fechaCheque" class="form-label font-weight-bold">Fecha de vencimiento</label>
                                        <input type="date" wire:model="fechaCheque" id="fechaCheque" class="form-control">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="nroCheque" class="form-label font-weight-bold">N° de cheque</label>
                                    <input type="text" class="form-control" wire:model="nroCheque" id="nroCheque">
                                </div>
                            @elseif ($medioPago == 2)
                                <!-- EFECTIVO -->
                                @if ($pagoDe === 'orden')
                                    <div class="mb-3">
                                        <label for="efectivo" class="form-label font-weight-bold">Efectivo entregado</label>
                                        <div class="row">
                                            <div class="col-md-8 mb-2">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                    <input wire:model.live="efectivo" type="number" step="0.01" id="efectivo" class="form-control" placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <button type="button" class="btn {{ $colorBoton }} btn-block font-weight-bold" wire:click='montoExacto'>
                                                    <i class="fas fa-money-bill-alt mr-1"></i> PAGO EXACTO
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @elseif ($medioPago == 1)
                                <!-- TARJETA CRÉDITO -->
                                <div class="mb-3">
                                    <label for="planSelected" class="form-label font-weight-bold">Tarjeta y Plan</label>
                                    <select wire:model.live="planSelected" wire:change='cargaInteres' id="planSelected" class="form-control">
                                        <option value="">Seleccionar tarjeta / plan</option>
                                        @foreach ($tarjetasT as $p)
                                            <option value="{{ $p->id }}">
                                                {{ $p->tarjetas->nombre_tarjeta }} - {{ $p->descripcion_plan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('planSelected')
                                        <span class="text-danger small">** Seleccione un plan</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="cupon" class="form-label font-weight-bold">N° de cupón</label>
                                    <input wire:model="cupon" type="text" id="cupon" class="form-control">
                                </div>
                            @elseif ($medioPago == 5)
                                <!-- TRANSFERENCIA -->
                                <div class="mb-3">
                                    <label for="cupon_transf" class="form-label font-weight-bold">N° de operación / comprobante</label>
                                    <input wire:model="cupon" type="text" id="cupon_transf" class="form-control">
                                </div>
                            @endif
                        @endif

                        <!-- RECUADRO DE TOTALES -->
                        <div class="card card-outline card-secondary bg-light mb-0 mt-3">
                            <div class="card-body py-2 px-3">
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted">Subtotal:</span>
                                    <strong>${{ number_format((float)($montoAPagar ?? 0), 2, '.', ',') }}</strong>
                                </div>
                                @if($discountAmount > 0)
                                    <div class="d-flex justify-content-between py-1 border-bottom text-success">
                                        <span>Descuento aplicado:</span>
                                        <strong>- ${{ number_format((float)$discountAmount, 2, '.', ',') }}</strong>
                                    </div>
                                @endif
                                @if($montoInt > 0)
                                    <div class="d-flex justify-content-between py-1 border-bottom text-info">
                                        <span>Interés tarjeta ({{ $interes }}%):</span>
                                        <strong>+ ${{ number_format((float)$montoInt, 2, '.', ',') }}</strong>
                                    </div>
                                @endif
                                @if($pagoDe === 'orden')
                                    <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkIva" wire:model.live='checkIva' wire:click='setIva'>
                                            <label class="custom-control-label" for="checkIva">IVA 21%</label>
                                        </div>
                                        <span>${{ number_format((float)$iva, 2, '.', ',') }}</span>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between align-items-center pt-2">
                                    <h5 class="mb-0 font-weight-bold text-dark">Total a abonar:</h5>
                                    <h4 class="mb-0 font-weight-bold text-success">${{ number_format((float)($total ?? $montoAPagar ?? 0), 2, '.', ',') }}</h4>
                                </div>
                                @if($medioPago == 2 && $pagoDe === 'orden' && floatval($efectivo) > 0)
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-2 text-primary">
                                        <span class="font-weight-bold">Vuelto:</span>
                                        <h5 class="mb-0 font-weight-bold">${{ number_format((float)($vuelto ?? 0), 2, '.', ',') }}</h5>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between bg-light py-2">
                        <button type="button" class="btn btn-secondary" wire:click='closeModal' wire:loading.attr="disabled" wire:target="pagar">
                            <i class="fas fa-times mr-1"></i> Cerrar
                        </button>
                        <button type="button" class="btn btn-primary font-weight-bold px-4" wire:click='pagar' wire:loading.attr="disabled" wire:target="pagar">
                            <span wire:loading.remove wire:target="pagar"><i class="fas fa-check-circle mr-1"></i> Aceptar</span>
                            <span wire:loading wire:target="pagar">
                                <i class="fas fa-spinner fa-spin mr-1"></i> Procesando...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @script
        <script>
            $wire.on('no-hay-caja', (event) => {
                Swal.fire({
                    icon: "error",
                    title: "Atención...",
                    text: "Debe abrir caja antes de crear o procesar un cobro.",
                });
            });
        </script>
    @endscript
</div>
