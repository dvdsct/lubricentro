<div>
    @if ($modal == true)

<!-- MODAL PARA REALIZAR UN NUEVO PRESUPUESTO -->

        <div class="modal fade show" id="modal-default" style="display: block; padding-right: 17px;" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title" id="supplierOrderModalLabel"> <strong> LISTADO DE PROVEEDORES </strong>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">


                        <div class="mb-3">
                            <!--   <label for="provider" class="form-label">Select Provider</label> -->
                            <select id="provider" class="form-control" wire:model="cliente">
                                <option value="">Seleccione uno</option>
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
