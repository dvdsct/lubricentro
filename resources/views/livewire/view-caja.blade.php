<!-- VISTA FACTURACION -->
<div>
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3" style="cursor: pointer;"
            >
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fa fa-calculator"
                        aria-hidden="true"></i></span>
                <div class="info-box-content">
                    <h5> <strong> <span class="info-box-text">Gastos</span> </strong> </h5>
                    <h5> <span class="info-box-number">${{ $gastos }}</span> </h5>
                </div>
            </div>
        </div>





        <div class="col-12 col-sm-6 col-md-3" style="cursor: pointer;"
            >
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fa fa-usd-o" aria-hidden="true"></i></span>
                <div class="info-box-content">
                    <h5> <strong> <span class="info-box-text">VENTAS</span> </strong> </h5>
                    <h5> <span class="info-box-number">${{ $venta }}</span> </h5>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3" style="cursor: pointer;"
            wire:click="$dispatchTo('compra-caja', 'modal-compra')">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-purple elevation-1"><i class="fas fa-shopping-cart"></i></span>
                <div class="info-box-content">
                    <h5> <strong> <span class="info-box-text">COMPRAS</span> </strong> </h5>
                    <h5> <span class="info-box-number"></span> </h5>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3" style="cursor: pointer;"
            wire:click="$dispatchTo('form-create-order', 'modal-order')">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-info elevation-1"><i class="fa fa-calendar-check"
                        aria-hidden="true"></i></span>
                <div class="info-box-content">
                    <h5> <strong> <span class="info-box-text">TURNOS</span> </strong> </h5>
                    <h5> <span class="info-box-number">{{ count($ventaTotal) }}</span> </h5>
                </div>
            </div>
        </div>

    </div>


    <div class="row">
        <!-- TABLA DE LA IZQUIERDA  -->
        <div class="col-md-6">
            <div class="card">
                <table class="table table-striped">
                    <thead style="height: 10px;">
                        <h5 style="text-align: center; margin-top: 10px;"> <span> <strong> MOVIMIENTOS </strong> </span>
                        </h5>
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
                            <td>{{ $caja->created_at->format('H:i') }}</td>
                            <td>Apertura</td>
                            <td>-</td>
                            <td>Monto incial Efectivo</td>
                            <th> ${{ $montoInicial }}</th>
                        </tr>
                        @foreach ($pagos as $p)
                            <tr>
                                <td>{{ $p->facturas->created_at->format('H:i') }}</td>
                                <td>{{ $p->facturas->orden_id }}</td>
                                <td>{{ $p->facturas->pagos->first()->medios->descripcion ?? $p->facturas->pagos->first()->tipos->descripcion }}
                                </td>
                                <td>{{ $p->facturas->pagos->first()->concepto }}</td>
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
                    <thead style="height: 10px;">
                        <h5 style="text-align: center; margin-top: 10px;"> <span> <strong> CAJA </strong> </span> </h5>
                    </thead>
                    <thead>
                        <th>Tipo</th>
                        <th></th>
                        <th class="d-flex justify-content-end mr-2">Monto</th>
                    </thead>
                    <tbody>



                        <tr>
                            <td><strong>SUTOTAL</strong></td>
                            <td></td>
                            <td class="d-flex justify-content-end mr-2"> ${{ $totalv }}</td>
                        </tr>

                        <tr>
                            <td>Transferencias</td>
                            <td></td>
                            <td class="d-flex justify-content-end mr-2"> ${{ $pagosTrans }}</td>
                        </tr>

                        <tr>
                            <td>Tarjetas</td>
                            <td></td>
                            <td class="d-flex justify-content-end mr-2"> ${{ $pagosTarjeta }}</td>
                        </tr>

                        <tr>
                            <td>Cheques</td>
                            <td></td>
                            <td class="d-flex justify-content-end mr-2"> ${{ $pagosCtaCte }}</td>
                        </tr>
                        <tr>
                            <td>Cuenta Corriente</td>
                            <td></td>
                            <td class="d-flex justify-content-end mr-2"> ${{ $pagosCheques }}</td>
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
            <div class="small-box bg-danger" style="cursor: pointer;"
                wire:click='$dispatchTo("cerrar-caja","cerrar-caja-modal")'>
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
        @livewire('form-create-order')
        @livewire('compra-caja', ['caja' => $caja])
        @livewire('cerrar-caja', ['caja' => $caja])
    @endcan



</div>
