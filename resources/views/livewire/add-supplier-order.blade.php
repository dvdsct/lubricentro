<div>
    @if ($modal == true)



    <div class="modal fade show" id="modal-default" style="display: block; padding-right: 17px;" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="supplierOrderModalLabel">Add Supplier Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <form wire:submit.prevent="continueForm">
                        <div class="mb-3">
                            <label for="provider" class="form-label">Select Provider</label>
                            <select id="provider" class="form-select" wire:model="proveedor">
                                <option value="">Select a provider</option>
                                @foreach($proveedores as $p)
                                 <option value="{{ $p->id }}"> {{ $p->perfiles->personas->nombre }}</option>
                                @endforeach
                            </select>
                            @error('proveedor')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="date_ingreso" class="form-label">Date of Ingress</label>
                            <input type="date" class="form-control" id="date_ingreso" wire:model="fecha_in">
                            @error('fecha_in')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Select Type</label>
                            <select id="type" class="form-select" wire:model="selectedType">
                                <option value="">Select a type</option>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="supplierOrderForm" wire:click="continueForm">Continue</button>
                </div>
            </div>
        </div>
    </div>

    @endif
</div>