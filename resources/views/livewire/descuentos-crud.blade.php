<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="m-0"><strong>Descuentos</strong></h4>
            <div class="d-flex" style="gap:8px;">
                <input type="text" class="form-control" placeholder="Buscar..." style="width:220px;" wire:model.live="search">
                <button class="btn btn-success" wire:click="openModal()"><i class="fas fa-plus"></i> Nuevo</button>
            </div>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Porcentaje</th>
                        <th>Estado</th>
                        <th style="width:160px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($descuentos as $d)
                        <tr>
                            <td>{{ $d->descripcion }}</td>
                            <td>{{ $d->porcentaje }}%</td>
                            <td>
                                @if($d->estado==='1')
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <button class="btn btn-primary btn-sm" wire:click="openModal({{ $d->id }})"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-warning btn-sm" wire:click="toggleEstado({{ $d->id }})">
                                    @if($d->estado==='1') Desactivar @else Activar @endif
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center p-3">Sin registros</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $descuentos->links() }}</div>
    </div>

    @if($modal)
        <div class="modal fade show" style="display:block; background: rgba(0,0,0,.5);" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title">{{ $descuento_id ? 'Editar Descuento' : 'Nuevo Descuento' }}</h5>
                        <button type="button" class="close" wire:click="closeModal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Descripción</label>
                            <input type="text" class="form-control" wire:model.blur="descripcion">
                            @error('descripcion')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label>Porcentaje (%)</label>
                            <input type="number" min="0" max="100" step="0.01" class="form-control" wire:model.blur="porcentaje">
                            @error('porcentaje')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label>Estado</label>
                            <select class="form-control" wire:model="estado">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button class="btn btn-default" wire:click="closeModal">Cancelar</button>
                        <button class="btn btn-primary" wire:click="save">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
