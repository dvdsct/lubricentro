<div>
    <!-- MODAL DE PAGAR FACTURA  -->
    @if ($modal == true)

    <div class="modal fade show" id="modal-lg" style="display: block; padding-right: 17px;" aria-modal="true" role="dialog">
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


                            <div class="col-md-6">
                                <label for="tipo_pago" class="form-label">Tipo de Pago</label>
                                <select wire:model.live="tipoPago" id="tipo_pago" class="form-control">
                                    <option selected>{{ $tipoPago }}</option>
                                    @foreach ($tiposPago as $t)
                                    <option value="{{ $t->id }}">{{ $t->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($tipoPago == 2 || $tipoPago == 3)
                        </div>


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
                        <div class="mb-3">
                            <label for="montoPagado" class="form-label">Total a Pagar</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input wire:model="montoPagado" type="text" id="montoPagado" class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text">.00</span>
                                </div>
                            </div>
                        </div>
                        @endif


                        @if ($medioPago == 2)
                        {{-- Efectivo --}}
                        <div class="mb-3">
                            <label for="efectivo" class="form-label">Efectivo</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input wire:model.live="efectivo" type="text" id="efectivo" class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text">.00</span>
                                </div>
                            </div>
                        </div>
                        @if ($vuelto < 0) <div class="mb-3 bg-light" style="text-align: right; border: 1px solid grey; border-radius: 2%;">
                            <h4 for="vuelto" class="form-label"><strong> Total a pagar</strong></h4>
                            <h4 for="vuelto" class="form-label"><strong> ${{ $vuelto }} </strong></h4>
                    </div>
                    @else
                    <div class="mb-3 bg-light p-2" style="text-align: right; border: 1px solid grey; border-radius: 2%;">
                        <div class="row">
                            <div class="col-md-8">
                                <h6> Total Producto o Servicio </h6>
                                <h6> Bonificacion 10% por cliente especial </h6>
                                <h4 for="vuelto" class="form-label"><strong> Su vuelto </strong></h4>
                            </div>

                            <div class="col-md-4">
                                <h6> $4000 </h6>
                                <h6> -$400 </h6>
                                <h4 for="vuelto" class="form-label"><strong> ${{ $vuelto }} </strong></h4>
                            </div>
                        </div>
                    </div>
                    @endif

                    @endif

                    @if ($medioPago == 1)
                    {{-- Tarjeta --}}
                    <div class="mb-3">
                        <label for="tipo_pago" class="form-label">Tarjeta</label>
                        <select wire:model="tarjeta" wire:change='cargaInteres' id="tipo_pago" class="form-control">
                            <option selected>Seleccionar tarjeta</option>
                            @foreach ($tarjetas as $tar)
                            <option value="{{ $tar->id }}">
                                {{ $tar->nombre_tarjeta }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="codeOp" class="form-label">Codigo de Operacion</label>
                        <input wire:model="codeOp" type="number" id="codeOp" class="form-control">
                    </div>
                    <div class="mb-3 bg-light p-2" style="text-align: right; border: 1px solid grey; border-radius: 2%;">
                        <div class="row">
                            <div class="col-md-8">
                                <h6> Total Producto o Servicio </h6>
                                <h6> Comision de {{$tarjeta->interes}} % tarjeta Mastercard </h6>
                                <span class="badge badge-danger"> <a href="" style="color: black;"> Omitir </a>
                                    <h6 style="display: inline;"> Bonificacion -10% cliente especial </h6>
                                </span>
                                <h4 for="monto" class="form-label"><strong> Total a pagar</strong></h4>
                            </div>
                            <div class="col-md-4">
                                <h6> $4000 </h6>
                                <h6> $400</h6>
                                <span class="badge badge-danger">
                                    <h6 class="my-0"> -$400</h6>
                                </span>
                                <h4 for="monto" class="form-label"><strong> ${{ $montoAPagar }}
                                    </strong>
                                </h4>
                            </div>
                        </div>
                    </div>

                    @endif
                </div>


            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" wire:click='closeModal'>Cerrar</button>
                <button type="button" class="btn btn-primary" wire:click='pagar'>Aceptar</button>
            </div>
        </div>
    </div>
</div>
@endif
</div>