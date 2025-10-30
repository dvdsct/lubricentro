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
                            <select id="" wire:model.live='cliente' class="form-control" wire:change="upPerson" aria-label="Default select example">
                                <option selected> Seleccionar cliente</option>
                                @foreach ($clientes as $c)
                                <option value="{{ $c->id }}">
                                    {{ $c->id }}
                                    {{ $c->perfiles->personas->nombre }}
                                    {{ $c->perfiles->personas->apellido }}
                                    @if($c->perfiles->personas->DNI)
                                        - DNI: {{ $c->perfiles->personas->DNI }}
                                    @elseif($c->perfiles->personas->numero_telefono)
                                        - Tel: {{ $c->perfiles->personas->numero_telefono }}
                                    @endif
                                </option>
                                @endforeach ...
                            </select>
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
                    <div class="px-3 row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-form-label">Tipo de vehículo</label>
                                <select class="form-control" wire:model="tipo" wire:change="upMarcas">
                                    <option value="">Seleccionar</option>
                                    @foreach($tiposVehiculo as $t)
                                        <option value="{{ $t->id }}">{{ $t->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-form-label">Marca</label>
                                <select class="form-control" wire:model="marca" wire:change="upModelos">
                                    <option value="">Seleccionar</option>
                                    @foreach($marcas as $m)
                                        <option value="{{ $m->id }}">{{ $m->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-form-label">Modelo</label>
                                <select class="form-control" wire:model="modelo">
                                    <option value="">Seleccionar</option>
                                    @foreach($modelos as $mo)
                                        <option value="{{ $mo->id }}">{{ $mo->descripcion }}</option>
                                    @endforeach
                                </select>
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

</div>
