<div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <table class="table table-striped">
                    <thead>
                        <th>Orden</th>
                        <th>Cliente</th>
                        <th>Medio de Pago</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($caja->pagos as $p)
                        <tr>
                            <td>{{ $p->facturas->orden_id }}</td>
                            <td>{{ $p->facturas->ordenes->clientes->perfiles->personas->nombre }}
                                {{ $p->facturas->ordenes->clientes->perfiles->personas->apellido }}
                            </td>
                            <td>{{ $p->facturas->pagos->first()->medios->descripcion ?? $p->facturas->pagos->first()->tipos->descripcion }}
                            </td>
                            <td>{{ $p->facturas->total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="card-header d-flex justify-content-end">
                    <h3> <strong> TOTAL ${{ $totalv }} </strong> </h3>
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div class="card">
                <table class="table table-striped">
                    <thead>
                        <th>Tipo</th>
<!--                         <th></th> -->
                        <th>Monto</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Transferencias</td>
                            <td> ${{ $pagosTrans->sum('total') }}</td>
                        <!--     <td></td> -->
                        </tr>

                        <tr>
                            <td>Efectivo</td>
                            <td> ${{ $pagosEfectivo->sum('total') }}</td>
                        <!--     <td></td> -->
                        </tr>

                    </tbody>
                </table>
                <div class="card-header d-flex justify-content-end">
                    <h3><strong> TOTAL ${{ $pagosEfectivo->sum('total') }} </strong> </h3>
                </div>
            </div>

        </div>
    </div>



</div>