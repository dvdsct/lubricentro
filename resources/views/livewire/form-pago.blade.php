<div>

    @if ($modal == true)

        <div class="modal fade show" id="modal-lg" style="display: block; padding-right: 17px;" aria-modal="true"
            role="dialog">
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
                            <div class="mb-3">
                                <label for="tipoFactura" class="form-label">Tipo de Factura</label>
                                <select wire:model="tipoFactura" id="tipoFactura" class="form-control">
                                    <option selected>Seleccione factura</option>
                                    @foreach ($tiposFactura as $tf)
                                        <option value="{{ $tf->id }}">{{ $tf->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="mb-3">
                                <label for="tipo_pago" class="form-label">Tipo de Pago</label>
                                <select wire:model.live="tipoPago" id="tipo_pago" class="form-control">
                                    <option selected>{{ $tipoPago }}</option>
                                    @foreach ($tiposPago as $t)
                                        <option value="{{ $t->id }}">{{ $t->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($tipoPago == 2 || $tipoPago == 3)

                                <div class="mb-3">
                                    <label for="medioPago" class="form-label">Medio de Pago</label>
                                    <select class="form-control" aria-label="Default select example"
                                        wire:model.live="medioPago" id="medio_pago">
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
                                        <option selected>Open this select menu</option>
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
                                    <label for="montoPagado" class="form-label">Monto a Pagar</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input wire:model="montoPagado" type="text" id="montoPagado"
                                            class="form-control">
                                        <div class="input-group-append">
                                            <span class="input-group-text">.00</span>
                                        </div>
                                    </div>
                                </div>
                            @endif


                            @if ($medioPago == 2)
                                <div class="mb-3">
                                    <label for="efectivo" class="form-label">Efectivo</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input wire:model.live="efectivo" type="text" id="efectivo"
                                            class="form-control">
                                        <div class="input-group-append">
                                            <span class="input-group-text">.00</span>
                                        </div>
                                    </div>
                                </div>
                                @if ($vuelto < 0)
                                    <div class="mb-3 bg-danger">
                                        <label for="vuelto" class="form-label">Vuelto</label>
                                        <label for="vuelto" class="form-label">{{ $vuelto }}</label>
                                    </div>
                                @else
                                    <div class="mb-3 bg-success">
                                        <label for="vuelto" class="form-label">Vuelto</label>
                                        <label for="vuelto" class="form-label">{{ $vuelto }}</label>
                                    </div>
                                @endif

                            @endif


                            @if ($medioPago == 1)
                                <div class="mb-3">
                                    <label for="codeOp" class="form-label">Codigo de Operacion</label>
                                    <input wire:model="codeOp" type="number" id="codeOp" class="form-control">
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" wire:click='closeModal'>Cerrar</button>
                        <button type="button" class="btn btn-primary" wire:click='pagar'>Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
