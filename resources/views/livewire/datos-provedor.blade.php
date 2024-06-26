<div>

    <div>
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"><strong>
                        {{ mb_strtoupper($proveedor->perfiles->personas->nombre) }}
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
                        <h6><strong>CUIT: </strong>{{ $proveedor->cuit }}</h6>
                        <h6>
                            <strong>CATEGORIA: </strong>
                            {{ \Carbon\Carbon::parse($proveedor->perfiles->personas->fecha_nac)->age }} años
                        </h6>
                    </div>

                    <div class="col-md-3 d-flex flex-column">
                        <h6>
                            <strong>ALIAS: </strong>
                            {{ $proveedor->perfiles->first()->personas->correos->first()->direccion  ?? ''}}
                        </h6>

                        <h6>
                            <strong>TELEFONO: </strong>
                            {{-- {{ optional($proveedor->perfiles->personas->telefonos)->first()->numero ?? '-' }} --}}
                        </h6>
                    </div>

                    <div class="col-md-4 d-flex flex-column">
                        <h6>
                            <strong>DIRECCION: </strong>
                            {{-- {{ $proveedor->perfiles->personas->direcciones->first()->barrio ?? '-' }} --}}
                        </h6>

                        <h6>
                            <strong>EMAIL: </strong>
                            {{ $proveedor->perfiles->first()->personas->correos->first()->direccion  ?? ''}}
                        </h6>
                    </div>

                    <div class="col-md-3 d-flex flex-column"
                        style="display: flex; justify-content: flex-end; align-items: flex-end;">
                        <a href="" class="nav-link" style="display: flex; justify-content: flex-end;"
                            data-toggle="modal" data-target="#modal-datos-pac">
                            <i class="fas fa-edit"></i> EDITAR
                        </a>
                    </div>
                    @can('xx')
                        <div class="col-md-4 d-flex flex-column"
                            style="display: flex; justify-content: flex-end; align-items: flex-end;">
                            <a href="" class="nav-link" style="display: flex; justify-content: flex-end;"
                                data-toggle="modal" data-target="#modal-datos-pac">
                                <i class="fas fa-edit"></i> EDITAR
                            </a>
                        </div>
                    @endcan

                </div>
            </div>
        </div>
        @can('xx')
            <!-- MODAL PARA COMPLETAR DATOS DEL PROVEEDOR  -->

            <div class="modal fade" id="modal-datos-pac" style="display: none;" aria-hidden="true" wire:ignore.self>
                <div class="modal-dialog modal-l">
                    <div class="modal-content">
                        <div class="modal-header bg-info">
                            <h4 class="modal-title"> <strong> COMPLETAR DATOS DE CLIENTE </strong> </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                                <div class="modal fade" id="modal-datos-pac" style="display: none;" aria-hidden="true"
                                    wire:ignore.self>
                                    <div class="modal-dialog modal-l">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info">
                                                <h4 class="modal-title"> <strong> COMPLETAR DATOS DE CLIENTE </strong> </h4>
                                                <button type="button" class="btn-close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Apellido</label>
                                                            <input type="email" class="form-control"
                                                                id="exampleInputEmail1" placeholder="">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Nombre</label>
                                                            <input type="email" class="form-control"
                                                                id="exampleInputEmail1" placeholder="">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Fecha de nacimiento</label>
                                                            <input type="date" class="form-control"
                                                                id="exampleInputEmail1" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">DNI</label>
                                                            <input type="email" class="form-control"
                                                                id="exampleInputEmail1" placeholder="">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Email</label>
                                                            <input type="email" class="form-control"
                                                                id="exampleInputEmail1" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Telefono</label>
                                                            <input type="email" class="form-control"
                                                                id="exampleInputEmail1" placeholder="">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Cerrar</button>
                                                <button type="button" class="btn btn-primary">Guardar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endcan





                        </div>

                    </div>
