<div>

    <div class="row">
        <div class="col-6">
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
                                {{ $p->facturas->ordenes->clientes->perfiles->personas->apellido }}</td>
                            <td>{{ $p->facturas->pagos->first()->medios->descripcion ?? $p->facturas->pagos->first()->tipos->descripcion }}
                            </td>
                            <td>{{ $p->facturas->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end">
                <h2>TOTAL {{ $totalv }}</h2>
            </div>
        </div>
        <div class="col-6">

            <table class="table table-striped">
                <thead>
                    <th>Tipo</th>
                    <th></th>
                    <th>Monto</th>
                </thead>
                <tbody>
                    <tr>
                        <td>Transferencias</td>
                        <td class="d-flex justify-content-end">{{ $pagosTrans->sum('total') }}</td>
                        <td></td>
                    </tr>

                    <tr >
                        <td>Efectivo</td>
                        <td class="d-flex justify-content-end">{{ $pagosEfectivo->sum('total') }}</td>
                        <td></td>
                    </tr>

                </tbody>
            </table>

            <div class="d-flex justify-content-end">
                <h2>TOTAL {{ $pagosEfectivo->sum('total') }}</h2>
            </div>
        </div>
    </div>



</div>
