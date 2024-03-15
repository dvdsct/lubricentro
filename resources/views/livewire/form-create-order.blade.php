<div>

    @if ($modal == true)
        <div class="modal fade show" id="modal-lg" style="display: block; padding-right: 17px;" aria-modal="true"
            role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $fecha }}</h4>
                        <button type="button" class="close" wire:click='closeModal'>
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if ($formperson == false)
                            <div class="btn btn-primary" wire:click='formPerson'>+</div>


                            <div wire:ignore>

                                <div class="mb-3 row">
                                    <select id="mySelect" wire:model='cliente' class="form-select"
                                        aria-label="Default select example">
                                        <option selected>Open this select menu</option>

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
                            <div class="">
                                <div class="mb-3 row">
                                    <label for="inputCliente" class="col-sm-2 col-form-label">Nombre</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputCliente"
                                            wire:model='nombre'>
                                    </div>

                                </div>
                                <div class="mb-3 row">
                                    <label for="inputCliente" class="col-sm-2 col-form-label">Apellido</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputCliente"
                                            wire:model='apellido'>
                                    </div>

                                </div>

                                <div class="mb-3 row">
                                    <div class="col">
                                        <label for="inputCliente" class="col-sm-2 col-form-label">DNI</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputCliente"
                                                wire:model='dni'>
                                        </div>
                                    </div>
                                    <div class="col">



                                        <label for="inputCliente" class="col-sm-2 col-form-label">Fecha de
                                            Nacimiento</label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" id="inputCliente"
                                                wire:model='fecha_nac'>
                                        </div>
                                    </div>

<<<<<<< HEAD
=======
                                    <select class="form-select" aria-label="Default select example" wire:model='vehiculo'>
                                        <option selected>Open this select menu</option>
                                        @foreach ($vehiculos as $v)
                                        <option value="{{ $v->id }}">{{ $v->marcas->descripcion }}
                                            {{ $v->medelos }}
                                bril


                                    <div class='col'>

                                        <label for="inputVehiculo" class="col-sm-2 col-form-label">Dominio</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputVehiculo"
                                                wire:model='dominio'>
                                        </div>
                                    </div>

                                    <div class='col'>

                                        <label for="inputVehiculo" class="col-sm-2 col-form-label">Marca</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputVehiculo"
                                                wire:model='marca'>
                                        </div>
                                    </div>



                                    <div class='col'>

                                        <label for="inputVehiculo" class="col-sm-2 col-form-label">Modelo</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputVehiculo"
                                                wire:model='modelo'>
                                        </div>
                                    </div>


                                    <div class='col'>

                                        <label for="inputVehiculo" class="col-sm-2 col-form-label">Tipo Vehiculo</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputVehiculo"
                                                wire:model='tipo_vehiculo'>
                                        </div>
                                    </div>


                                    <div class='col'>

                                        <label for="inputVehiculo" class="col-sm-2 col-form-label">Color</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputVehiculo"
                                                wire:model='color'>
                                        </div>
                                    </div>


                                    <div class='col'>

                                        <label for="inputVehiculo" class="col-sm-2 col-form-label">Año</label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" id="inputVehiculo"
                                                wire:model='año'>
                                        </div>
                                    </div>


                                    <div class='col'>

                                        <label for="inputVehiculo" class="col-sm-2 col-form-label">Version</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputVehiculo"
                                                wire:model='version'>
                                        </div>
                                    </div>

                                    @if ($formperson == false)

                                        <select class="form-select" aria-label="Default select example"
                                            wire:model='vehiculo'>
                                            <option selected>Open this select menu</option>
                                            @foreach ($vehiculos as $v)
                                                <option value="{{ $v->id }}">{{ $v->marcas->descripcion }}
                                                    {{ $v->modelos }}
                                                </option>
                                            @endforeach

                                        </select>

                                    @endif


                                    <select class="form-select" aria-label="Default select example"
                                        wire:model='servicio'>
                                        <option selected>Open this select menu</option>
                                        @foreach ($servicios as $s)
                                            <option value="{{ $s->id }}">{{ $s->descripcion }}
                                            </option>
                                        @endforeach

                                    </select>


                                    <select class="form-select" aria-label="Default select example" wire:model='motivo'>
                                        <option selected>Open this select menu</option>
                                        <option value="1">Lav</option>
                                        <option value="2">Lubris</option>




                                    </select>




                                </div>

                            </div>

                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" wire:click='closeModal'>Close</button>
                        <button type="button" class="btn btn-primary" wire:click='addTurno'>Save changes</button>
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
