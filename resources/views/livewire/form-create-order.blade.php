<div>

    @if ($modal == true)
        <div class="modal fade show" id="modal-lg" style="display: block; padding-right: 17px;" aria-modal="true"
            role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h4 class="modal-title"> <strong> Nuevo turno </strong> </h4>
                        <button type="button" class="close" wire:click='closeModal'>
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">


                        @if ($formperson == false)

                            <div class="row bg-info d-flex justify-content-between">

                                <h3>Datos Cliente</h3>

                                <button class="btn btn-primary" wire:click='formPerson'>
                                    <div class="icon">
                                        <i class="ion ion-person-add"></i>
                                    </div>
                                    Nuevo Cliente
                                </button>
                            </div>
                            @if ($cliente == null)
                                <div class="row">
                                    <select id="" wire:model.live='cliente' class="form-control"
                                        wire:change="upPerson" aria-label="Default select example">
                                        <option selected></option>


                                        @foreach ($clientes as $c)
                                            <option value="{{ $c->id }}">

                                                {{ $c->id }}
                                                {{ $c->perfiles->personas->nombre }}
                                                {{ $c->perfiles->personas->apellido }} - DNI:
                                                {{ $c->perfiles->personas->DNI }}
                                            </option>
                                        @endforeach ...
                                    </select>
                                </div>
                            @else
                                {{ $nombre }} {{ $apellido }}

                            @endif
                        @endif
                    </div>
                    @if ($formperson == true)
                        <div class="row bg-info">

                            <h3>Datos Cliente</h3>


                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputCliente" class="col-sm-2 col-form-label">Nombre</label>
                                    <input type="text" {{ $act }} class="form-control" id="inputCliente"
                                        wire:model='nombre'>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputCliente" class="col-sm-2 col-form-label">Apellido</label>
                                    <input type="text" {{ $act }} class="form-control" id="inputCliente"
                                        wire:model='apellido'>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputCliente" class="col-sm-2 col-form-label">DNI</label>
                                    <input type="text" {{ $act }} class="form-control" id="inputCliente"
                                        wire:model='dni'>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputCliente" class="col-form-label">Fecha de Nac.</label>
                                    <input type="date" {{ $act }} class="form-control" id="inputCliente"
                                        wire:model='fecha_nac'>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button class="btn btn-primary {{ $butt }}"
                                        wire:click='addClient'>Guardar</button>
                                </div>
                            </div>

                        </div>
                    @endif
                    <hr>


                    {{-- Vehiculo --}}
                    @if ($cliente != null)
                        @if ($formVehiculo == true)
                            <div class="row  bg-info">

                                <h3>Datos Vehiculo</h3>
                            </div>


                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label class="col col-form-label">Tipo Vehiculo</label>
                                        <select class="form-control" aria-label="Default select example"
                                            wire:model='tipos'>
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
                                        <select class="form-control" aria-label="Default select example"
                                            wire:model='marca'>
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
                                        <select class="form-control" aria-label="Default select example"
                                            wire:model='modelo'>
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
                                        <select class="form-control" aria-label="Default select example"
                                            wire:model='color'>
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
                                        <input type="text" class="form-control" id="inputCliente"
                                            wire:model='dominio'>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputCliente" class="col-form-label">Version</label>
                                        <input type="text" class="form-control" id="inputCliente"
                                            wire:model='version'>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputCliente" class="col-form-label">Año</label>
                                        <input type="text" class="form-control" id="inputCliente"
                                            wire:model='año'>
                                    </div>
                                </div>
                                <button class="btn btn-primary" wire:click='addVehicle'>Guadar</button>


                            </div>
                        @else
                            <div class="row bg-info d-flex justify-content-between">

                                <h3>Datos Vehiculo</h3>
                                <button class="btn btn-primary" wire:click='setForm'>Agregar</button>
                            </div>



                            <div class="row">

                                <div class="col">
                                    <div class="form-group">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="col col-form-label">Vehiculos</label>
                                                <select class="form-control" aria-label="Default select example"
                                                    wire:model='vehiculo'>
                                                    <option selected>Seleccionar..</option>
                                                    @foreach ($cliente->vehiculos as $vc)
                                                        <option value="{{ $vc->id }}">
                                                            {{ $vc->modelos->descripcion . ' ' . $vc->dominio }}

                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                        @endif
                    @else
                        <div class="row bg-info d-flex justify-content-between">

                            <h3>Datos Vehiculo</h3>
                            <button class="btn btn-primary" disabled wire:click='setForm'>Agregar</button>
                        </div>
                    @endif



                    <hr><br>
                    {{-- Sector --}}

                    <h3>Servicio</h3>
                    <div class="row d-flex justify-content-between">

                        <div class="col">
                            <div class="form-group">

                                <div>
                                    <button wire:click="setMot('lav')"
                                        class="btn {{ $s_btnLav  }} px-4 py-2 mr-2">Lavadero</button>
                                    <button wire:click="setMot('lub')"
                                        class="btn {{ $s_btnLub }} px-4 py-2">Lubricentro</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>


                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" wire:click='closeModal'>Cancelar</button>
                        <button type="button" class="btn btn-primary" wire:click='addTurno'>Guardar</button>
                    </div>

                </div>


            </div>
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
