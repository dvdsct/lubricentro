<div>
    @if ($modal == true)
        <div class="modal fade show" id="modal-default" style="display: block;" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h4 class="modal-title"> <strong> NUEVO TURNO </strong> </h4>
                        <button type="button" class="close" wire:click='closeModal'>
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <!-- SI EL CLIENTE NO EXISTE - CREAR NUEVO CLIENTE -->
                    <div class="modal-body">
                        @if ($formperson == false)
                            <div class="row  d-flex justify-content-between">
                                <h4 class="pl-2"> <strong> CLIENTE </strong> </h4>
                            </div>
                            @if ($cliente == null)
                                <div class="row pt-2">
                                    <div class="col-md-10">
                                        <select id="" wire:model.live='cliente' class="form-control"
                                            wire:change="upPerson" aria-label="Default select example">
                                            <option selected> Seleccionar cliente</option>
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
                                    <!-- BOTON CREAR NUEVO CLIENTE  -->
                                    <div class="col-md-2">
                                        <button class="btn btn-success" wire:click='formPerson'>
                                            <div class="icon">
                                                <i class="fas fa-user-plus"></i>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <!-- MUESTRA NOMBRE APELLIDO Y DNI DEL CLIENTE SELECCIONADO -->
                                <div class="row d-flex justify-content-center">
                                    <h1> <span class="font-italic float-right badge bg-secondary"> {{ $nombre }}
                                            {{ $apellido }} - {{ $dni }} </span> </h1>
                                </div>
                                            <!-- BOTON CREAR NUEVO CLIENTE  -->
                                            <div class="col-md-2">
                                                <button class="btn btn-success" wire:click='formPerson'>
                                                    <div class="icon">
                                                        <i class="fas fa-user-plus"></i>
                                                    </div>
                                                </button>
                                            </div>
                            @endif
                        @endif
                    </div>

                    @if ($formperson == true)
                        <!-- SECCION DONDE SE CREA EL NUEVO CLIENTE -->
                        <div class="row pl-4">
                            <h4><strong> Agregar nuevo cliente </strong> </h4>
                        </div>
                        <div class="row px-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputCliente" class="col-sm-2 col-form-label">Nombre</label>
                                    <input type="text" {{ $act }} class="form-control" id="inputCliente"
                                        wire:model='nombre'>
                                    <div>
                                        @error('nombre')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputCliente" class="col-sm-2 col-form-label">Apellido</label>
                                    <input type="text" {{ $act }} class="form-control" id="inputCliente"
                                        wire:model='apellido'>
                                    <div>
                                        @error('apellido')
                                            {{ $message }}
                                        @enderror
                                    </div>

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
                            <div class="col-md-12 pt-2">
                                <div class="form-group d-flex justify-content-end">
                                    <button class="btn btn-danger mr-2" wire:click='formPerson'>Cancelar</button>
                                    <button class="btn btn-success" wire:click='addClient'>Guardar</button>
                                </div>
                            </div>
                        </div>
                    @endif


                    @if ($cliente != null)
                        @if ($formVehiculo == true)
                            <div class="pl-3">
                                <h4> <strong>VEHÍCULO </strong> </h4>
                            </div>
                            <!-- SI EL VEHICULO NO EXISTE, CREAR NUEVO VEHICULO -->
                            <div class="px-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <select class="form-control" aria-label="Default select example"
                                            wire:model='marca'>
                                            <option selected>Marca</option>
                                            @foreach ($marcas as $marca)
                                                <option value="{{ $marca->id }}">{{ $marca->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <select class="form-control" aria-label="Default select example"
                                            wire:model='modelo'>
                                            <option selected>Modelo</option>
                                            @foreach ($modelos as $modelo)
                                                <option value="{{ $modelo->id }}">{{ $modelo->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row pt-4">
                                    <div class="col-md-6">
                                        <select class="form-control" aria-label="Default select example"
                                            wire:model='tipos'>
                                            <option selected>Tipo de vehiculo</option>
                                            @foreach ($tipos_vehiculo as $tipos)
                                                <option value="{{ $tipos->id }}">{{ $tipos->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <select class="form-control" aria-label="Default select example"
                                            wire:model='color'>
                                            <option selected>Color</option>
                                            @foreach ($colores as $color)
                                                <option value="{{ $color->id }}">{{ $color->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row pt-4">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="inputCliente"
                                            placeholder="Dominio" wire:model='dominio'>
                                    </div>


                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="inputCliente"
                                            placeholder="Año" wire:model='año'>
                                    </div>
                                </div>

                                <div class="col-md-12 pt-3">
                                    <div class="form-group d-flex justify-content-end">
                                        <button class="btn btn-danger mr-2" wire:click='setForm'>Cancelar</button>
                                        <button class="btn btn-success" wire:click='addVehicle'>Guardar</button>
                                    </div>
                                </div>

                            </div>
                        @else
                            <!-- SECCION DONDE SE ELIJE O CREA UN NUEVO VEHICULO -->
                            <div class="row pl-4">
                                <h4><strong> VEHÍCULO </strong></h4>
                            </div>

                            @if ($selecedtVehiculo == true)

                                <!-- AQUI MUESTRA EL VEHICULO DEL CLIENTE JUNTO CON SU DOMINIO -->
                                <div class="col-md-10">

                                    <div class="row d-flex justify-content-center">
                                        <h1> <span class="font-italic float-right badge bg-danger">
                                                {{ $vehiculo->modelos->marcas->descripcion }}
                                                {{ $vehiculo->modelos->descripcion . ' ' . $vehiculo->dominio }}
                                                <!-- VARIABLES PARA MOSTRAR VECHICULO Y DOMINIO --></span> </h1>
                                    </div>
                                    <!-- BOTON PARA CREAR NUEVO VEHICULO -->
                                    <div class="col-md-2">
                                        <button class="btn btn-success" wire:click='setForm'>
                                            <div class="icon">
                                                <i class="fas fa-car"></i>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <!-- SELECCIONAR VEHICULO EXISTENTE -->
                                <div class="row px-3">
                                    <div class="col-md-10">
                                        <select class="form-control" aria-label="Default select example"
                                            wire:model='vehiculo' wire:change='selectVehiculo'>
                                            <option selected>Seleccionar vehiculo</option>
                                            @foreach ($cliente->vehiculos as $vc)
                                                <option value="{{ $vc->id }}">
                                                    {{ $vc->modelos->descripcion . ' ' . $vc->dominio }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <!-- BOTON PARA CREAR NUEVO VEHICULO -->
                                    <div class="col-md-2">
                                        <button class="btn btn-success" wire:click='setForm'>
                                            <div class="icon">
                                                <i class="fas fa-car"></i>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            @endif


                        @endif
                    @endif


                    <!-- SECTOR AL QUE SE LE ASIGNARA EL TURNO -->
                    <div class="row pl-4">
                        <h4><strong> SERVICIO </strong></h4>
                    </div>

                    <div class="row px-3 d-flex justify-content-center pb-1">
                        <div class="d-flex justify-content-between">
                            <button wire:click="setMot('lub')"
                                class="btn btn-app bg-danger {{ $s_btnLub }} px-4 py-2"
                                style="width: 180px; height:90px">
                                <i class="fas fa-tools"></i>
                                <h5> <strong> Lubricentro </strong> </h5>
                            </button>

                            <button wire:click="setMot('lav')"
                                class="btn btn-app bg-warning {{ $s_btnLav }} px-4 py-2 mr-2"
                                style="width: 180px; height:90px">
                                <i class="fas fa-hand-sparkles"></i>
                                <h5> <strong> Lavadero </strong> </h5>
                            </button>
                        </div>
                    </div>
                    <!-- FOOTER DEL MODAL -->
                    <div class="modal-footer justify-content-between pb-4">
                        <button type="button" class="btn btn-default" wire:click='closeModal'>Cancelar</button>
                        <button type="button" class="btn btn-primary" wire:click='addTurno'>Guardar</button>
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

            </div>
        </div>
    @endif
</div>
