<div>

    <div class="row d-flex justify-content-between" style="padding-top: 20px;">
        <div class="col-3 d-flex align-items-center">

            <button wire:click='change_day("yes")' class="btn btn-info btn-sm">
                <i class="fas fa-arrow-left"></i></button>
            <input type="date" wire:model="fecha" class="form-control">
            <button wire:click='change_day("tmw")' class="btn btn-info btn-sm"><i class="fas fa-arrow-right"></i></button>


        </div>
        <div class="col-3">
            <h1> <strong>{{ ucfirst(Carbon\Carbon::parse($fecha)->locale('es')->isoFormat('dddd DD ')) }} </strong></h1>
        </div>
        <div class="col-2 pt-2 mr-2">
            <!--             {{ $fecha }} -->
            <button type="button" class="btn btn-block btn-info" data-target="modal-default" wire:click="$dispatchTo('form-create-order', 'modal-order')">
                Nuevo Turno</button>
        </div>
    </div>



<div class="row pt-2">
        <!-- TABLA LUBRICENTRO -->
        <div class="col-6 card">
            <table class="table">
                <thead>
                    <span class="badge bg-orange" style="height: 40px;">
                        <h3><strong>Lubricentro </strong></h3>
                    </span>
                </thead>
                <thead>
                    <th>ORDEN</th>
                    <th>HORARIO</th>
                    <th>CLIENTE</th>
                    <th>VEHICULO</th>
                </thead>
                <tbody>
                    @foreach ($turnlub as $t)
                    <tr>
                        <td class="py-0">{{ $t->id }} </td>
                        <td class="py-0">{{ \Carbon\Carbon::parse($t->horario)->format('H:i') }} hs</td>
                        <td class="py-0">{{ $t->nombre . ' ' . $t->apellido }} </td>
                        <td class="py-0">{{
                        $t->vehiculos->modelos->descripcion.
                        ' ' .
                        $t->vehiculos->descripcion .
                        ' ' .
                        $t->vehiculos->año }}
                            <span class="badge bg-orange">{{ $t->vehiculos->dominio }}</span>
                        </td>
                        <td class="p-1"><a class="btn btn-secondary btn-sm" href="{{ route('ordenes.show', $t->id) }}">cargar</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- TABLA LAVADERO -->
            <div class="col-6 card">
                <table class="table">
                    <thead>
                        <span class="badge bg-primary" style="height: 40px;">
                            <h3><strong>Lavadero </strong></h3>
                        </span>
                    </thead>
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
                            <td class="py-0">{{ $t->nombre .' '. $t->apellido }}</td>
                            <td class="py-0">{{
                        $t->vehiculos->modelos->descripcion.
                        ' ' .
                        $t->vehiculos->descripcion .
                        ' ' .
                        $t->vehiculos->año }}
                                <span class="badge bg-primary">{{ $t->vehiculos->dominio }}</span>
                            </td>
                            <td class="py-1"><a class="btn btn-secondary btn-sm" href="{{ route('ordenes.show', $t->id) }}">cargar</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </div>


</div>
