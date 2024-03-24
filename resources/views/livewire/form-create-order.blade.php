<div>

    @if ($modal == true)
    <div class="modal fade show" id="modal-lg" style="display: block; padding-right: 17px;" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title"> <strong>  Nuevo turno </strong> </h4>
                    <button type="button" class="close" wire:click='closeModal'>
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    {{$nombre}}

                    @if ($formperson == false)

                    <button class="btn btn-primary" wire:click='formPerson'>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        Nuevo Cliente
                    </button>

                    <div>
                        <div class="mb-3 row">
                            <select id="mySelect" wire:model='cliente' class="form-control" wire:change="upPerson" aria-label="Default select example">
                                <option selected>Buscar clientes</option>
                                @foreach ($clientes as $c)
                                <option value="{{ $c->id }}">{{ $c->perfiles->personas->nombre }}
                                    {{ $c->perfiles->personas->apellido }} {{ $c->perfiles->personas->dni }}
                                </option>
                                @endforeach ...
                            </select>
                        </div>
                    </div>
                    @endif
                    @if ($formperson == true)

                    <h3>Datos Cliente</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputCliente" class="col-sm-2 col-form-label">Nombre</label>
                                <input type="text" class="form-control" id="inputCliente" wire:model='nombre' >
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputCliente" class="col-sm-2 col-form-label">Apellido</label>
                                <input type="text" class="form-control" id="inputCliente" wire:model='apellido' >
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputCliente" class="col-sm-2 col-form-label">DNI</label>
                                <input type="text" class="form-control" id="inputCliente" wire:model='dni' >
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputCliente" class="col-form-label">Fecha de Nac.</label>
                                <input type="date" class="form-control" id="inputCliente" wire:model='fecha_nac'>
                            </div>
                        </div>

                      {{--  <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 col-form-label">Vehiculo</label>
                                <select class="form-control" aria-label="Default select example" wire:model='vehiculo'>
                                    <option selected>Seleccionar..</option>
                                    @foreach ($vehiculos as $v)
                                    <option value="{{ $v->id }}">{{ $v->marcas->descripcion }}
                                        {{ $v->modelos }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                       --}}

                       <h3>Datos Vehiculo</h3>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col col-form-label">Tipo Vehiculo</label>
                                <select class="form-control" aria-label="Default select example" wire:model='tipos'>
                                    <option selected>Seleccionar..</option>
                                    @foreach ($tipos_vehiculo as $tipos)
                                    <option value="{{ $tipos->id }}">{{ $tipos->descripcion }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 col-form-label">Marca</label>
                                <select class="form-control" aria-label="Default select example" wire:model='marca'>
                                    <option selected>Seleccionar..</option>
                                    @foreach ($marcas as $marca)
                                    <option value="{{ $marca->id }}">{{ $marca->descripcion }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 col-form-label">Modelos</label>
                                <select class="form-control" aria-label="Default select example" wire:model='modelo'>
                                    <option selected>Seleccionar..</option>
                                    @foreach ($modelos as $modelo)
                                    <option value="{{ $modelo->id }}">{{ $modelo->descripcion }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 col-form-label">Color</label>
                                <select class="form-control" aria-label="Default select example" wire:model='color'>
                                    <option selected>Seleccionar..</option>
                                    @foreach ($colores as $color)
                                    <option value="{{ $color->id }}">{{ $color->descripcion }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputCliente" class="col-form-label">Dominio</label>
                                <input type="text" class="form-control" id="inputCliente" wire:model='dominio'>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputCliente" class="col-form-label">Version</label>
                                <input type="text" class="form-control" id="inputCliente" wire:model='version'>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputCliente" class="col-form-label">Año</label>
                                <input type="text" class="form-control" id="inputCliente" wire:model='año'>
                            </div>
                        </div>
<!--
                        <div class="col-md-3">
                            <button type="button" class="btn btn-success" wire:click='addTurno'>Agregar</button>
                        </div> -->
                        <h3>Servicio</h3>
                        <div class="col-md-6">
                        <div class="form-group">
                        <label class="col-sm-2 col-form-label">Sector</label>
                            <select class="form-control" aria-label="Default select example" wire:model='motivo'>
                                <option selected>Seleccionar</option>
                                <option value="1">Lavadero</option>
                                <option value="2">Lubricentro</option>
                            </select>
                        </div>
                        </div>

                        <div class="col-md-12">
                        <div class="form-group">
                        <label class="col-sm-2 col-form-label">Servicio</label>
                            <select class="form-control" aria-label="Default select example" wire:model='servicio'>
                                <option selected>Seleccionar servicio</option>
                                @foreach ($servicios as $s)
                                <option value="{{ $s->id }}">{{ $s->descripcion }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" wire:click='closeModal'>Cancelar</button>
                        <button type="button" class="btn btn-primary" wire:click='addTurno'>Guardar</button>
                    </div>

                </div>

            </div>

        </div>
        @endif
    </div>
    @script
    <script>
        $(document).ready(function() {
            $('#mySelect').select2();
            $('#mySelect').on('change', function() {
                @this.set('cliente', this.value)
                @this.upPerson()
            });

            document.addEventListener('livewire.navigated', () => {
                $('#mySelect').select2();

            });
        });
    </script>
    @endscript




    @endif






</div>
