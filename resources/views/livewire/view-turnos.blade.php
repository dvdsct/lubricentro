<div>
    <div class="row">
        <div class="col-6">
            <table>
                <thead>Lavadero</thead>
                <thead>
                    <th>HORARIO</th>
                    <th>CLIENTE</th>
                    <th>VEHICULO</th>
                </thead>

                <tbody>
                    @foreach ($turnlav as $t)
                    <tr>
                        <td>{{ $t->clientes->perfiles->personas->nombre .' '. $t->clientes->perfiles->personas->apellido }}</td>
                        <td>{{ $t->vehiculos->modelos->marcas->descripcion .' ', $t->vehiculos->modelos->descripcion}}</td>
                        <td>
                            <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-danger">{{ $t->horario  }}</span></td>
                    </tr>
                    <tr>

                    @endforeach

                </tbody>



            </table>
        </div>
        <div class="col-6">
            <table>
                    <thead>Lubricentro</thead>
                <thead>
                    <th>HORARIO</th>
                    <th>CLIENTE</th>
                    <th>VEHICULO</th>
                </thead>
                <tbody>
                    @foreach ($turnlav as $t)
                    <tr>
                        <td>{{ $t->clientes->perfiles->personas->nombre .' '. $t->clientes->perfiles->personas->apellido }}</td>
                        <td>{{ $t->vehiculos->modelos->marcas->descripcion .' ', $t->vehiculos->modelos->descripcion}}</td>
                        <td>
                            <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-danger">{{ $t->horario }}</span></td>
                    </tr>
                    <tr>

                    @endforeach

                </tbody>
                </tbody>
            </table>
        </div>

    </div>
</div>
