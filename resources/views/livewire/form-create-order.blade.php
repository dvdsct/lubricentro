<div>
    @if ($modal == true)
        <div class="modal fade show" id="modal-default" style="display: block; background-color: rgba(0, 0, 0, 0.5);"
            aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h4 class="modal-title"> <strong> ORDEN </strong> </h4>
                        <button type="button" class="close" wire:click='closeModal'>
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <!-- SI EL CLIENTE NO EXISTE - CREAR NUEVO CLIENTE -->
                    <div class="modal-body">
                        <!-- SECTOR AL QUE SE LE ASIGNARA EL TURNO -->
                        <div class="row  d-flex justify-content-between">
                            <h4 class="pl-2"> <strong> SERVICIO </strong> </h4>
                        </div>
                        <div class="px-3 d-flex justify-content-center pb-1">
                            <div class="row">
                                <div class="d-flex justify-content-between">
                                    <div class="col-md-4 col-xs-12">
                                        <button wire:click="setMot('lub')" type="button"
                                            class="btn btn-lg {{ $s_btnLub }}" style="width: 150px; ">
                                            <i class="fas fa-tools"></i>
                                            <h6 style="display: inline;"><strong>Lubricentro</strong></h6>
                                        </button>
                                    </div>

                                    <div class="col-md-4">
                                        <button wire:click="setMot('lav')" class="btn btn-lg {{ $s_btnLav }}"
                                            style="width: 150px;">
                                            <i class="fas fa-hand-sparkles"></i>
                                            <h6 style="display: inline;"> <strong> Lavadero </strong> </h6>
                                        </button>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="input-group input-group-lg">
                                            <input type="time" class="form-control" aria-label="Sizing example input"
                                                aria-describedby="inputGroup-sizing-lg" wire:model='horario'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- LISTADO DESPLEGABLE DE CLIENTES  -->

                        @if ($formperson == false)
                            <div class="row pt-2 d-flex justify-content-between">
                                <h4 class="pl-2"> <strong> CLIENTE </strong> </h4>
                            </div>
                            @if ($cliente == null)
                                <div class="row">
                                    <div class="col-10 col-xs-10">
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
                                <div class="row px-3">
                                    <h2> <span class="font-italic float-right badge bg-secondary"> {{ $nombre }}
                                            {{ $apellido }} - {{ $dni }} </span> </h2>

                                    <!-- BOTON ELIMINAR CLIENTE SELECCIONADO -->
                                    <div class="col-1 pl-2">
                                        <button class="btn btn-danger" wire:click='formPerson'>
                                            <div class="icon">
                                                <i class="fas fa-user-minus"></i>
                                            </div>
                                        </button>
                                    </div>
                                </div>

                            @endif
                        @endif


                        @if ($formperson == true)
                            <!-- SECCION DONDE SE CREA EL NUEVO CLIENTE -->
                            <div class="row pl-4">
                                <h4><strong> Agregar nuevo cliente </strong> </h4>
                            </div>
                            <div class="row px-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputCliente" class="col-sm-2 col-form-label">Nombre</label>
                                        <input type="text" {{ $act }} class="form-control"
                                            id="inputCliente" wire:model='nombre'>
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
                                        <input type="text" {{ $act }} class="form-control"
                                            id="inputCliente" wire:model='apellido'>
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
                                        <input type="number" {{ $act }} class="form-control"
                                            id="inputCliente" wire:model='dni'>
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
                                        <input type="date" {{ $act }} class="form-control"
                                            id="inputCliente" wire:model='fecha_nac'>
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
                                <div class="row pl-2 pt-2">
                                    <h4> <strong>VEHÍCULO </strong> </h4>
                                </div>
                                <!-- SI EL VEHICULO NO EXISTE, CREAR NUEVO VEHICULO -->
                                <div class="px-3">
                                    <div class="row">
                                        <div class="col">
                                            <select class="form-control" aria-label="Default select example"
                                                wire:model='tipo' wire:change='upMarcas'>
                                                <option selected>Tipo de vehiculo</option>
                                                @foreach ($tiposVehiculo as $tipos)
                                                    <option value="{{ $tipos->id }}">{{ $tipos->descripcion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- <div class="col-md-6">
                                            <select class="form-control" aria-label="Default select example"
                                                wire:model='marca' wire:change='upModelos'>
                                                <option selected>Marca</option>
                                                @foreach ($marcas as $marca)
                                                    <option value="{{ $marca->marcas->id }}">{{ $marca->marcas->descripcion }} - {{ $marca->descripcion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div> --}}




                                    </div>

                                    <div class="row pt-4">
                                        <div class="col-md-6">
                                            <select class="form-control" aria-label="Default select example"
                                                wire:model='modelo'>
                                                <option selected>Modelo</option>
                                                @foreach ($modelos as $modelo)
                                                    <option value="{{ $modelo->id }}">{{ $modelo->marcas->descripcion }} - {{ $modelo->descripcion }}
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
                                <div class="row pl-2 pt-2">
                                    <h4><strong> VEHÍCULO </strong></h4>
                                </div>

                                @if ($selecedtVehiculo == true)

                                    <!-- AQUI MUESTRA EL VEHICULO DEL CLIENTE JUNTO CON SU DOMINIO -->

                                    <div class="row px-3">
                                        <h2> <span class="font-italic float-right badge bg-secondary">
                                                {{ $vehiculo->modelos->marcas->descripcion }}
                                                {{ $vehiculo->modelos->descripcion . ' - ' . $vehiculo->dominio }}
                                                <!-- VARIABLES PARA MOSTRAR VECHICULO Y DOMINIO --></span> </h2>

                                        <!-- BOTON PARA ELIMINAR VEHICULO SELECCIONADO -->
                                        <div class="col-1">
                                            <button class="btn btn-danger" wire:click='setForm'>
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
                                                        {{ $vc->modelos->descripcion . ' - ' . $vc->dominio }}
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

                    </div>


                    <!-- FOOTER DEL MODAL -->
                    <div class="modal-footer justify-content-between pb-4">
                        <button type="button" class="btn btn-default" wire:click='closeModal'>Cancelar</button>
                        <button type="button" class="btn btn-primary" wire:click='addTurno'>Guardar</button>
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

        </div>
    @endif
</div>
