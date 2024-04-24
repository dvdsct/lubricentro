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

            <div class="card-body p-3" style="display: block;">
                <div class="row">
                    <div class="col-md-4 d-flex flex-column">
                        <h6><strong>DNI: </strong>{{ $persona->perfiles->personas->dni }}</h6>
                        <h6><strong>Edad: </strong> {{ $persona->perfiles->personas->edad }}</h6>
                    </div>

                    <div class="col-md-4 d-flex flex-column">
                        <h6>
                            <strong>Email: </strong>
                            {{-- {{ $persona->perfiles->personas->correos->first()->direccion ?? '-' }} --}}
                        </h6>

                        <h6>
                            <strong>Telefono: </strong>
                            {{-- {{ optional($persona->perfiles->personas->telefonos)->first()->numero ?? '-' }} --}}
                        </h6>
                    </div>

                    <div class="col-md-4 d-flex flex-column"
                        style="display: flex; justify-content: flex-end; align-items: flex-end;">
                        <a href="" class="nav-link" style="display: flex; justify-content: flex-end;"
                            data-toggle="modal" data-target="#modal-datos-pac">
                            <i class="fas fa-edit"></i> Completar datos de cliente
                        </a>
                    </div>

                </div>
            </div>
        </div>

        <!-- MODAL PARA COMPLETAR DATOS DE Cliente  -->

        <div class="modal fade" id="modal-datos-pac" style="display: none;" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-l">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h4 class="modal-title"> <strong> COMPLETAR DATOS DE CLIENTE </strong> </h4>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="apellido">Apellido</label>
                                    <input type="text" class="form-control" id="apellido" placeholder="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" placeholder="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fec_nac">Fecha de nacimiento</label>
                                    <input type="date" class="form-control" id="fec_nac" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dni">DNI</label>
                                    <input type="number" class="form-control" id="dni" placeholder="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email</label>
                                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telefono">Telefono</label>
                                    <input type="number" class="form-control" id="telefono" placeholder="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label for="direccion">Direccion</label>
                                    <input type="text" class="form-control" id="direccion" placeholder="">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>




    </div>

</div>
