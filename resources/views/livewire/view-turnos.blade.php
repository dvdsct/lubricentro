<div>

    <div class="row d-flex justify-content-between" style="padding-top: 20px;">
        <div class="col-md-3 d-flex align-items-center">
            <button wire:click='dispatch("change-yes")' class="btn btn-info btn-sm">
                <i class="fas fa-arrow-left"></i></button>
            <input type="date" wire:model.live="fecha" class="form-control">
            <button wire:click='dispatch("change-day")' class="btn btn-info btn-sm"><i class="fas fa-arrow-right"></i></button>
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
                        <th class="pr-0">ORDEN</th>
                        <th class="pr-0">HORARIO</th>
                        <th>CLIENTE</th>
                        <th>VEHICULO</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($turnlub as $t)
                        <tr>
                            <td class="py-0 pr-0">{{ $t->id }} </td>
                            <td class="py-0 pr-0">{{ \Carbon\Carbon::parse($t->horario)->format('H:i') }} hs</td>
                            <td class="py-0">
                                {{ $t->clientes->perfiles->personas->nombre . ' ' . $t->clientes->perfiles->personas->apellido }}
                            </td>
                            @if ($t->vehiculos != null)
                            <td class="py-0">
                                {{ $t->vehiculos->modelos->descripcion . ' ' . $t->vehiculos->descripcion . ' ' . $t->vehiculos->año }}
                                <span class="badge bg-orange">{{ $t->vehiculos->dominio }}</span>
                            </td>
                            @endif
                            <td class="p-1">
                                @if ($t->estado == '100')
                                <button class="btn btn-secondary" disabled >Atendido</button>
                                @else

                                <div class="btn-group">
                                    <a type="button" class="btn btn-info btn-sm" href="{{ route('ordenes.show', $t->id) }}">Atender</a>
                                    <button type="button" class="btn btn-danger btn-sm"  wire:click="cancelTurn('{{ $t->id }}')"><i class="fas fa-trash"></i></button>
                                </div>

<!--                                 <button class="btn btn-info btn-sm" href="{{ route('ordenes.show', $t->id) }}">Atender</button>
                                <div wire:click="cancelTurn('{{ $t->id }}')">
                                    <a class="btn btn-danger btn-sm" href="">
                                        <i class="fas fa-trash">
                                        </i>
                                    </a>
                                </div> -->
                                @endif
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
                            <td class="py-0">
                                {{ $t->clientes->perfiles->personas->nombre . ' ' . $t->clientes->perfiles->personas->apellido }}
                            </td>
                            @if ($t->vehiculos != null)
                            <td class="py-0">
                                {{ $t->vehiculos->modelos->descripcion . ' ' . $t->vehiculos->descripcion . ' ' . $t->vehiculos->año }}
                                <span class="badge bg-primary">{{ $t->vehiculos->dominio }}</span>
                            </td>
                            @endif
                            <td class="py-1">


                                <div class="btn-group">
                                    <a class="btn btn-info" href="{{ route('ordenes.show', $t->id) }}">Atender</a>

                                </div>
                                <div class="dropdown-item" {{ $des }} wire:click="cancelTurn('8')">
                                    <i class="fas fa-trash-alt"></i> Eliminar

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
    @livewire('form-create-order', ['fecha' => $fecha])



</div>
