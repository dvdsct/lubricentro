<!-- VISTA FACTURACION -->
<div>
    <div class="row">
        <!-- NUEVO CARD DE ESTADISTICAS DE GASTOS -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box bg-gradient-warning">
                <span class="info-box-icon"><i class="fa fa-calculator"></i></span>
                <div class="info-box-content">
                <h5> <strong> <span class="info-box-text">GASTOS</span> </strong> </h5>
                <h5> <strong> <span class="info-box-number">${{ $gastos }}</span> </strong></h5>
                </div>
            </div>
        </div>


        <!-- NUEVO CARD DE ESTADISTICAS DE VENTAS -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box bg-gradient-success">
                <span class="info-box-icon"><i class="fa fa-handshake"></i></span>
                <div class="info-box-content">
                <h5> <strong> <span class="info-box-text">VENTAS</span> </strong> </h5>
                <h5> <strong> <span class="info-box-number">${{ $venta }}</span> </strong></h5>
                </div>
            </div>
        </div>



        <div class="col-12 col-sm-6 col-md-3" style="cursor: pointer;" wire:click="$dispatchTo('compra-caja', 'modal-compra')">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-purple elevation-1"><i class="fas fa-share"></i></span>
                <div class="info-box-content">
                    <h5> <strong> <span class="info-box-text">REALIZAR <br> EXTRACCION</span> </strong> </h5>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3" style="cursor: pointer;" wire:click="$dispatchTo('form-create-order', 'modal-order')">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-calendar-alt"></i>
                </span>
                <div class="info-box-content">
                    <h5> <strong> <span class="info-box-text">GENERAR <br> TURNO</span> </strong> </h5>
                    <!-- <h5> <span class="info-box-number">{{ count($ventaTotal) }}</span> </h5> -->
                </div>
            </div>
        </div>

    </div>


    <div class="row">
        <!-- TABLA DE LA IZQUIERDA  -->
        <div class="col-md-6">
            <div class="card">
                <table class="table table-striped">
                    <thead style="background-color: #007bff; color: white;">
                        <tr>
                            <th colspan="5" style="text-align: center; padding: 10px 0;">
                                <h4 style="margin: 0;"> <strong>MOVIMIENTOS</strong> </h4>
                            </th>
                        </tr>
                    </thead>
                    <thead>
                        <th>Hora</th>
                        <th>Tipo</th>
                        <th>Medio de Pago</th>
                        <th>Concepto</th>
                        <th>Monto</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $caja->created_at->format('H:i') }} Hs.</td>
                            <td> <span class="badge badge-primary"> Apertura</span> </td>
                            <td>Efectivo</td>
                            <td>Monto incial</td>
                            <td> ${{ $montoInicial }}</td>
                        </tr>
                        @foreach ($pagos as $p)
                        <tr>
                            <td>{{ $p->facturas->created_at->format('H:i') }} Hs.</td>
                            <td>
                                @if($p->in_out == 'in')
                                <span class="badge badge-success">Ingreso</span>
                                @elseif($p->in_out == 'out')
                                <span class="badge badge-danger">Egreso</span>
                                @else
                                @endif
                            </td>
                            <td>{{ $p->facturas->pagos->first()->medios->descripcion ?? $p->facturas->pagos->first()->tipos->descripcion }}</td>
                            <td>{{ $p->facturas->pagos->first()->concepto}}</td>
                            <td>$ {{ $p->facturas->total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-header d-flex justify-content-end">
                    <h3><strong> SUBTOTAL ${{ $totalv }} </strong> </h3>
                </div>
            </div>
        </div>

        <!-- TABLA DERECHA  -->
        <div class="col-md-6">
            <div class="card">
                <table class="table table-striped">
                    <thead style="background-color: #007bff; color: white;">
                        <tr>
                            <th colspan="2" style="text-align: center; padding: 10px 0;">
                                <h4 style="margin: 0;"> <strong>CAJA</strong> </h4>
                            </th>
                        </tr>
                    </thead>
                    <thead>
                        <th>Tipo</th>
                        <th class="d-flex justify-content-end mr-2">Monto</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>SUBTOTAL</strong></td>
                            <td class="d-flex justify-content-end mr-2"> ${{ $totalv }}</td>
                        </tr>

                        <tr>
                            <td>Transferencias</td>
                            <td class="d-flex justify-content-end mr-2"> ${{ $pagosTrans }}</td>
                        </tr>

                        <tr>
                            <td>Tarjetas</td>
                            <td class="d-flex justify-content-end mr-2"> ${{ $pagosTarjeta }}</td>
                        </tr>

                        <tr>
                            <td>Cheques</td>
                            <td class="d-flex justify-content-end mr-2"> ${{ $pagosCheques }}</td>

                        </tr>
                        <tr>
                            <td>Cuenta Corriente</td>
                            <td class="d-flex justify-content-end mr-2"> ${{ $pagosCtaCte }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="card-header d-flex justify-content-end">
                    <h3><strong> TOTAL ${{ $totalEfectivo }} </strong> </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- BOTON PARA CERRAR CAJA  -->
    <div class="row" style="display: flex; justify-content: end;">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger" style="cursor: pointer;" wire:click='$dispatchTo("cerrar-caja","cerrar-caja-modal")'>
                <div class="inner">
                    <h3 class="m-0">Cerrar caja</h3>
                    <p>Total del dia ${{ $totalEfectivo }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cash-register"></i>
                </div>
            </div>
        </div>
    </div>
    @can('caja')
    @livewire('add-presupuesto')
    @livewire('form-create-order',['fecha' => $fecha])
    @livewire('compra-caja', ['caja' => $caja])
    @livewire('cerrar-caja', ['caja' => $caja])
    @endcan
</div>