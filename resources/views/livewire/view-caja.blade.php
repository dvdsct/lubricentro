<div>
    <div class="row">
        <div class="col-md-3 pl-0">
            <a class="btn btn-app bg-warning btn-lg" style="width: 100%; height: 80px;cursor: pointer;" wire:click="$dispatchTo('add-presupuesto', 'addPresupuesto')">
                <span class="badge bg-purple" style="font-size: 15px;">10</span>
                <i class="fas fa-edit"></i>
                <h4><strong> Presupuestos </strong></h4>
            </a>
        </div>

        <div class="col-md-3 pr-3">
            <a class="btn btn-app bg-purple" style="width: 100%; height: 80px;cursor: pointer;" wire:click="$dispatchTo('compra-caja', 'modal-compra')">
                <span class="badge bg-success" style="font-size: 15px;">4</span>
                <i class="fas fa-arrow-circle-down"></i>
                <h4><strong> Compras </strong></h4>
            </a>
        </div>

        <div class="col-md-3 pl-0">
            <a class="btn btn-app bg-success" style="width: 100%; height: 80px;cursor: pointer;" wire:click="$dispatchTo('form-create-order', 'modal-order')">
                <span class="badge bg-danger" style="font-size: 15px;">15</span>
                <i class="fas fa-arrow-circle-up"></i>
                <h4><strong> Ventas </strong></h4>
            </a>
        </div>

        <div class="col-md-3 pr-3">
            <a class="btn btn-app bg-info" style="width: 100%; height: 80px;cursor: pointer;" wire:click="$dispatchTo('form-create-order', 'modal-order')">
                <i class="fa fa-calendar"></i>
                <h4><strong> Turnos </strong></h4>
            </a>
        </div>
    </div>



    <div class="row">

        <!-- TABLA DE LA IZQUIERDA  -->
        <div class="col-md-6">
            <div class="card">
                <table class="table table-striped">
                    <thead style="height: 10px;">
                        <h5 style="text-align: center; margin-top: 10px;"> <span> <strong> MOVIMIENTOS </strong> </span> </h5>
                    </thead>
                    <thead>
                        <th>Hora</th>
                        <th>Tipo</th>
                        <th>Medio de Pago</th>
                        <th>N° Transaccion</th>
                    </thead>
                    <tbody>
                        <tr>
                        <td>{{ $caja->created_at->format('H:i:s') }}</td>
                            <td>Apertura</td>
                            <td>Monto Inicial</td>
                            <td></td>
                        </tr>
                        @foreach ($pagos as $p)
                        <tr>
                            <td>{{ $p->facturas->estado }}</td>
                            <td>{{ $p->facturas->orden_id }}</td>
                            <td>{{ $p->facturas->ordenes->clientes->perfiles->personas->nombre  ?? $p->facturas->ordenes->proveedores->perfiles->personas->nombre ?? ''}}
                                {{ $p->facturas->ordenes->clientes->perfiles->personas->apellido  ?? $p->facturas->ordenes->proveedores->perfiles->personas->apellido ?? ''}}
                            </td>
                            <td>{{ $p->facturas->pagos->first()->medios->descripcion ?? $p->facturas->pagos->first()->tipos->descripcion }}
                            </td>
                                       <td>{{ $p->facturas->total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-header d-flex justify-content-end">
                 <!--    <h3> <strong> TOTAL ${{ $totalv }} </strong> </h3> -->
                </div>
            </div>
        </div>

        <!-- TABLA DERECHA  -->
        <div class="col-md-6">
            <div class="card">
                <table class="table table-striped">
                    <thead style="height: 10px;">
                        <h5 style="text-align: center; margin-top: 10px;"> <span> <strong> CAJA </strong> </span> </h5>
                    </thead>
                    <thead>
                        <th>Tipo</th>
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

                        <tr>
                            <td>Cheques</td>
                            <td> ${{ $pagosCheques->sum('total') }}</td>
                            <!--     <td></td> -->
                        </tr>
                        <tr>
                            <td>Monto Inicila</td>
                            <td> ${{ $montoInicial }}</td>
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

    <!-- BOTON PARA CERRAR CAJA  -->
    <div class="row mr-1" style="display: flex; justify-content: end;">
        <div class="info-box bg-danger d-flex align-items-center justify-content-center" wire:click='$dispatchTo("form-pago","formPago",{ tipo: "orden" })' style="cursor: pointer; width: 25%;">
            <span class="info-box-icon"> <i class="fas fa-cash-register"></i> </span>
            <div class="info-box-content">
                <h4 class="info-box-text m-0"> <strong> Cerrar Caja </strong> </h4>
            </div>
        </div>
    </div>

    @livewire('add-presupuesto')
    @livewire('form-create-order')
    {{-- @livewire('compra-caja') --}}



</div>
