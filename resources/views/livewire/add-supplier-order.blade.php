<div>
    @if ($modal == true)
    <div class="modal fade show" id="modal-default" style="display: block; background-color:rgba(0, 0, 0, 0.5)" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fas fa-truck mr-2"></i> NUEVA ORDEN DE COMPRA (FASE 1)
                    </h5>
                    <button type="button" class="close text-white" wire:click='modalOff'>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="supplierOrderForm" wire:submit.prevent="continueForm">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold small">Proveedor (*):</label>
                            <select id="provider" class="form-control @error('proveedor') is-invalid @enderror" wire:model="proveedor">
                                <option value="">-- Seleccionar proveedor --</option>
                                @foreach($proveedores as $p)
                                    <option value="{{ $p->id }}">{{ $p->nombre_completo }} {{ $p->cuit ? '(' . $p->cuit . ')' : '' }}</option>
                                @endforeach
                            </select>
                            @error('proveedor') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class="font-weight-bold small">Fecha de la Orden (*):</label>
                                <input type="date" class="form-control @error('fechaIn') is-invalid @enderror" id="date_ingreso" wire:model="fechaIn">
                                @error('fechaIn') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label class="font-weight-bold small">Categoría / Tipo de Pedido (*):</label>
                                <select id="type" class="form-control @error('tipoPedido') is-invalid @enderror" wire:model="tipoPedido">
                                    <option value="">-- Seleccionar categoría --</option>
                                    @foreach($tiposPedidos as $tp)
                                        <option value="{{ $tp->id }}">{{ $tp->descripcion }}</option>
                                    @endforeach
                                </select>
                                @error('tipoPedido') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold small">Condiciones Comerciales / Pago:</label>
                            <input type="text" class="form-control" wire:model="condiciones_pago" placeholder="Ej: Contado, 30 días, Cheque a 60 días...">
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold small">Observaciones adicionales:</label>
                            <textarea class="form-control" rows="2" wire:model="observaciones" placeholder="Opcional..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" wire:click="modalOff">Cancelar</button>
                    <button type="submit" class="btn btn-success font-weight-bold" form="supplierOrderForm">
                        <i class="fas fa-arrow-right mr-1"></i> Crear y Cargar Productos
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
