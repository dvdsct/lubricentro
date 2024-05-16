<div>
    @if ($modalCierreCaja)
    <div class="modal fade show" id="modal-lg" aria-modal="true" role="dialog" style="padding-right: 17px; display: block;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title"><strong> CERRAR CAJA </strong> </h4>
                    <button type="button" class="close" aria-label="Close" wire:click="modalCerrarCaja">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="cajero"> Cajero: </label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <!-- Icono de usuario -->
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <select class="form-control" disabled>
                            <option value="">Hugo Larcher </option>
                        </select>
                    </div>

                    <label for="cajero"> Efectivo en caja: </label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input type="text" class="form-control" wire:model.live='efectivo'>
                    </div>



                    <label for="observaciones"> Observaciones: </label>
                    <textarea class="form-control" rows="3" placeholder="Observaciones adicionales.." wire:click='observaciones'></textarea>

                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" wire:click="modalCerrarCaja">Cancelar</button>
                    <button type="button" class="btn btn-success" wire:click='dispatchTo("view-caja","cierre-caja",{efectivo:"{{$efectivo}}"})'>Aceptar</button>
                </div>
            </div>
        </div>

    </div>
    @endif


</div>