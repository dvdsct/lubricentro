<tr wire:key="pago-{{ $pago->id }}">
    <td>{{ $pago->id }}</td>
    <td>{{ $pago->created_at->translatedFormat('j \\d\\e F \\d\\e\\l Y') }}</td>
    <td>{{ $pago->concepto }}</td>
    <td>${{ $pago->total }}</td>
    <td>{{ $pago->estado }}</td>
    <td>
        <button type="button" class="btn btn-primary btn-sm" wire:click="pagar">Cobrar</button>
    </td>
</tr>

@if($modal)
    <div class="modal fade show" style="position: fixed; inset: 0; z-index: 1050; display:block; background-color: rgba(0,0,0,.5);" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered" style="z-index: 1051;">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title"><strong>Cobro Cuenta Corriente</strong></h5>
                    <button type="button" class="close" wire:click="$set('modal', false)"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Monto a cobrar</label>
                        <input type="text" class="form-control" value="{{ number_format($monto,2) }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Medio de pago</label>
                        <select class="form-control" wire:model="medioPago">
                            <option value="">Seleccionar</option>
                            <option value="2">Efectivo</option>
                            <option value="5">Transferencia</option>
                        </select>
                        @error('medioPago')<small style="color:red; font-weight:700;">{{ $message }}</small>@enderror
                    </div>
                    @if($medioPago == 5)
                        <div class="form-group">
                            <label>Código de operación</label>
                            <input type="text" class="form-control" wire:model.defer="code_op" placeholder="Código de transferencia">
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('modal', false)">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click="confirmarCobro">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
@endif
