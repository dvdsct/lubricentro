<div>
    @if ($modal == true)



        <div class="modal fade show" id="modal-default" style="display: block; padding-right: 17px;" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title" id="supplierOrderModalLabel"> <strong> NUEVO PEDIDO A PROVEEDOR </strong>
                        </h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">


                        <div class="mb-3">
                            <!--   <label for="provider" class="form-label">Select Provider</label> -->
                            <select id="provider" class="form-control" wire:model="cliente">
                                <option value="">Seleccionar proveedor</option>
                                @foreach ($clientes as $c)
                                    <option value="{{ $c->id }}"> {{ $c->perfiles->personas->nombre }}</option>
                                @endforeach
                            </select>
                            @error('proveedor')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" wire:click='modalOnOff'>Cancelar</button>
                        <button type="submit" class="btn btn-success" form="supplierOrderForm"
                            wire:click="continueForm">Continuar</button>
                    </div>
                </div>
            </div>
        </div>

    @endif
    @if ($presupuesto)
        hay
    @endif
</div>
