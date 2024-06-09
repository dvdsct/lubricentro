<div>

    <!-- MODAL PARA GENERAR COMPRA - EXTRACCION DE CAJA  -->

    @if ($modalCompra)
    <div class="modal fade show" id="modal-default" style="display: block; background-color: rgba(0, 0, 0, 0.5);" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="supplierOrderModalLabel"> <strong> EXTRACCION/INGRESO DE CAJA </strong> </h5>
                    <button type="button" class="close" wire:click='$dispatch("modal-compra")'>
                        <span aria-hidden="true">×</span>
                    </button>
                </div>


                    <div class="modal-body">

                        {{-- Medio de pago --}}
                        <div class="mb-3">
                            <div class=" mb-3">
                                <button  @if($in) class="btn btn-success"  @else class="btn btn-secondary" @endif wire:click='setMov("in")'>
                                    <span class="info-box-icon ">
                                        <i class="fas fa-caret-up"></i>
                                        ENTRADA
                                    </span>
                                </button>

                                <button @if($out) class="btn btn-danger"  @else class="btn btn-secondary" @endif wire:click='setMov("out")'>
                                    <span class="info-box-icon ">
                                        <i class="fas fa-caret-down"></i>
                                    SALIDA </span>
                                </button>
                            </div>

                        </div>
                        {{-- End medio de pago --}}

                        {{-- Medio de pago --}}
                        <div class="mb-3">
                            <label for="medioPago" class="form-label">Medio de Pago</label>
                            <select class="form-control"  aria-label="Default select example"
                                wire:model.live="medioPago" id="medio_pago">
                                <option value="2">Efectivo</option>
                            </select>
                            @error('medioPago')
                                <strong> <span class="text-danger">{{ $message }}</span> </strong>
                            @enderror
                        </div>
                        {{-- End medio de pago --}}


                        {{-- Concepto --}}
                        <div class="mb-3">
                            <label for="concepto" class="form-label">Concepto</label>
                            <input wire:model="concepto" type="text" id="concepto" class="form-control">
                            @error('concepto')
                                <strong> <span class="text-danger">{{ $message }}</span> </strong>
                            @enderror
                        </div>


                        {{-- Monto --}}
                        <div class="mb-3">
                            <label for="efectivo" class="form-label">Monto</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input wire:model="montoAPagar" type="text" id="montoAPagar" class="form-control">
                            </div>
                            @error('montoAPagar')
                                <strong> <span class="text-danger">{{ $message }}</span> </strong>
                            @enderror
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger"
                            wire:click='$dispatch("modal-compra")'>Cancelar</button>
                        <button type="submit" class="btn btn-success" form="supplierOrderForm"
                            wire:click="pagar">Continuar</button>
                    </div>
                </div>
            </div>
        </div>
</div>
@endif
</div>
