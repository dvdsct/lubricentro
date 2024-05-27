<div>
    <!-- MODAL DE PAGAR FACTURA  -->
    @if ($modal == true)

    <div class="modal fade show" id="modal-lg" style="display: block; background-color: rgba(0, 0, 0, 0.5);" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title"> <strong> PAGAR FACTURA </strong> </h4>
                    <button type="button" class="close" wire:click='closeModal'>
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="tipoFactura" class="form-label">Tipo de Factura</label>
                                <select wire:model="tipoFactura" id="tipoFactura" class="form-control">
                                    <option selected>Seleccione factura</option>
                                    @foreach ($tiposFactura as $tf)
                                    <option value="{{ $tf->id }}">{{ $tf->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-md-6 mb-3">
                                <label for="tipo_pago" class="form-label">Tipo de Pago</label>
                                <select wire:model.live="tipoPago" id="tipo_pago" class="form-control">
                                    @foreach ($tiposPago as $t)
                                    <option value="{{ $t->id }}">{{ $t->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($tipoPago == 2 || $tipoPago == 3)
                        </div>

                        <!-- MEDIO DE PAGO  -->
                        <div class="mb-3">
                            <label for="medioPago" class="form-label">Medio de Pago</label>
                            <select class="form-control" aria-label="Default select example" wire:model.live="medioPago" id="medio_pago">
                                <option selected>Seleccione el medio</option>
                                @foreach ($mediosPago as $m)
                                <option value="{{ $m->id }}">{{ $m->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif


                        @if ($tipoPago == 1)
                        <div class="mb-3">
                            <label for="tipo_pago" class="form-label">Cliente</label>
                            <select wire:model.live="cliente" id="tipo_pago" class="form-control">
                                <option selected>Seleccionar cliente</option>
                                @foreach ($clientes as $c)
                                <option value="{{ $c->id }}">
                                    {{ $c->perfiles->personas->nombre . ' ' . $c->perfiles->personas->apellido . ' ' . $c->perfiles->personas->dni }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endif


                        @if ($tipoPago == 3)
                        <!-- TIPO DE PAGO PREVENTA -->
                        <div class="mb-3">
                            <label for="montoPagado" class="form-label">Total a Pagar</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input wire:model="montoPagado" type="text" id="montoPagado" class="form-control">
                            </div>
                        </div>
                        @endif

                        {{-- _________________________________________________________________________________________________________________________________________________________________ --}}
                        {{-- _________________________________________________________________________________________________________________________________________________________________ --}}
                        {{-- _________________________________________________________________________________________________________________________________________________________________ --}}
                        <!-- MEDIO DE PAGO EN CHEQUE -->
                        @if ($medioPago == 3)
                        <div class="row">
                            <div class="col-md-6">
                                <label for="tipo_pago" class="form-label">Banco</label>
                                <select class="form-control" wire:model='banco'>
                                    @foreach ($bancos as $b)
                                    <option value="{{ $b->id }}">{{ $b->descripcion }}</option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="vencimiento" class="form-label">Fecha de vencimiento</label>
                                <input type="date" wire:model="fechaCheque" class="form-control">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="Numero_cheque">N° de cheque </label>
                                <input type="number" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3 bg-light p-2" style="text-align: right; border: 1px solid grey; border-radius: 2%;">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6> Total Producto o Servicio </h6>
                                    <h4 for="monto" class="form-label"><strong> Total a pagar</strong></h4>
                                </div>
                                <div class="col-md-4">
                                    <h6>{{ $montoAPagar }}</h6>
                                    <h4 for="monto" class="form-label"><strong> ${{ $montoAPagar }}
                                        </strong>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        @endif





                        {{-- _________________________________________________________________________________________________________________________________________________________________ --}}
                        {{-- _________________________________________________________________________________________________________________________________________________________________ --}}
                        {{-- _________________________________________________________________________________________________________________________________________________________________ --}}


                        @if ($medioPago == 2)
                        <!-- MEDIO DE PAGO EN EFECTIVO  -->
                        <div class="mb-3">
                            <label for="efectivo" class="form-label">Efectivo</label>
                            <div class="row">
                                <div class="input-group col-md-8">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input wire:model.live="efectivo" type="text" id="efectivo" class="form-control">
                                </div>

                                <div class="col-md-4">
                                <button type="button" class="btn {{ $colorBoton }} float-right" wire:click='montoExacto'><i class="fas fa-money-bill-alt"></i> PAGO EXACTO </button>
                                </div>

                            </div>
                        </div>
                        <div class="mb-3 bg-light P-2" style="text-align: right; border: 1px solid grey; border-radius: 2%;">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="mt-2"> Total Producto o Servicio </h6>
                                    <h4 for="monto" class="form-label"><strong> Total a pagar</strong></h4>
                                    <h4 for="vuelto" class="form-label"><strong> Su vuelto</strong></h4>
                                </div>

                                <div class="col-md-4">
                                    <h6 class="pr-2 mt-2">{{ $montoAPagar }}</h6>
                                    <h4 for="monto" class="form-label pr-2"><strong> ${{ $montoAPagar }}</strong></h4>
                                    <h4 for="vuelto" class="form-label pr-2"><strong> ${{ $vuelto }} </strong></h4>
                                </div>

                            </div>
                        </div>
                        @endif



                        {{-- _________________________________________________________________________________________________________________________________________________________________ --}}
                        {{-- _________________________________________________________________________________________________________________________________________________________________ --}}

                        @if ($medioPago == 1)
                        <!-- MEDIO DE PAGO CON TARJETA -->
                        <div class="mb-3">
                            <label for="tipo_pago" class="form-label">Tarjeta</label>
                            <select wire:model="planSelected" wire:change='cargaInteres' id="tipo_pago" class="form-control">
                                <option value="">Seleccionar tarjeta</option>
                                @foreach ($tarjetasT as $p)
                                <option value="{{ $p->id }}">
                                    {{ $p->tarjetas->nombre_tarjeta }} -
                                    {{ $p->descripcion_plan }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="codeOp" class="form-label">N° de cupon</label>
                            <input wire:model="codeOp" type="number" wire:model='cupon' id="codeOp" class="form-control">
                        </div>

                        <!-- RECUADRO DE TOTAL TARJETA -->
                        <div class="mb-3 bg-light p-2" style="text-align: right; border: 1px solid grey; border-radius: 2%;">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6> Total Producto o Servicio </h6>
                                    <h6> Comision de {{ $interes }}% tarjeta Mastercard </h6>
                                    <h4 for="monto" class="form-label"><strong> Total a pagar</strong></h4>
                                </div>
                                <div class="col-md-4">
                                    <h6>{{ $montoAPagar }}</h6>
                                    <h6>$ {{ $montoInt }}</h6>
                                    <h4 for="monto" class="form-label"><strong> ${{ $montoAPagarInteres }}
                                        </strong>
                                    </h4>
                                </div>
                            </div>
                        </div>

                        @endif


                        @if ($medioPago == 5)
                        <!-- MEDIO DE PAGO CON TRANSFERENCIA -->


                        <div class="mb-3">
                            <label for="codeOp" class="form-label">N° de cupon</label>
                            <input wire:model="codeOp" type="number" id="codeOp" wire:model='cupon' class="form-control">
                        </div>

                        <!-- RECUADRO DE TOTAL TARJETA -->
                        <div class="mb-3 bg-light P-2" style="text-align: right; border: 1px solid grey; border-radius: 2%;">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6> Total Producto o Servicio </h6>
                                    <h4 for="monto" class="form-label"><strong> Total a pagar</strong></h4>
                                </div>

                                <div class="col-md-4">
                                    <h6>{{ $montoAPagar }}</h6>
                                    <h4 for="monto" class="form-label"><strong> ${{ $montoAPagar }}</strong></h4>
                                    <h4 for="vuelto" class="form-label"><strong> ${{ $vuelto }} </strong></h4>
                                </div>

                            </div>
                        </div>
                        @endif

                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" wire:click='closeModal'>Cerrar</button>
                        <button type="button" class="btn btn-primary" wire:click='pagar'>Aceptar</button>
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
                    text: "Debe Abrir caja antes de crear una orden",
                });
            });
        </script>
        @endscript
    </div>
