<div>
    @if ($modal == true)
    <div class="modal fade show" id="modal-default" style="display: block; padding-right: 17px;background-color:rgba(0, 0, 0, 0.5)" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="supplierOrderModalLabel"> <strong> NUEVO PEDIDO A PROVEEDOR </strong></h5>
                    <button type="button" class="close"  wire:click='modalOff'>
                            <span aria-hidden="true">×</span>
                        </button>
                </div>
                <div class="modal-body">


                    <form id="supplierOrderForm" wire:submit.prevent="continueForm">
                        <div class="mb-3">
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
                            <input type="date" class="form-control" id="date_ingreso" wire:model="fechaIn">
                            @error('fechaIn')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
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
                    <button type="button" class="btn btn-danger" wire:click="modalOff">Cancelar</button>
                    <button type="submit" class="btn btn.success" form="supplierOrderForm">Continuar</button>
                </div>
            </div>
        </div>
    </div>

    @endif
</div>
