<div>

    <div class="row d-flex justify-content-between" style="padding-top: 20px;">
        <div class="col-md-3 d-flex align-items-center">
            <button wire:click='dispatch("change-yes")' class="btn btn-info btn-sm">
                <i class="fas fa-arrow-left"></i></button>
            <input type="date" wire:model.live="fecha" class="form-control">
            <button wire:click='dispatch("change-day")' class="btn btn-info btn-sm"><i
                    class="fas fa-arrow-right"></i></button>
        </div>

        <!-- FECHA DEL MEDIO -->
        <div class="col-md-3">
            <h1 style="text-align: center;">
                <strong>{{ ucfirst(Carbon\Carbon::parse($fecha)->locale('es')->isoFormat('dddd DD ')) }} </strong>
            </h1>
        </div>

        <!-- BOTON PARA GENERAR NUEVO TURNO -->
        <div class="pt-2 col-md-2">
            @can('caja')
                <button type="button" class="btn btn-block btn-info" data-target="modal-default"
                    wire:click="$dispatchTo('form-create-order', 'modal-order')">
                    <i class="fas fa-plus-circle"></i> Nuevo Turno</button>
            @endcan
        </div>
    </div>

    <div class="pt-2 row">
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
                        <h6 class="pt-2 pl-3 font-italic"> Aun no hay turnos asignados para este día!</h6>
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
                                    <td class="py-0 pr-0">{{ \Carbon\Carbon::parse($t->horario)->format('H:i') }} hs
                                    </td>
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
                                            <a type="button" class="btn btn-info btn-sm"
                                                href="{{ route('ordenes.show', $t->id) }}"><strong> IR </strong></a>
                                            <button class="btn btn-secondary" disabled>Atendido</button>
                                        @elseif ($t->estado == '700')
                                            <button class="btn btn-secondary" disabled>Cancelado</button>
                                        @else
                                            <div class="btn-group">
                                                <a type="button" class="btn btn-info btn-sm"
                                                    href="{{ route('ordenes.show', $t->id) }}"><strong> IR </strong></a>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    wire:click="cancelTurn('{{ $t->id }}')"><i
                                                        class="fas fa-trash"></i></button>
                                            </div>
                                        @endif
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-danger btn-sm"
                                            wire:click="reprTurn('{{ $t->id }}')"><i
                                            class="far fa-clock"></i> </button>
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
                        <h6 class="pt-2 pl-3 font-italic"> Aun no hay turnos asignados para este día!</h6>
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
                                    <td class="p-1">

                                        @if ($t->estado == '100')
                                            <div class="btn-group">
                                                <a type="button" class="btn btn-info btn-sm"
                                                    href="{{ route('ordenes.show', $t->id) }}"><strong> IR
                                                    </strong></a>
                                                <button class="btn btn-secondary" disabled>Atendido</button>
                                            </div>
                                        @elseif ($t->estado == '700')
                                            <button class="btn btn-secondary" disabled>Cancelado</button>
                                        @else
                                            <div class="btn-group">
                                                <a type="button" class="btn btn-info btn-sm"
                                                    href="{{ route('ordenes.show', $t->id) }}"><strong> IR
                                                    </strong></a>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    wire:click="cancelTurn('{{ $t->id }}')"><i
                                                        class="fas fa-trash"></i></button>
                                            </div>
                                        @endif
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-danger btn-sm"
                                                wire:click="reprTurn('{{ $t->id }}')"><i
                                                    class="far fa-clock"></i> </button>
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

    @if ($reprogramar)

    <div class="modal fade show" id="modal-default"
        style="display: block; background-color: rgba(0, 0, 0, 0.5);" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title"> <strong> ORDEN</strong> </h4>
                    <button type="button" class="close"  wire:click="reprTurn('{{ $t->id }}')">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <!-- SI EL CLIENTE NO EXISTE - CREAR NUEVO CLIENTE -->
                <div class="modal-body">
                    <!-- FECHA Y HORA DEL TURNO -->
                    <div class="row  d-flex justify-content-between">
                        <h4 class="pl-2"> <strong> FECHA Y HORA </strong> </h4>
                    </div>
                    <div class="px-3 d-flex justify-content-center pb-1">
                        <div class="row">
                            <div class="d-flex justify-content-between">
                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group input-group-lg">
                                        <input type="date" class="form-control"
                                            aria-label="Sizing example input"
                                            aria-describedby="inputGroup-sizing-lg" wire:model='fecha'>
                                    </div>
                                </div>

                                <div class="col-md-6 col-xs-12">
                                    <div class="input-group input-group-lg">
                                        <input type="time" class="form-control"
                                            aria-label="Sizing example input"
                                            aria-describedby="inputGroup-sizing-lg" wire:model='horario'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <!-- FOOTER DEL MODAL -->
                <div class="modal-footer justify-content-between pb-4">
                    <button type="button" class="btn btn-default"  wire:click="reprTurn('{{ $t->id }}')">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click='reproTurno'>Guardar</button>
                </div>
            </div>
        </div>
    </div>

    @endif



    @livewire('form-create-order', ['fecha' => $fecha])

</div>
