<div>

    <div>
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"><strong>
                        {{ mb_strtoupper($persona->perfiles->personas->nombre) }}
                        {{ mb_strtoupper($persona->perfiles->personas->apellido) }}
                    </strong></h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body py-1 px-3" style="display: block;">
                <div class="row p-2">
                    <div class="col-md-2 d-flex flex-column">
                        <h6><strong>DNI: </strong>{{ $persona->perfiles->personas->DNI ?? '-' }}</h6>
                        <h6>
                            <strong>EDAD: </strong>
                            @if($persona->perfiles->personas->fecha_nac)
                                {{ \Carbon\Carbon::parse($persona->perfiles->personas->fecha_nac)->age }} años
                            @else
                                -
                            @endif
                        </h6>
                    </div>

                    <div class="col-md-3 d-flex flex-column">
                        <h6>
                            <strong>EMAIL: </strong>
                            {{ optional(optional(optional($persona->perfiles->first())->personas)->correos->first())->direccion ?? '-' }}
                        </h6>


                        <h6>
                            <strong>TELEFONO: </strong>
                            {{ $persona->perfiles->personas->numero_telefono ?? '-' }}
                        </h6>
                    </div>

                    <div class="col-md-4 d-flex flex-column">
                        <h6>
                            <strong>DOMICILIO: </strong>
                            {{-- Si deseas mostrar un domicilio específico, reemplaza este texto por el campo correcto --}}
                            {{ '-' }}
                        </h6>

                        <h6>
                            <strong>CUMPLEAÑOS: </strong>
                            @if($persona->perfiles->personas->fecha_nac)
                                {{ \Carbon\Carbon::parse($persona->perfiles->personas->fecha_nac)->translatedFormat('j \\d\\e F', 'es') }}
                            @else
                                {{ '-' }}
                            @endif
                        </h6>
                    </div>
            
                </div>
            </div>
        </div>

        <!-- MODAL PARA COMPLETAR DATOS DE Cliente  -->
        @if ($modalDatos)
            <div class="modal fade show" id="modal-datos-pac"
                style="display: block; background-color: rgba(0, 0, 0, 0.5);" wire:ignore.self>
                <div class="modal-dialog modal-l">
                    <div class="modal-content">
                        <div class="modal-header bg-info">
                            <h4 class="modal-title"> <strong> COMPLETAR DATOS DE CLIENTE </strong> </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                wire:click='$dispatch("modal-datosPac")'>
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="apellido">Apellido</label>
                                        <input type="text" class="form-control" wire:model="apellido" id="apellido"
                                            placeholder="">
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Nombre</label>
                                        <input type="text" class="form-control" wire:model="nombre" id="nombre"
                                            placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputFecha">Fecha de nacimiento</label>
                                        <input type="date" class="form-control" id="inputFecha" placeholder="">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputDni">DNI</label>
                                        <input type="text" class="form-control" wire:model='dni' id="inputDni"
                                            placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Email</label>
                                        <input type="email" class="form-control" id="exampleInputEmail1"
                                            placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputTel">Telefono</label>
                                        <input type="number" class="form-control" id="inputTel" placeholder="">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal"
                                wire:click='$dispatch("modal-datosPac")'>Cerrar</button>
                            <button type="button" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>

</div>
