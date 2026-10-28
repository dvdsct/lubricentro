<div>
    @if ($modal == true)

    <!-- MODAL PARA REALIZAR UN NUEVO PRESUPUESTO -->

    <div class="modal fade show" id="modal-default" style="display: block; background-color: rgba(0, 0, 0, 0.5);" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-info" style="position: sticky; top: 0; z-index: 2;">
                    <h5 class="modal-title" id="supplierOrderModalLabel"> <strong> GENERAR NUEVO PRESUPUESTO </strong>
                    </h5>
                    <button type="button" class="close" wire:click='modalOnOff'>
                        <span aria-hidden="true">×</span>
                    </button>

                </div>
                <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
                    @if ($formperson == false)
                    <div class="pt-2 row d-flex justify-content-between">
                        <h4 class="pl-2"> <strong> CLIENTE </strong> </h4>
                    </div>
                    @if ($cliente == null)
                    <div class="row">
                        <div class="col-10 col-xs-10">
                            <button type="button" class="btn btn-outline-secondary btn-block text-left d-flex align-items-center justify-content-between"
                                    wire:click="toggleClientModal"
                                    style="height: 38px; border: 1px solid #ced4da; background: #fff; border-radius: .25rem;">
                                <span style="color: #6c757d;">
                                    <i class="fas fa-search mr-2"></i> Buscar y seleccionar cliente...
                                </span>
                                <i class="fas fa-chevron-down" style="color: #6c757d;"></i>
                            </button>
                        </div>
                        <!-- BOTON CREAR NUEVO CLIENTE  -->
                        <div class="col-2 col-xs-2">
                            <button class="btn btn-success" wire:click='formPerson'>
                                <div class="icon">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                            </button>
                        </div>
                    </div>

                    {{-- MODAL BUSCAR CLIENTE --}}
                    @if ($showClientModal)
                    <div class="modal fade show" style="display: block; background-color: rgba(0, 0, 0, 0.6); z-index: 1060;" role="dialog">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" style="z-index: 1070;">
                            <div class="modal-content" style="border-radius: .5rem; overflow: hidden;">
                                <div class="modal-header" style="background: linear-gradient(135deg, #1abc9c, #16a085); border: none;">
                                    <h5 class="modal-title text-white"><i class="fas fa-users mr-2"></i> <strong>SELECCIONAR CLIENTE</strong></h5>
                                    <button type="button" class="close text-white" wire:click="toggleClientModal" style="opacity: 1;">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body p-0">
                                    {{-- BUSCADOR --}}
                                    <div class="p-3" style="background: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="background: #fff; border-right: none;">
                                                    <i class="fas fa-search text-muted"></i>
                                                </span>
                                            </div>
                                            <input type="text"
                                                   class="form-control"
                                                   wire:model.live.debounce.300ms="searchCliente"
                                                   placeholder="Buscar por nombre, apellido, DNI o teléfono..."
                                                   autofocus
                                                   style="border-left: none;">
                                            @if ($searchCliente)
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" wire:click="$set('searchCliente', '')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- LISTA DE CLIENTES --}}
                                    <div style="max-height: 400px; overflow-y: auto;">
                                        <table class="table table-hover table-sm mb-0">
                                            <thead style="position: sticky; top: 0; background: #fff; z-index: 1;">
                                                <tr>
                                                    <th style="width: 50px;" class="text-center">#</th>
                                                    <th>Nombre</th>
                                                    <th>Apellido</th>
                                                    <th>DNI</th>
                                                    <th>Teléfono</th>
                                                    <th style="width: 80px;" class="text-center">Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($this->filteredClientes->items() as $c)
                                                <tr wire:click="selectCliente({{ $c->id }})"
                                                    style="cursor: pointer;"
                                                    class="client-search-row">
                                                    <td class="text-center text-muted">{{ $c->id }}</td>
                                                    <td><strong>{{ $c->perfiles->personas->nombre ?? '-' }}</strong></td>
                                                    <td>{{ $c->perfiles->personas->apellido ?? '-' }}</td>
                                                    <td>{{ $c->perfiles->personas->DNI ?? '-' }}</td>
                                                    <td>{{ $c->perfiles->personas->numero_telefono ?? '-' }}</td>
                                                    <td class="text-center">
                                                        <button class="btn btn-sm btn-info" wire:click.stop="selectCliente({{ $c->id }})">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4 text-muted">
                                                        <i class="fas fa-user-slash fa-2x mb-2 d-block"></i>
                                                        No se encontraron clientes
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- PAGINACIÓN --}}
                                    @if ($this->filteredClientes->lastPage() > 1)
                                    <div class="d-flex justify-content-between align-items-center px-3 py-2" style="background: #f8f9fa; border-top: 1px solid #dee2e6;">
                                        <button class="btn btn-sm btn-outline-secondary"
                                                wire:click="previousClientPage"
                                                @if ($clientPage <= 1) disabled @endif>
                                            <i class="fas fa-chevron-left mr-1"></i> Anterior
                                        </button>
                                        <small class="text-muted">
                                            Página <strong>{{ $clientPage }}</strong> de <strong>{{ $this->filteredClientes->lastPage() }}</strong>
                                            <span class="ml-2">({{ $this->filteredClientes->total() }} clientes)</span>
                                        </small>
                                        <button class="btn btn-sm btn-outline-secondary"
                                                wire:click="nextClientPage"
                                                @if ($clientPage >= $this->filteredClientes->lastPage()) disabled @endif>
                                            Siguiente <i class="fas fa-chevron-right ml-1"></i>
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @else
                    <!-- MUESTRA NOMBRE APELLIDO Y DNI DEL CLIENTE SELECCIONADO -->
                    <div class="px-3 row">
                        <h2>
                            <span class="float-right font-italic badge bg-secondary">
                                {{ $nombre }} {{ $apellido }}
                                @if($dni)
                                    - DNI: {{ $dni }}
                                @elseif($numero_telefono)
                                    - Tel: {{ $numero_telefono }}
                                @endif
                            </span>
                        </h2>

                        <!-- BOTON ELIMINAR CLIENTE SELECCIONADO -->
                        <div class="pl-2 col-1">
                            <button class="btn btn-danger" wire:click='formPerson'>
                                <div class="icon">
                                    <i class="fas fa-user-minus"></i>
                                </div>
                            </button>
                        </div>
                    </div>

                    @endif
                    @endif

                    @if ($formperson == false && $cliente != null)
                    <!-- SECCION VEHICULO (selección/alta) -->
                    <div class="pt-3 row d-flex justify-content-between align-items-center">
                        <div class="col-md-4">
                            <h4 class="mb-0"> <strong> VEHÍCULO </strong> </h4>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <select class="form-control" wire:model="vehiculo" wire:change="selectVehiculo">
                                    <option value="">Seleccionar vehículo existente</option>
                                    @foreach($vehiculos as $v)
                                        <option value="{{ $v->id }}">
                                            {{ optional(optional($v->modelos)->marcas)->descripcion ?? '-' }}
                                            {{ $v->modelos->descripcion ?? '' }}
                                            @if($v->dominio) - {{ $v->dominio }} @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 text-right">
                            <button class="btn btn-sm btn-success" wire:click="setFormVehiculo" title="Vehículo">
                                <i class="fas fa-car"></i>
                            </button>
                        </div>
                    </div>

                    @if($selecedtVehiculo)
                        <div class="px-3 pt-2">
                            <span class="badge bg-success">
                                Vehículo seleccionado
                            </span>
                        </div>
                    @endif

                    @if ($formVehiculo)
                    <div class="px-3 pt-2">
                        <span class="text-muted small font-weight-bold"><i class="fas fa-car mr-1"></i> Datos del vehículo:</span>
                    </div>
                    <div class="px-3 row">
                        <div class="col-md-4">
                            <div class="form-group mb-2">
                                <label class="col-form-label py-0 mb-1 d-flex justify-content-between align-items-center">
                                    <span>Tipo de vehículo</span>
                                    <button type="button" class="btn btn-link btn-xs p-0 text-primary font-weight-bold" wire:click="openModalNuevoCatalogo('tipo')" title="Agregar nuevo tipo">
                                        <i class="fas fa-plus"></i> Nuevo
                                    </button>
                                </label>
                                <div class="input-group">
                                    <select class="form-control" wire:model="tipo" wire:change="upMarcas">
                                        <option value="">Seleccionar</option>
                                        @foreach($tiposVehiculo as $t)
                                            <option value="{{ $t->id }}">{{ $t->descripcion }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" wire:click="openModalNuevoCatalogo('tipo')" title="Agregar nuevo tipo">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-2">
                                <label class="col-form-label py-0 mb-1 d-flex justify-content-between align-items-center">
                                    <span>Marca</span>
                                    <button type="button" class="btn btn-link btn-xs p-0 text-primary font-weight-bold" wire:click="openModalNuevoCatalogo('marca')" title="Agregar nueva marca">
                                        <i class="fas fa-plus-circle"></i> Nueva marca
                                    </button>
                                </label>
                                <div class="input-group">
                                    <select class="form-control" wire:model="marca" wire:change="upModelos">
                                        <option value="">Seleccionar</option>
                                        @foreach($marcas as $m)
                                            <option value="{{ $m->id }}">{{ $m->descripcion }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-primary" wire:click="openModalNuevoCatalogo('marca')" title="Agregar nueva marca">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-2">
                                <label class="col-form-label py-0 mb-1 d-flex justify-content-between align-items-center">
                                    <span>Modelo</span>
                                    <button type="button" class="btn btn-link btn-xs p-0 text-primary font-weight-bold" wire:click="openModalNuevoCatalogo('modelo')" title="Agregar nuevo modelo">
                                        <i class="fas fa-plus-circle"></i> Nuevo modelo
                                    </button>
                                </label>
                                <div class="input-group">
                                    <select class="form-control" wire:model="modelo">
                                        <option value="">Seleccionar</option>
                                        @foreach($modelos as $mo)
                                            <option value="{{ $mo->id }}">{{ $mo->descripcion }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-primary" wire:click="openModalNuevoCatalogo('modelo')" title="Agregar nuevo modelo">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-form-label">Dominio</label>
                                <input type="text" class="form-control" wire:model="dominio">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-form-label">Color</label>
                                <select class="form-control" wire:model="color">
                                    <option value="">Seleccionar</option>
                                    @foreach($colores as $c)
                                        <option value="{{ $c->descripcion }}">{{ $c->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-form-label">Versión</label>
                                <input type="text" class="form-control" wire:model="version">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-form-label">Año</label>
                                <input type="number" class="form-control" wire:model="año">
                            </div>
                        </div>

                        <div class="col-12 pt-2 d-flex justify-content-end">
                            <button class="btn btn-success" wire:click="addVehicle">Guardar vehículo</button>
                        </div>
                    </div>
                    @endif
                    @endif


                    @if ($formperson == true)
                    <!-- SECCION DONDE SE CREA EL NUEVO CLIENTE -->
                    <div class="pl-4 row">
                        <h4><strong> Agregar nuevo cliente </strong> </h4>
                    </div>
                    <div class="px-3 row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputCliente" class="col-sm-2 col-form-label">Nombre</label>
                                <input type="text" class="form-control" id="inputCliente" wire:model='nombre' wire:keydown.enter='addClient'>
                                <div style="color: red; font-weight: 800;">
                                    @error('nombre')
                                    {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputCliente" class="col-sm-2 col-form-label">Apellido</label>
                                <input type="text" class="form-control" id="inputCliente" wire:model='apellido' wire:keydown.enter='addClient'>
                                <div style="color: red; font-weight: 800;">
                                    @error('apellido')
                                    {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputCliente" class="col-sm-2 col-form-label">DNI</label>
                                <input type="number" class="form-control" id="inputCliente" wire:model='dni' wire:keydown.enter='addClient'>
                                <div style="color: red; font-weight: 800;">
                                    @error('dni')
                                    {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputCliente" class="col-form-label">Fecha de Nac.</label>
                                <input type="date" class="form-control" id="inputCliente" wire:model='fecha_nac'>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputTelefono" class="col-form-label">Teléfono</label>
                                <input type="text" class="form-control" id="inputTelefono" wire:model='numero_telefono' wire:keydown.enter='addClient' placeholder="Solo números">
                                <div style="color: red; font-weight: 800;">
                                    @error('numero_telefono')
                                    {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="pt-2 col-md-12">
                            <div class="form-group d-flex justify-content-end">
                                <button class="mr-2 btn btn-danger" wire:click='formPerson'>Cancelar</button>
                                <button class="btn btn-success" wire:click='addClient'>Guardar</button>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
                <div class="modal-footer" style="position: sticky; bottom: 0; z-index: 2; background: #fff;">
                    <button type="submit" class="btn btn-primary" form="supplierOrderForm" wire:click="continueForm"><strong> Continuar </strong> <i class="fas fa-arrow-right"></i>
</button>
                </div>
            </div>
        </div>
    </div>

    @endif

    <!-- MODAL AGREGAR VEHÍCULO / MODELO AL CATÁLOGO -->
    @if($modalNuevoCatalogo)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.6); z-index: 1060;" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header bg-primary text-white py-2">
                        <h5 class="modal-title font-weight-bold">
                            @if($cat_target === 'marca')
                                <i class="fas fa-tag mr-1"></i> Agregar Nueva Marca al Catálogo
                            @elseif($cat_target === 'modelo')
                                <i class="fas fa-car mr-1"></i> Agregar Nuevo Modelo al Catálogo
                            @elseif($cat_target === 'tipo')
                                <i class="fas fa-truck mr-1"></i> Agregar Nuevo Tipo de Vehículo
                            @else
                                <i class="fas fa-car mr-1"></i> Agregar Auto / Modelo al Catálogo
                            @endif
                        </h5>
                        <button type="button" class="close text-white" wire:click="closeModalNuevoCatalogo">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small mb-3">
                            @if($cat_target === 'marca')
                                Ingrese la nueva marca y verifique el tipo. Opcionalmente puede registrar su modelo inicial.
                            @elseif($cat_target === 'modelo')
                                Ingrese el nuevo modelo para la marca seleccionada.
                            @else
                                Este vehículo preestablecido quedará guardado en el catálogo y seleccionado para este cliente.
                            @endif
                        </p>

                        <!-- 1. TIPO -->
                        <div class="form-group mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="font-weight-bold mb-0">1. Tipo de Vehículo <span class="text-danger">*</span></label>
                                <button type="button" class="btn btn-link btn-xs p-0 text-primary font-weight-bold" wire:click="toggleCatNuevoTipo">
                                    {{ $cat_crear_nuevo_tipo ? '← Elegir existente' : '+ Crear nuevo tipo' }}
                                </button>
                            </div>
                            @if($cat_crear_nuevo_tipo)
                                <input type="text" class="form-control form-control-sm @error('cat_nuevo_tipo_nombre') is-invalid @enderror" placeholder="Ej: CAMIÓN, MOTO, UTILITARIO..." wire:model="cat_nuevo_tipo_nombre">
                                @error('cat_nuevo_tipo_nombre')<span class="text-danger small">{{ $message }}</span>@enderror
                            @else
                                <select class="form-control form-control-sm @error('cat_tipo_id') is-invalid @enderror" wire:model.live="cat_tipo_id">
                                    <option value="">Seleccione tipo...</option>
                                    @foreach($tiposVehiculo as $t)
                                        <option value="{{ $t->id }}">{{ $t->descripcion }}</option>
                                    @endforeach
                                </select>
                                @error('cat_tipo_id')<span class="text-danger small">{{ $message }}</span>@enderror
                            @endif
                        </div>

                        <!-- 2. MARCA -->
                        <div class="form-group mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="font-weight-bold mb-0">2. Marca <span class="text-danger">*</span></label>
                                <button type="button" class="btn btn-link btn-xs p-0 text-primary font-weight-bold" wire:click="toggleCatNuevaMarca">
                                    {{ $cat_crear_nueva_marca ? '← Elegir existente' : '+ Crear nueva marca' }}
                                </button>
                            </div>
                            @if($cat_crear_nueva_marca)
                                <input type="text" class="form-control form-control-sm @error('cat_nueva_marca_nombre') is-invalid @enderror" placeholder="Ej: BMW, CHERY, AUDI..." wire:model="cat_nueva_marca_nombre">
                                @error('cat_nueva_marca_nombre')<span class="text-danger small">{{ $message }}</span>@enderror
                            @else
                                <select class="form-control form-control-sm @error('cat_marca_id') is-invalid @enderror" wire:model="cat_marca_id">
                                    <option value="">Seleccione marca...</option>
                                    @foreach($cat_marcasDisponibles as $m)
                                        <option value="{{ $m->id }}">{{ $m->descripcion }}</option>
                                    @endforeach
                                </select>
                                @error('cat_marca_id')<span class="text-danger small">{{ $message }}</span>@enderror
                            @endif
                        </div>

                        <!-- 3. MODELO -->
                        <div class="form-group mb-3">
                            <label class="font-weight-bold mb-1">
                                3. Nombre del Modelo
                                @if(!$cat_crear_nueva_marca)
                                    <span class="text-danger">*</span>
                                @else
                                    <span class="text-muted font-weight-normal small">(opcional)</span>
                                @endif
                            </label>
                            <input type="text" class="form-control form-control-sm @error('cat_nuevo_modelo_nombre') is-invalid @enderror" placeholder="Ej: Serie 3, X5, Hilux GR, 208..." wire:model="cat_nuevo_modelo_nombre">
                            @error('cat_nuevo_modelo_nombre')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2">
                        <button type="button" class="btn btn-secondary btn-sm" wire:click="closeModalNuevoCatalogo">Cancelar</button>
                        <button type="button" class="btn btn-success btn-sm font-weight-bold" wire:click="saveNuevoCatalogo">
                            <i class="fas fa-check-circle mr-1"></i> Guardar y Seleccionar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
