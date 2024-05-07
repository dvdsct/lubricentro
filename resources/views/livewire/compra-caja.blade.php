<div>

<!-- MODAL PARA GENERAR COMPRA - EXTRACCION DE CAJA  -->

    @if ($modalCompra)
        <div class="modal fade show" id="modal-default" style="display: block; padding-right: 17px;" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title" id="supplierOrderModalLabel"> <strong> GENERAR COMPRA - Extraccion de caja </strong>
                        </h5>
                        <button type="button" class="close" wire:click='$dispatch("modal-compra")'>
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">


                        {{-- MEdio de pago --}}
                        <div class="mb-3">
                            <label for="medioPago" class="form-label">Medio de Pago</label>
                            <select class="form-control" aria-label="Default select example" wire:model.live="medioPago"
                                id="medio_pago">
                                <option selected>Seleccione el medio</option>
                                @foreach ($mediosPago as $m)
                                    <option value="{{ $m->id }}">{{ $m->descripcion }}</option>
                                @endforeach
                            </select>
                            @error('medioPago')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- End medio de pago --}}


                        {{-- Concepto --}}
                        <div class="mb-3">
                            <label for="concepto" class="form-label">Concepto</label>

                                <input wire:model="concepto" type="text" id="concepto" class="form-control">

                            </div>
                        </div>

                        {{-- Monto --}}
                        <div class="mb-3">
                            <label for="efectivo" class="form-label">Efectivo</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input wire:model="montoAPagar" type="text" id="montoAPagar" class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text">.00</span>
                                </div>
                            </div>
                        </div>



                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" wire:click='$dispatch("modal-compra")'>Cancelar</button>
                        <button type="submit" class="btn btn-success" form="supplierOrderForm"
                            wire:click="pagar">Continuar</button>
                    </div>
                </div>
            </div>
        </div>


    @endif
</div>
