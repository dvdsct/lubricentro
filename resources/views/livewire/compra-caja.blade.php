<div>
    @if ($modalCompra)
        <div class="modal fade show" id="modal-default" style="display: block; padding-right: 17px;" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title" id="supplierOrderModalLabel"> <strong> NUEVO PEDIDO A PROVEEDOR </strong>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        @if ($step = 1)
                            <div class="mb-3">
                                <!-- <label for="type" class="form-label">Select Type</label> -->
                                <select id="type" class="form-control" wire:model="tipoPedido">
                                    <option value="">Seleccionar categoria</option>
                                    @foreach ($tiposPago as $tp)
                                        <option value="{{ $tp->id }}">{{ $tp->descripcion }}</option>
                                    @endforeach
                                </select>
                                @error('tipoPedido')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="medioPago" class="form-label">Medio de Pago</label>
                                <select class="form-control" aria-label="Default select example"
                                    wire:model.live="medioPago" id="medio_pago">
                                    <option selected>Seleccione el medio</option>
                                    @foreach ($mediosPago as $m)
                                        <option value="{{ $m->id }}">{{ $m->descripcion }}</option>
                                    @endforeach
                                </select>
                                @error('medioPago')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- Efectivo --}}
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
                                <div class="mb-3 bg-danger p-2" style="text-align: right;">
                                    <h5 for="vuelto" class="form-label"><strong> Monto a pagar</strong></h5>
                                    <h5 for="vuelto" class="form-label"><strong> ${{ $vuelto }} </strong>
                                    </h5>
                                </div>
                            @else
                                <div class="mb-3 bg-success p-2" style="text-align: right;">
                                    <h5 for="vuelto" class="form-label"><strong> Vuelto </strong></h5>
                                    <h5 for="vuelto" class="form-label"><strong> ${{ $vuelto }} </strong>
                                    </h5>
                                </div>
                            @endif
                    </div>
    @endif
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success" form="supplierOrderForm"
            wire:click="continueForm">Continuar</button>
    </div>
</div>
</div>
</div>


@endif
</div>
