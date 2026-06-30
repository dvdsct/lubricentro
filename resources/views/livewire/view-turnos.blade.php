<div x-data="{
    confirmCancel(ordenId) {
        Swal.fire({
            title: '¿Cancelar orden?',
            text: 'Se devolverá el stock de los ítems no provisionales y la orden quedará cancelada.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'No, volver'
        }).then((result) => {
            if (result.isConfirmed && ordenId) {
                $wire.cancelTurn(ordenId);
            }
        });
    }
}" @confirm-cancel.window="confirmCancel($event.detail.ordenId)">

    <div class="row d-flex justify-content-between align-items-center" style="padding-top: 20px;">
        <div class="col-md-3 d-flex align-items-center">
            <button wire:click='dispatch("change-yes")' class="btn btn-info btn-sm">
                <i class="fas fa-arrow-left"></i></button>
            <input type="date" wire:model.live="fecha" class="form-control mx-1">
            <button wire:click='dispatch("change-day")' class="btn btn-info btn-sm"><i
                    class="fas fa-arrow-right"></i></button>
        </div>

        <!-- FECHA DEL MEDIO -->
        <div class="col-md-4 text-center">
            <h1 class="mb-0" style="font-size: 1.8rem; font-weight: bold;">
                @if ($vista === 'semanal')
                    Semana del {{ $semanaRango }}
                @else
                    {{ ucfirst(Carbon\Carbon::parse($fecha)->locale('es')->isoFormat('dddd DD ')) }}
                @endif
            </h1>
        </div>

        <!-- SELECTOR DE FORMATO (DIARIO/SEMANAL) -->
        <div class="col-md-3 d-flex justify-content-center">
            <div class="btn-group btn-group-toggle shadow-sm">
                <button wire:click="$set('vista', 'diario')" class="btn btn-sm {{ $vista === 'diario' ? 'btn-info active font-weight-bold' : 'btn-outline-info' }}">
                    Diario
                </button>
                <button wire:click="$set('vista', 'semanal')" class="btn btn-sm {{ $vista === 'semanal' ? 'btn-info active font-weight-bold' : 'btn-outline-info' }}">
                    Semanal
                </button>
            </div>
        </div>

        <!-- BOTON PARA GENERAR NUEVO TURNO -->
        <div class="pt-2 col-md-2 text-right">
            @can('caja')
                <button type="button" class="btn btn-block btn-info shadow-sm" data-target="modal-default"
                    wire:click="$dispatchTo('form-create-order', 'modal-order')">
                    <i class="fas fa-plus-circle"></i> Nuevo Turno</button>
            @endcan
        </div>
    </div>

    <div class="pt-2 row">
        <!-- TABLA LUBRICENTRO -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <table class="table table-hover table-striped">
                    <thead>
                        <span class="badge bg-orange w-100 py-2" style="font-size: 1.1rem;">
                            <strong>Lubricentro </strong>
                        </span>
                    </thead>
                    @if ($turnlub->isEmpty())
                        <h6 class="pt-3 pl-3 font-italic text-muted"> Aun no hay turnos asignados para {{ $vista === 'semanal' ? 'esta semana' : 'este día' }}!</h6>
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
                                    <td class="py-2 pr-0 align-middle">{{ $t->id }} </td>
                                    <td class="py-2 pr-0 align-middle">
                                        @if($vista === 'semanal')
                                            <span class="text-capitalize text-secondary font-weight-bold mr-1">
                                                {{ \Carbon\Carbon::parse($t->fecha_turno)->locale('es')->isoFormat('ddd D') }}
                                            </span>
                                        @endif
                                        <span class="font-weight-bold">{{ \Carbon\Carbon::parse($t->horario)->format('H:i') }}</span> hs
                                    </td>
                                    <td class="py-2 align-middle">
                                        {{ $t->clientes->perfiles->personas->nombre . ' ' . $t->clientes->perfiles->personas->apellido }}
                                    </td>
                                    <td class="py-2 align-middle">
                                        @if ($t->vehiculos != null)
                                            {{ $t->vehiculos->modelos->descripcion . ' ' . $t->vehiculos->descripcion . ' ' . $t->vehiculos->año }}
                                            <span class="badge bg-orange">{{ $t->vehiculos->dominio }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="p-1 align-middle text-right">
                                        @if ($t->estado == '100')
                                            <a type="button" class="btn btn-info btn-sm"
                                                href="{{ route('ordenes.show', $t->id) }}"><strong> IR </strong></a>
                                            <button class="btn btn-secondary btn-sm" disabled>Atendido</button>
                                        @elseif ($t->estado == '700')
                                            <button class="btn btn-secondary btn-sm" disabled>Cancelado</button>
                                        @else
                                            <div class="btn-group">
                                                <a type="button" class="btn btn-info btn-sm"
                                                    href="{{ route('ordenes.show', $t->id) }}"><strong> IR </strong></a>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    wire:click="$dispatch('confirm-cancel', { ordenId: {{ $t->id }} })"><i
                                                        class="fas fa-trash"></i></button>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    wire:click="reprTurn('{{ $t->id }}')"><i class="far fa-clock"></i></button>
                                            </div>
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
            <div class="card shadow-sm">
                <table class="table table-hover table-striped">
                    <thead>
                        <span class="badge bg-primary w-100 py-2" style="font-size: 1.1rem;">
                            <strong>Lavadero </strong>
                        </span>
                    </thead>
                    @if ($turnlav->isEmpty())
                        <h6 class="pt-3 pl-3 font-italic text-muted"> Aun no hay turnos asignados para {{ $vista === 'semanal' ? 'esta semana' : 'este día' }}!</h6>
                    @else
                        <thead>
                            <th class="pr-0">ORDEN</th>
                            <th class="pr-0">HORARIO</th>
                            <th>CLIENTE</th>
                            <th>VEHICULO</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($turnlav as $t)
                                <tr>
                                    <td class="py-2 pr-0 align-middle">{{ $t->id }}</td>
                                    <td class="py-2 pr-0 align-middle">
                                        @if($vista === 'semanal')
                                            <span class="text-capitalize text-secondary font-weight-bold mr-1">
                                                {{ \Carbon\Carbon::parse($t->fecha_turno)->locale('es')->isoFormat('ddd D') }}
                                            </span>
                                        @endif
                                        <span class="font-weight-bold">{{ \Carbon\Carbon::parse($t->horario)->format('H:i') }}</span> hs
                                    </td>
                                    <td class="py-2 align-middle">
                                        {{ $t->clientes->perfiles->personas->nombre . ' ' . $t->clientes->perfiles->personas->apellido }}
                                    </td>
                                    <td class="py-2 align-middle">
                                        @if ($t->vehiculos != null)
                                            {{ $t->vehiculos->modelos->descripcion . ' ' . $t->vehiculos->descripcion . ' ' . $t->vehiculos->año }}
                                            <span class="badge bg-primary">{{ $t->vehiculos->dominio }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="p-1 align-middle text-right">
                                        @if ($t->estado == '100')
                                            <div class="btn-group">
                                                <a type="button" class="btn btn-info btn-sm"
                                                    href="{{ route('ordenes.show', $t->id) }}"><strong> IR </strong></a>
                                                <button class="btn btn-secondary btn-sm" disabled>Atendido</button>
                                            </div>
                                        @elseif ($t->estado == '700')
                                            <button class="btn btn-secondary btn-sm" disabled>Cancelado</button>
                                        @else
                                            <div class="btn-group">
                                                <a type="button" class="btn btn-info btn-sm"
                                                    href="{{ route('ordenes.show', $t->id) }}"><strong> IR </strong></a>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    wire:click="$dispatch('confirm-cancel', { ordenId: {{ $t->id }} })"><i
                                                        class="fas fa-trash"></i></button>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    wire:click="reprTurn('{{ $t->id }}')"><i class="far fa-clock"></i></button>
                                            </div>
                                        @endif
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
