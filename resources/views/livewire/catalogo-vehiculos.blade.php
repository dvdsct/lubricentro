<div>
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h3 class="card-title font-weight-bold mb-2 mb-sm-0">
                    <i class="fas fa-car mr-1 text-primary"></i> Catálogo de Vehículos (Preestablecidos)
                </h3>
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-success font-weight-bold mr-2" wire:click="openModal">
                        <i class="fas fa-plus-circle mr-1"></i> Agregar Auto / Modelo
                    </button>
                    <div class="input-group" style="width: 250px;">
                        <input type="text" class="form-control" placeholder="Buscar modelo, marca..." wire:model.live.debounce.300ms="search">
                        <div class="input-group-append">
                            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible m-3">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-check-circle mr-1"></i> {{ session('message') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 80px;"># ID</th>
                            <th>TIPO</th>
                            <th>MARCA</th>
                            <th>MODELO</th>
                            <th class="text-right" style="width: 100px;">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($modelos as $m)
                            <tr>
                                <td class="font-weight-bold text-muted">#{{ $m->id }}</td>
                                <td>
                                    <span class="badge badge-info text-uppercase">
                                        {{ $m->tipos?->descripcion ?: 'General' }}
                                    </span>
                                </td>
                                <td class="font-weight-bold text-dark">
                                    {{ $m->marcas?->descripcion ?: '-' }}
                                </td>
                                <td class="font-weight-bold text-primary">
                                    {{ $m->descripcion }}
                                </td>
                                <td class="text-right">
                                    <button type="button" class="btn btn-danger btn-xs" wire:click="deleteModelo({{ $m->id }})" wire:confirm="¿Desea eliminar este modelo del catálogo?" title="Eliminar modelo">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-car fa-2x mb-2 d-block text-secondary"></i>
                                    No se encontraron modelos en el catálogo.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($modelos->hasPages())
            <div class="card-footer clearfix d-flex justify-content-end py-2">
                {{ $modelos->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL NUEVO AUTO / MODELO -->
    @if($modalNuevo)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header bg-primary text-white py-2">
                        <h5 class="modal-title font-weight-bold">
                            <i class="fas fa-plus-circle mr-1"></i> Cargar Auto / Modelo al Catálogo
                        </h5>
                        <button type="button" class="close text-white" wire:click="closeModal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small mb-3">
                            Completa los datos para registrar un vehículo preestablecido disponible en los selectores de presupuestos y turnos.
                        </p>

                        <!-- 1. TIPO -->
                        <div class="form-group mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="font-weight-bold mb-0">1. Tipo de Vehículo <span class="text-danger">*</span></label>
                                <button type="button" class="btn btn-link btn-xs p-0 text-primary font-weight-bold" wire:click="toggleNuevoTipo">
                                    {{ $crear_nuevo_tipo ? '← Elegir existente' : '+ Crear nuevo tipo' }}
                                </button>
                            </div>
                            @if($crear_nuevo_tipo)
                                <input type="text" class="form-control form-control-sm @error('nuevo_tipo_nombre') is-invalid @enderror" placeholder="Ej: CAMIÓN, MOTO, UTILITARIO..." wire:model="nuevo_tipo_nombre">
                                @error('nuevo_tipo_nombre')<span class="text-danger small">{{ $message }}</span>@enderror
                            @else
                                <select class="form-control form-control-sm @error('tipo_id') is-invalid @enderror" wire:model.live="tipo_id">
                                    <option value="">Seleccione tipo...</option>
                                    @foreach($tiposDisponibles as $t)
                                        <option value="{{ $t->id }}">{{ $t->descripcion }}</option>
                                    @endforeach
                                </select>
                                @error('tipo_id')<span class="text-danger small">{{ $message }}</span>@enderror
                            @endif
                        </div>

                        <!-- 2. MARCA -->
                        <div class="form-group mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="font-weight-bold mb-0">2. Marca <span class="text-danger">*</span></label>
                                <button type="button" class="btn btn-link btn-xs p-0 text-primary font-weight-bold" wire:click="toggleNuevaMarca">
                                    {{ $crear_nueva_marca ? '← Elegir existente' : '+ Crear nueva marca' }}
                                </button>
                            </div>
                            @if($crear_nueva_marca)
                                <input type="text" class="form-control form-control-sm @error('nueva_marca_nombre') is-invalid @enderror" placeholder="Ej: BMW, CHERY, AUDI..." wire:model="nueva_marca_nombre">
                                @error('nueva_marca_nombre')<span class="text-danger small">{{ $message }}</span>@enderror
                            @else
                                <select class="form-control form-control-sm @error('marca_id') is-invalid @enderror" wire:model="marca_id">
                                    <option value="">Seleccione marca...</option>
                                    @foreach($marcasDisponibles as $m)
                                        <option value="{{ $m->id }}">{{ $m->descripcion }}</option>
                                    @endforeach
                                </select>
                                @error('marca_id')<span class="text-danger small">{{ $message }}</span>@enderror
                            @endif
                        </div>

                        <!-- 3. MODELO -->
                        <div class="form-group mb-3">
                            <label class="font-weight-bold mb-1">3. Nombre del Modelo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm @error('nuevo_modelo_nombre') is-invalid @enderror" placeholder="Ej: Serie 3, X5, Hilux GR, 208..." wire:model="nuevo_modelo_nombre">
                            @error('nuevo_modelo_nombre')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2">
                        <button type="button" class="btn btn-secondary btn-sm" wire:click="closeModal">Cancelar</button>
                        <button type="button" class="btn btn-success btn-sm font-weight-bold" wire:click="saveModelo">
                            <i class="fas fa-save mr-1"></i> Guardar en Catálogo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
