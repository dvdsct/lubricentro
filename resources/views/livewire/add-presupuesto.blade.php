<div>
    @if ($modal == true)

    <!-- MODAL PARA REALIZAR UN NUEVO PRESUPUESTO -->

    <div class="modal fade show" id="modal-default" style="display: block; padding-right: 17px;" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="supplierOrderModalLabel"> <strong> GENERAR NUEVO PRESUPUESTO </strong>
                    </h5>
                    <button type="button" class="close" wire:click='modalOnOff'>
                        <span aria-hidden="true">×</span>
                    </button>

                </div>
                <div class="modal-body">
                    @if ($formperson == false)
                    <div class="row pt-2 d-flex justify-content-between">
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
                        <div class="col-md-12 pt-2">
                            <div class="form-group d-flex justify-content-end">
                                <button class="btn btn-danger mr-2" wire:click='formPerson'>Cancelar</button>
                                <button class="btn btn-success" wire:click='addClient'>Guardar</button>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" form="supplierOrderForm" wire:click="continueForm"><strong> Continuar </strong> <i class="fas fa-arrow-right"></i>
</button>
                </div>
            </div>
        </div>
    </div>

    @endif

</div>