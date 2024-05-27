<div>

    <div class="row d-flex justify-content-between" style="padding-top: 20px;">
        <div class="col-md-3 d-flex align-items-center">
            <button wire:click='change_day("yes")' class="btn btn-info btn-sm">
                <i class="fas fa-arrow-left"></i></button>
            <input type="date" wire:model="fecha" class="form-control">
            <button wire:click='change_day("tmw")' class="btn btn-info btn-sm"><i class="fas fa-arrow-right"></i></button>
        </div>

        <!-- FECHA DEL MEDIO -->
        <div class="col-md-3">
            <h1 style="text-align: center;">
                <strong>{{ ucfirst(Carbon\Carbon::parse($fecha)->locale('es')->isoFormat('dddd DD ')) }} </strong>
            </h1>
        </div>

        <!-- BOTON PARA GENERAR NUEVO TURNO -->
        <div class="col-md-2 pt-2">
            @can('caja')
            <button type="button" class="btn btn-block btn-info" data-target="modal-default" wire:click="$dispatchTo('form-create-order', 'modal-order')">
                <i class="fas fa-plus-circle"></i> Nuevo Turno</button>
            @endcan
        </div>
    </div>

    <div class="row pt-2">
        <!-- TABLA LUBRICENTRO -->
        <div class="col-md-6">
            <div class="card">
                <table class="table">
                    <thead>
                        <span class="badge bg-orange" style="height: 40px;">
                            <h3><strong>Lubricentro </strong></h3>
                        </span>
                    </thead>
                    @if ($turnlub->isEmpty())
                    <h6 class="font-italic pt-2 pl-3"> Aun no hay turnos asignados para este día!</h6>
                    @else
                    <thead>
                        <th>ORDEN</th>
                        <th>HORARIO</th>
                        <th>CLIENTE</th>
                        <th>VEHICULO</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($turnlub as $t)
                        <tr>
                            <td class="py-0">{{ $t->id }} </td>
                            <td class="py-0">{{ \Carbon\Carbon::parse($t->horario)->format('H:i') }} hs</td>
                            <td class="py-0">{{ $t->clientes->perfiles->personas->nombre . ' ' . $t->clientes->perfiles->personas->apellido }} </td>
                            @if ($t->vehiculos != null)
                            <td class="py-0">
                                {{ $t->vehiculos->modelos->descripcion . ' ' . $t->vehiculos->descripcion . ' ' . $t->vehiculos->año }}
                                <span class="badge bg-orange">{{ $t->vehiculos->dominio }}</span>
                            </td>
                            @endif
                            <td class="p-1">
                                <div class="btn-group">
                                    <a class="btn btn-info" href="{{ route('ordenes.show', $t->id) }}">Atender</a>
                                    <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <a class="dropdown-item" href="#">
                                        <i class="fas fa-calendar-alt"></i> Reprogramar
                                        </a>
                                        <a class="dropdown-item" href="#">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @endif
                </table>
            </div>
        </div>

        <!-- TABLA LAVADERO -->
        <div class="col-md-6">
            <div class="card">
                <table class="table">
                    <thead>
                        <span class="badge bg-primary" style="height: 40px;">
                            <h3><strong>Lavadero </strong></h3>
                        </span>
                    </thead>
                    @if ($turnlav->isEmpty())
                    <h6 class="font-italic pt-2 pl-3"> Aun no hay turnos asignados para este día!</h6>
                    @else
                    <thead>
                        <th>ORDEN</th>
                        <th>HORARIO</th>
                        <th>CLIENTE</th>
                        <th>VEHICULO</th>
                    </thead>
                    <tbody>
                        @foreach ($turnlav as $t)
                        <tr>
                            <td class="py-0">{{ $t->id }}</td>
                            <td class="py-0">{{ \Carbon\Carbon::parse($t->horario)->format('H:i') }} hs</td>
                            <td class="py-0">{{ $t->clientes->perfiles->personas->nombre . ' ' . $t->clientes->perfiles->personas->apellido }} </td>
                            @if ($t->vehiculos != null)
                            <td class="py-0">
                                {{ $t->vehiculos->modelos->descripcion . ' ' . $t->vehiculos->descripcion . ' ' . $t->vehiculos->año }}
                                <span class="badge bg-primary">{{ $t->vehiculos->dominio }}</span>
                            </td>
                            @endif
                            <td class="py-1">
                            <div class="btn-group">
                                    <a class="btn btn-info" href="{{ route('ordenes.show', $t->id) }}">Atender</a>
                                    <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <a class="dropdown-item" href="#">
                                        <i class="fas fa-calendar-alt"></i> Reprogramar
                                        </a>
                                        <a class="dropdown-item" href="#">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @endif
                </table>
            </div>
        </div>
    </div>


</div>