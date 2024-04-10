<div>
    @if ($modal == true)



    <div class="modal fade show" id="modal-default" style="display: block; padding-right: 17px;" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="supplierOrderModalLabel"> <strong> NUEVO PEDIDO A PROVEEDOR </strong></h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                </div>
                <div class="modal-body">


                    <form wire:submit.prevent="continueForm">
                        <div class="mb-3">
                          <!--   <label for="provider" class="form-label">Select Provider</label> -->
                            <select id="provider" class="form-control" wire:model="proveedor">
                                <option value="">Seleccionar proveedor</option>
                                @foreach($proveedores as $p)
                                 <option value="{{ $p->id }}"> {{ $p->perfiles->personas->nombre }}</option>
                                @endforeach
                            </select>
                            @error('proveedor')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <!-- <label for="date_ingreso" class="form-label">Date of Ingress</label> -->
                            <input type="date" class="form-control" id="date_ingreso" wire:model="fechaIn">
                            @error('fechaIn')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <!-- <label for="type" class="form-label">Select Type</label> -->
                            <select id="type" class="form-control" wire:model="tipoPedido">
                                <option value="">Seleccionar categoria</option>
                           @foreach($tiposPedidos as $tp)
                           <option value="{{$tp->id}}">{{$tp->descripcion}}</option>
                           @endforeach
                            </select>
                            @error('tipoPedido')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success" form="supplierOrderForm" wire:click="continueForm">Continuar</button>
                </div>
            </div>
        </div>
    </div>

    @endif
</div>