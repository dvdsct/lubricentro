<!-- VISTA FACTURACION -->
<div>
    <div class="row">
        <!-- NUEVO CARD DE ESTADISTICAS DE GASTOS -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box bg-gradient-warning">
                <span class="info-box-icon"><i class="fa fa-calculator"></i></span>
                <div class="info-box-content">
                    <h5> <strong> <span class="info-box-text">GASTOS</span> </strong> </h5>
                    <h5> <strong> <span class="info-box-number">${{ number_format((float)$gastos, 2, '.', '') }}</span> </strong></h5>
                </div>
            </div>
        </div>


        <!-- NUEVO CARD DE ESTADISTICAS DE VENTAS -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box bg-gradient-success">
                <span class="info-box-icon"><i class="fa fa-handshake"></i></span>
                <div class="info-box-content">
                    <h5> <strong> <span class="info-box-text">VENTAS</span> </strong> </h5>
                    <h5> <strong> <span class="info-box-number">${{ number_format((float)$venta, 2, '.', '') }}</span> </strong></h5>
                </div>
            </div>
        </div>



        <div class="col-12 col-sm-6 col-md-3" style="cursor: pointer;"
            wire:click="$dispatchTo('compra-caja', 'modal-compra')">
            <div class="mb-3 info-box">
                <span class="info-box-icon bg-purple elevation-1"><i class="fas fa-cash-register"></i></span>
                <div class="info-box-content">
                    <h5> <strong> <span class="info-box-text">INGRESO/ <br> EXTRACCION</span> </strong> </h5>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3" style="cursor: pointer;"
            wire:click="$dispatchTo('form-create-order', 'modal-order')">
            <div class="mb-3 info-box">
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
                            <td> ${{ number_format((float)$montoInicial, 2, '.', '') }}</td>
                        </tr>
                        @foreach ($pagos as $p)
                            @if ($p->facturas->pagos->first()->estado != '400')
                                {{-- $p->facturas->orden_id --}}

                                <tr>
                                    <td>{{ $p->facturas->created_at->format('H:i') }} Hs.</td>
                                    <td>
                                        @if ($p->in_out == 'in')
                                            @if ($p->facturas->pagos->first()->concepto == 'Lubricentro' || $p->facturas->pagos->first()->concepto == 'Lavadero')
                                                <a href="{{ route('ordenes.show', $p->facturas->orden_id) ?? '' }}">
                                            @endif

                                            <span class="badge badge-success">Ingreso</span>
                                        @elseif($p->in_out == 'out')
                                            <span class="badge badge-danger">Egreso</span>
                                        @endif
                                    </td>
                                    <td>{{ $p->medios->descripcion ?? $p->tipos->descripcion }}
                                    </td>
                                    <td>{{ $p->facturas->pagos->first()->concepto }}</td>
                                    <td>$ {{ number_format((float)$p->total, 2, '.', '') }}</td>


                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="card-header d-flex justify-content-end">
                    <h3><strong> SUBTOTAL ${{ number_format((float)$totalv, 2, '.', '') }} </strong> </h3>
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
                        <th class="mr-2 d-flex justify-content-end">Monto</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>SUBTOTAL</strong></td>
                            <td class="mr-2 d-flex justify-content-end"> ${{ number_format((float)$totalv, 2, '.', '') }}</td>
                        </tr>

                        <tr>
                            <a class="aa" href="pagos-transferencia/{{ $caja->id }}">
                                <td>Transferencias</td>
                                <td class="mr-2 d-flex justify-content-end"> ${{ number_format((float)$pagosTrans, 2, '.', '') }}</td>
                            </a>
                        </tr>

                        <tr>
                            <td>Tarjetas</td>
                            <td class="mr-2 d-flex justify-content-end"> ${{ number_format((float)$pagosTarjeta, 2, '.', '') }}</td>
                        </tr>

                        <tr>
                            <td>Cheques</td>
                            <td class="mr-2 d-flex justify-content-end"> ${{ number_format((float)$pagosCheques, 2, '.', '') }}</td>

                        </tr>
                        <tr>
                            <td>Cuenta Corriente</td>
                            <td class="mr-2 d-flex justify-content-end"> ${{ number_format((float)$pagosCtaCte, 2, '.', '') }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="card-header d-flex justify-content-end">
                    <h3><strong> TOTAL ${{ number_format((float)$totalEfectivo, 2, '.', '') }} </strong> </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- BOTON PARA CERRAR CAJA  -->
    <div class="row" style="display: flex; justify-content: end;">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger" style="cursor: pointer;"
                @can('caja')
                    wire:click='$dispatchTo("cerrar-caja","cerrar-caja-modal")'
                    @endcan>
                <div class="inner">
                    <h3 class="m-0">Cerrar caja</h3>
                    <p>Total del dia ${{ number_format((float)$totalEfectivo, 2, '.', '') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cash-register"></i>
                </div>
            </div>
        </div>
    </div>
    @can('caja')
        @livewire('add-presupuesto')
        @livewire('form-create-order', ['fecha' => $fecha])
        @livewire('compra-caja', ['caja' => $caja])
        @livewire('cerrar-caja', ['caja' => $caja])
    @endcan
</div>
