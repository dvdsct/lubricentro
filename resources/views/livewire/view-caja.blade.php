<div>
    <div class="row">
        <div class="col-md-3">
            <a class="btn btn-app bg-warning btn-lg" style="width: 100%; height: 80px;cursor: pointer;"
                wire:click="$dispatchTo('add-presupuesto', 'addPresupuesto')"
                >
                <span class="badge bg-purple" style="font-size: 15px;">10</span>
                <i class="fas fa-edit"></i>
                <h4><strong> Presupuesto </strong></h4>
            </a>
        </div>

        <div class="col-md-3">
            <a class="btn btn-app bg-danger" style="width: 100%; height: 80px;cursor: pointer;"
            >
                <span class="badge bg-success" style="font-size: 15px;">4</span>
                <i class="fas fa-arrow-circle-down"></i>
                <h4><strong> Compra </strong></h4>
            </a>
        </div>

        <div class="col-md-3">
            <a class="btn btn-app bg-success" style="width: 100%; height: 80px;cursor: pointer;"
            wire:click="$dispatchTo('form-create-order', 'modal-order')"
            >
                <span class="badge bg-purple" style="font-size: 15px;">15</span>
                <i class="fas fa-arrow-circle-up"></i>
                <h4><strong> Venta </strong></h4>
            </a>
        </div>

        <div class="col-md-3">
            <a class="btn btn-app bg-info" style="width: 100%; height: 80px;cursor: pointer;"
            wire:click="$dispatchTo('form-create-order', 'modal-order')"
            >
                <span class="badge bg-danger" style="font-size: 15px;">20</span>
                <i class="fa fa-calendar"></i>
                <h4><strong> Turno </strong></h4>
            </a>
        </div>
    </div>



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

    @livewire('add-presupuesto')
    @livewire('form-create-order')


</div>
