<div>
    <button type="button" class="btn btn-default" wire:click='modalOn'>
        Agregar Producto </button>
    @if ($modal == true)

    <div class="modal fade show" id="modal-default" style="display: block; padding-right: 17px;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Nuevo producto</h4>
                    <button type="button" class="close" wire:click='modalOff'>
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="m-3">

                        <div class="form-group text-center">
                            <h4>{{ $producto }}</h4>
                        </div>

                        <div class="form-group d-flex justify-content-between">
                            <label>Descripción:</label>
                            <input type="text" class="form-control col-8" wire:model='descripcion' />
                        </div>

                        <div class="form-group d-flex justify-content-between">
                            <label>Stock:</label>
                            <input type="text" class="form-control col-8" wire:model='stock' />
                        </div>

                        <div class="form-group input-group d-flex justify-content-between">
                            <div class="input-group-prepend">
                                <label>Precio:</label>
                                <span class="input-group-text">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                                <input type="text" class="form-control col-8" wire:model='costo' />
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-between">
                            <label>Codigo:</label>
                            <input type="text" class="form-control col-8" wire:model='codigo' />
                        </div>

                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" wire:click='modalOff'>Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click='saveproduct'>Guardar</button>
                </div>
                </form>
            </div>

        </div>

    </div>
    @endif
</div>