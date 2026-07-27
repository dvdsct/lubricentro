<div>
    <div class="row">
        <div class="col-12">
            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left mr-1"></i> Volver a Clientes
            </a>
        </div>
        
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center mb-3">
                        <i class="fas fa-user-circle fa-5x text-muted"></i>
                    </div>
                    <h3 class="profile-username text-center">
                        <strong>
                            {{ optional(optional($cliente->perfiles)->personas)->nombre }}
                            {{ optional(optional($cliente->perfiles)->personas)->apellido }}
                        </strong>
                    </h3>
                    <p class="text-muted text-center">Cliente registrado</p>
                    
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>DNI</b> <a class="float-right text-dark">{{ optional(optional($cliente->perfiles)->personas)->DNI ?? '-' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Teléfono</b> <a class="float-right text-dark">{{ optional(optional($cliente->perfiles)->personas)->numero_telefono ?? 'No registrado' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Fecha de Nacimiento</b> 
                            <a class="float-right text-dark">
                                {{ optional(optional($cliente->perfiles)->personas)->fecha_nac ? \Carbon\Carbon::parse($cliente->perfiles->personas->fecha_nac)->format('d/m/Y') : 'No registrada' }}
                            </a>
                        </li>
                        <li class="list-group-item">
                            <b>Acceso Web</b>
                            <span class="float-right">
                                @if (optional(optional($cliente->perfiles)->users)->email)
                                    <span class="badge bg-success" title="{{ optional($cliente->perfiles->users)->email }}">
                                        <i class="fas fa-check-circle mr-1"></i> {{ optional($cliente->perfiles->users)->email }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Sin acceso</span>
                                @endif
                            </span>
                        </li>
                    </ul>

                    <button wire:click="openEditModal" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-user-edit mr-1"></i> Modificar Datos
                    </button>

                    <button wire:click="openUserModal" class="btn btn-outline-info btn-block">
                        <i class="fas fa-key mr-1"></i> {{ optional(optional($cliente->perfiles)->users)->email ? 'Gestionar Acceso Web' : 'Crear Acceso Web' }}
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title m-0"><strong>Vehículos Registrados</strong></h4>
                </div>
                <div class="card-body p-0">
                    @if ($cliente->vehiculos->count())
                        <div class="table-responsive">
                            <table class="table table-hover table-striped m-0">
                                <thead>
                                    <tr>
                                        <th>Vehículo</th>
                                        <th>Año</th>
                                        <th>Patente (Dominio)</th>
                                        <th class="text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cliente->vehiculos as $v)
                                        <tr>
                                            <td class="align-middle">
                                                <strong>{{ optional(optional($v->modelos)->marcas)->descripcion ?? '' }}</strong>
                                                {{ optional($v->modelos)->descripcion ?? '' }}
                                            </td>
                                            <td class="align-middle">{{ $v->año ?? '-' }}</td>
                                            <td class="align-middle">
                                                <span class="badge bg-orange text-white" style="font-size: 0.9rem; padding: 5px 10px;">
                                                    {{ $v->dominio }}
                                                </span>
                                            </td>
                                            <td class="text-right align-middle">
                                                <a href="{{ route('vehiculos.perfil', $v->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-history mr-1"></i> Historial y Detalles
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-car-crash fa-3x mb-3 text-gray-300"></i>
                            <p class="m-0">No se encontraron vehículos registrados para este cliente.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL MODIFICAR DATOS CLIENTE -->
    @if ($showEditModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0, 0, 0, 0.5);" wire:keydown.escape="closeEditModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-user-edit mr-2"></i>Modificar Datos del Cliente
                        </h5>
                        <button type="button" class="close text-white" wire:click="closeEditModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form wire:submit.prevent="updateClient">
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="nombre" class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" wire:model="nombre" placeholder="Ingrese nombre">
                                @error('nombre')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="apellido" class="font-weight-bold">Apellido <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('apellido') is-invalid @enderror" id="apellido" wire:model="apellido" placeholder="Ingrese apellido">
                                @error('apellido')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="dni" class="font-weight-bold">DNI</label>
                                <input type="text" class="form-control @error('dni') is-invalid @enderror" id="dni" wire:model="dni" placeholder="Ingrese DNI">
                                @error('dni')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="numero_telefono" class="font-weight-bold">Teléfono</label>
                                <input type="text" class="form-control @error('numero_telefono') is-invalid @enderror" id="numero_telefono" wire:model="numero_telefono" placeholder="Ingrese teléfono">
                                @error('numero_telefono')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="fecha_nac" class="font-weight-bold">Fecha de Nacimiento</label>
                                <input type="date" class="form-control @error('fecha_nac') is-invalid @enderror" id="fecha_nac" wire:model="fecha_nac">
                                @error('fecha_nac')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-secondary" wire:click="closeEditModal">
                                <i class="fas fa-times mr-1"></i> Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL CREAR/GESTIONAR ACCESO WEB CLIENTE -->
    @if ($showUserModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0, 0, 0, 0.5);" wire:keydown.escape="closeUserModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-key mr-2"></i>Acceso Web del Cliente
                        </h5>
                        <button type="button" class="close text-white" wire:click="closeUserModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form wire:submit.prevent="createWebAccess">
                        <div class="modal-body">
                            <p class="text-muted small">
                                Genere o actualice las credenciales con las cuales el cliente podrá iniciar sesión en el portal web para consultar sus turnos, vehículos e historial.
                            </p>

                            <div class="form-group mb-3">
                                <label for="userEmail" class="font-weight-bold">Correo Electrónico (Login) <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('userEmail') is-invalid @enderror" id="userEmail" wire:model="userEmail" placeholder="cliente@correo.com">
                                @error('userEmail')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="userPassword" class="font-weight-bold">
                                    Contraseña {{ optional(optional($cliente->perfiles)->users)->id ? '(dejar en blanco para conservar actual)' : '*' }}
                                </label>
                                <input type="password" class="form-control @error('userPassword') is-invalid @enderror" id="userPassword" wire:model="userPassword" placeholder="******">
                                @error('userPassword')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-secondary" wire:click="closeUserModal">
                                <i class="fas fa-times mr-1"></i> Cancelar
                            </button>
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-save mr-1"></i> Guardar Acceso Web
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
