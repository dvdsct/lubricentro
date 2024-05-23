<div>

    @if ($modalProductos == true)

    <!-- MODAL PARA CARGAR UN NUEVO PRODUCTO -->
    <div class="modal fade show" id="modal-default" style="display: block; padding-right: 17px;background-color:rgba(0, 0, 0, 0.5)" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title"> <strong> NUEVO PRODUCTO </strong> </h4>
                    <button type="button" class="close" wire:click='modalProductosOn'>
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="m-3">
                        <div class="form-group text-center">
                            <h4>{{ $producto }}</h4>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" class="form-control" wire:model='descripcion' placeholder="Producto" />
                            </div>

                            <div class="col-md-4">
                                <input type="text" class="form-control" wire:model='codigo' placeholder="Codigo" />
                            </div>
                        </div>

                        <div class="row pt-4">
                            <div class="col-md-6">
                                <input type="number" class="form-control" wire:model='stock' placeholder="Stock inicial" />
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="text" class="form-control" wire:model='costo' placeholder="Precio de costo">
                                    <div class="input-group-append">
                                        <span class="input-group-text">.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                        <div class="col-md-4">
                                <input type="text" class="form-control" wire:model='cod_barra' placeholder="Codigo de barras" />
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" wire:click='modalProductosOn'>Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click='saveproduct'>Guardar</button>
                </div>
                </form>
            </div>

        </div>

    </div>
    @endif
</div>
