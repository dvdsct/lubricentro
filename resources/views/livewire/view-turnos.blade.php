<div>

    <div class="row d-flex justify-content-between" style="padding-top: 20px;">
        <div class="col-3 d-flex align-items-center">

            <button wire:click='change_day("yes")' class="btn btn-info btn-sm">
                <i class="fas fa-arrow-left"></i></button>
            <input type="date" wire:model="fecha" class="form-control">
            <button wire:click='change_day("tmw")' class="btn btn-info btn-sm"><i
                    class="fas fa-arrow-right"></i></button>


        </div>
        <div class="col-3">
            <h1> <strong>{{ ucfirst(Carbon\Carbon::now()->locale('es')->isoFormat('dddd DD ')) }} </strong></h1>
        </div>
        <div class="col-2 pt-2 mr-2">
            <button type="button" class="btn btn-block btn-info" data-target="modal-default" wire:click='openModal'>
                Nuevo Turno</button>
        </div>
    </div>
    <div class="row">

        <div class="col-6">
            <table class="table">
                <thead>Lubricentro</thead>
                <thead>
                    <th>HORARIO</th>
                    <th>CLIENTE</th>
                    <th>VEHICULO</th>
                </thead>
                <tbody>
                    @foreach ($turnlub as $t)
                        <tr>
                            <td><span class="badge bg-danger">{{ $t->horario }}aaa</span></td>
                            <td>{{ $t->clientes->perfiles->personas->nombre . ' ' . $t->clientes->perfiles->personas->apellido }}
                            </td>
                            <td>{{ $t->vehiculos->modelos->marcas->descripcion .
                                ' ' .
                                $t->vehiculos->modelos->descripcion .
                                ' ' .
                                $t->vehiculos->año }}
                            </td>

                        </tr>
                        <tr>
                    @endforeach

                </tbody>
                </tbody>
            </table>
        </div>



        <div class="col-6">
            <table class="table">
                <thead>Lavadero</thead>
                <thead>
                    <th>HORARIO</th>
                    <th>CLIENTE</th>
                    <th>VEHICULO</th>
                </thead>

                <tbody>
                    @foreach ($turnlav as $t)
                        <tr>
                            <td><span class="badge bg-danger">{{ $t->horario }}1313</span></td>
                            <td>{{ $t->clientes->perfiles->personas->nombre . ' ' . $t->clientes->perfiles->personas->apellido }}
                            </td>
                            <td>{{ $t->vehiculos->modelos->marcas->descripcion .
                                ' ' .
                                $t->vehiculos->modelos->descripcion .
                                ' ' .
                                $t->vehiculos->año }}
                            </td>
                            </td>

                        </tr>
                        <tr>
                    @endforeach

                </tbody>



            </table>
        </div>


    </div>
</div>
