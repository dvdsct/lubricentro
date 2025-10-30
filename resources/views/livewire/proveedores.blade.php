<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0"><strong> Proveedores </strong></h5>
            <button class="btn btn-primary" wire:click="openModal">Nuevo proveedor</button>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>CUIT</th>
                        <th>Nombre Fantasía</th>
                        <th>Rubro</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($proveedores as $p)
                        <tr>
                            <td>{{ $p->id }}</td>
                            <td>{{ $p->perfiles->personas->apellido ?? '' }} {{ $p->perfiles->personas->nombre ?? '' }}</td>
                            <td>{{ $p->tipo }}</td>
                            <td>{{ $p->cuit }}</td>
                            <td>{{ $p->nombre_fantasia }}</td>
                            <td>{{ $p->rubro }}</td>
                            <td>{{ $p->telefono }}</td>
                            <td>{{ $p->email }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Sin proveedores</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-end">
            {{ $proveedores->links() }}
        </div>
    </div>

    @if ($modal)
        <div class="modal fade show" style="display:block; background-color: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title"><strong>Nuevo proveedor</strong></h5>
                        <button type="button" class="close" wire:click="closeModal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Apellido</label>
                                <input type="text" class="form-control" wire:model="apellido">
                                @error('apellido')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" wire:model="nombre">
                                @error('nombre')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="form-label">Tipo</label>
                                <select class="form-control" wire:model="tipo">
                                    <option value="">Seleccionar</option>
                                    <option value="juridica">Jurídica</option>
                                    <option value="fisica">Física</option>
                                </select>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="form-label">CUIT</label>
                                <input type="text" class="form-control" wire:model="cuit">
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="form-label">Nombre Fantasía</label>
                                <input type="text" class="form-control" wire:model="nombre_fantasia">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Dirección</label>
                                <input type="text" class="form-control" wire:model="direccion">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Rubro</label>
                                <input type="text" class="form-control" wire:model="rubro">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" class="form-control" wire:model="telefono">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" wire:model="email">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="closeModal">Cancelar</button>
                        <button class="btn btn-primary" wire:click="save">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
