<div>
    <!-- FILTRO DE PERIODO -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="text-dark font-weight-bold mb-2">
                <i class="fas fa-chart-pie mr-2 text-primary"></i>Panel de Control de Facturación
            </h4>
            <div class="d-flex align-items-center flex-wrap mb-2" style="gap: 10px;">
                <div class="d-flex align-items-center">
                    <span class="mr-2 text-muted font-weight-bold"><i class="fas fa-filter mr-1"></i>Período:</span>
                    <select class="form-control custom-select shadow-sm" style="width: 180px; border-radius: 8px; border: 1px solid #ced4da;" wire:model.live="period">
                        <option value="today">Hoy</option>
                        <option value="this_week">Esta Semana</option>
                        <option value="this_month">Este Mes</option>
                        <option value="this_year">Este Año</option>
                        <option value="custom">Rango de fechas</option>
                        <option value="all_time">Histórico (Todo)</option>
                    </select>
                </div>
                
                @if($period === 'custom')
                    <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                        <span class="text-muted font-weight-bold ml-md-2">Desde:</span>
                        <input type="date" class="form-control shadow-sm" style="width: 145px; border-radius: 8px; border: 1px solid #ced4da;" wire:model.live="startDate">
                        <span class="text-muted font-weight-bold">Hasta:</span>
                        <input type="date" class="form-control shadow-sm" style="width: 145px; border-radius: 8px; border: 1px solid #ced4da;" wire:model.live="endDate">
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- CARDS DE KPIS -->
    <div class="row">
        <!-- INGRESOS -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box shadow-sm border-0 bg-gradient-success text-white position-relative overflow-hidden" style="border-radius: 12px; transition: transform 0.3s;">
                <span class="info-box-icon bg-white text-success rounded-circle m-2 shadow-sm" style="width: 55px; height: 55px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-chart-line fa-lg"></i>
                </span>
                <div class="info-box-content p-3">
                    <span class="info-box-text text-uppercase font-weight-bold" style="font-size: 0.8rem; letter-spacing: 1px; opacity: 0.9;">Ingresos (Ventas)</span>
                    <span class="info-box-number font-weight-bold" style="font-size: 1.6rem;">${{ number_format($totalRevenue, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- GASTOS -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box shadow-sm border-0 bg-gradient-danger text-white position-relative overflow-hidden" style="border-radius: 12px; transition: transform 0.3s;">
                <span class="info-box-icon bg-white text-danger rounded-circle m-2 shadow-sm" style="width: 55px; height: 55px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-wallet fa-lg"></i>
                </span>
                <div class="info-box-content p-3">
                    <span class="info-box-text text-uppercase font-weight-bold" style="font-size: 0.8rem; letter-spacing: 1px; opacity: 0.9;">Gastos (Egresos)</span>
                    <span class="info-box-number font-weight-bold" style="font-size: 1.6rem;">${{ number_format($totalExpenses, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- BALANCE NETO -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box shadow-sm border-0 bg-gradient-info text-white position-relative overflow-hidden" style="border-radius: 12px; transition: transform 0.3s;">
                <span class="info-box-icon bg-white text-info rounded-circle m-2 shadow-sm" style="width: 55px; height: 55px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-balance-scale fa-lg"></i>
                </span>
                <div class="info-box-content p-3">
                    <span class="info-box-text text-uppercase font-weight-bold" style="font-size: 0.8rem; letter-spacing: 1px; opacity: 0.9;">Balance Neto</span>
                    <span class="info-box-number font-weight-bold" style="font-size: 1.6rem;">${{ number_format($totalBalance, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- ORDENES TOTALES -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box shadow-sm border-0 bg-gradient-indigo text-white position-relative overflow-hidden" style="border-radius: 12px; background: linear-gradient(135deg, #6610f2, #6f42c1); transition: transform 0.3s;">
                <span class="info-box-icon bg-white text-purple rounded-circle m-2 shadow-sm" style="width: 55px; height: 55px; display: flex; align-items: center; justify-content: center; color: #6610f2;">
                    <i class="fas fa-receipt fa-lg"></i>
                </span>
                <div class="info-box-content p-3">
                    <span class="info-box-text text-uppercase font-weight-bold" style="font-size: 0.8rem; letter-spacing: 1px; opacity: 0.9;">Órdenes de Servicio</span>
                    <span class="info-box-number font-weight-bold" style="font-size: 1.6rem;">{{ $totalOrdersCount }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCION DE GRAFICO Y SECTORES -->
    <div class="row">
        <!-- GRAFICO DE LINEA -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title font-weight-bold text-dark m-0">Tendencia de Ingresos</h5>
                        <small class="text-muted">Visualización del flujo de ventas en el período seleccionado</small>
                    </div>
                </div>
                <div class="card-body px-4 pb-4">
                    @php
                        $hasChartData = collect($chartData)->sum() > 0;
                    @endphp
                    <div class="chart position-relative" id="chartContainer" style="height: 250px; {{ $hasChartData ? '' : 'display: none;' }}">
                        <canvas id="salesChart" height="250" style="height: 250px; display: block; width: 100%;"></canvas>
                    </div>
                    <div class="flex-column align-items-center justify-content-center text-muted" id="noDataContainer" style="height: 250px; {{ $hasChartData ? 'display: none;' : 'display: flex !important;' }}">
                        <i class="fas fa-chart-area fa-3x mb-3 text-light"></i>
                        <p class="m-0 font-weight-bold">No hay ingresos registrados en este período</p>
                        <small>Prueba seleccionando otro período o ingresando una nueva orden.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- COMPARACION LUBRICENTRO VS LAVADERO -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0" style="border-radius: 12px; height: calc(100% - 24px);">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="card-title font-weight-bold text-dark m-0">Desempeño por Sector</h5>
                    <small class="text-muted">Comparativa de ventas y órdenes</small>
                </div>
                <div class="card-body px-4">
                    @php
                        $lubPercent = $totalRevenue > 0 ? ($ordersLubricentroTotal / $totalRevenue) * 100 : 0;
                        $lavPercent = $totalRevenue > 0 ? ($ordersLavaderoTotal / $totalRevenue) * 100 : 0;
                    @endphp

                    <!-- LUBRICENTRO -->
                    <div class="mb-4 p-3 rounded bg-light" style="border-left: 5px solid #fd7e14;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="font-weight-bold text-orange"><i class="fas fa-oil-can mr-2"></i>Lubricentro</span>
                            <span class="badge badge-warning text-white">{{ number_format($lubPercent, 1) }}%</span>
                        </div>
                        <h4 class="font-weight-bold text-dark mb-2">${{ number_format($ordersLubricentroTotal, 2, ',', '.') }}</h4>
                        <div class="progress progress-sm mb-2" style="border-radius: 5px; height: 6px;">
                            <div class="progress-bar bg-orange" style="width: {{ $lubPercent }}%; border-radius: 5px;"></div>
                        </div>
                        <div class="d-flex justify-content-between text-xs text-muted" style="font-size: 0.85rem;">
                            <span>{{ $ordersLubricentroCount }} órdenes</span>
                            <span>Ticket Promedio: <strong>${{ $ordersLubricentroCount > 0 ? number_format($ordersLubricentroTotal / $ordersLubricentroCount, 2, ',', '.') : '0,00' }}</strong></span>
                        </div>
                    </div>

                    <!-- LAVADERO -->
                    <div class="mb-3 p-3 rounded bg-light" style="border-left: 5px solid #007bff;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="font-weight-bold text-primary"><i class="fas fa-car mr-2"></i>Lavadero</span>
                            <span class="badge badge-primary">{{ number_format($lavPercent, 1) }}%</span>
                        </div>
                        <h4 class="font-weight-bold text-dark mb-2">${{ number_format($ordersLavaderoTotal, 2, ',', '.') }}</h4>
                        <div class="progress progress-sm mb-2" style="border-radius: 5px; height: 6px;">
                            <div class="progress-bar bg-primary" style="width: {{ $lavPercent }}%; border-radius: 5px;"></div>
                        </div>
                        <div class="d-flex justify-content-between text-xs text-muted" style="font-size: 0.85rem;">
                            <span>{{ $ordersLavaderoCount }} órdenes</span>
                            <span>Ticket Promedio: <strong>${{ $ordersLavaderoCount > 0 ? number_format($ordersLavaderoTotal / $ordersLavaderoCount, 2, ',', '.') : '0,00' }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCION METODOS DE PAGO Y TARJETAS -->
    <div class="row">
        <!-- DESGLOSE MEDIOS DE PAGO -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="card-title font-weight-bold text-dark m-0"><i class="fas fa-wallet mr-2 text-teal"></i>Medios de Pago Utilizados</h5>
                    <small class="text-muted">Distribución de ingresos según el medio de cobro</small>
                </div>
                <div class="card-body px-4 pb-4">
                    @php
                        $efectivoPercent = $totalRevenue > 0 ? ($pagoEfectivo / $totalRevenue) * 100 : 0;
                        $tarjetaPercent = $totalRevenue > 0 ? ($pagoTarjeta / $totalRevenue) * 100 : 0;
                        $ctaCtePercent = $totalRevenue > 0 ? ($pagoCtaCte / $totalRevenue) * 100 : 0;
                        $transferenciaPercent = $totalRevenue > 0 ? ($pagoTransferencia / $totalRevenue) * 100 : 0;
                        $chequePercent = $totalRevenue > 0 ? ($pagoCheque / $totalRevenue) * 100 : 0;
                    @endphp

                    <!-- Efectivo -->
                    <div class="progress-group mb-3">
                        <div class="d-flex justify-content-between font-weight-bold text-dark" style="font-size: 0.95rem;">
                            <span><i class="fas fa-money-bill-wave text-success mr-2"></i>Efectivo</span>
                            <span>${{ number_format($pagoEfectivo, 2, ',', '.') }} <small class="text-muted">({{ number_format($efectivoPercent, 1) }}%)</small></span>
                        </div>
                        <div class="progress progress-sm" style="height: 8px; border-radius: 10px;">
                            <div class="progress-bar bg-success" style="width: {{ $efectivoPercent }}%; border-radius: 10px;"></div>
                        </div>
                    </div>

                    <!-- Tarjetas -->
                    <div class="progress-group mb-3">
                        <div class="d-flex justify-content-between font-weight-bold text-dark" style="font-size: 0.95rem;">
                            <span><i class="credit-card fas fa-credit-card text-info mr-2"></i>Tarjetas (Crédito/Débito)</span>
                            <span>${{ number_format($pagoTarjeta, 2, ',', '.') }} <small class="text-muted">({{ number_format($tarjetaPercent, 1) }}%)</small></span>
                        </div>
                        <div class="progress progress-sm" style="height: 8px; border-radius: 10px;">
                            <div class="progress-bar bg-info" style="width: {{ $tarjetaPercent }}%; border-radius: 10px;"></div>
                        </div>
                    </div>

                    <!-- Transferencias -->
                    <div class="progress-group mb-3">
                        <div class="d-flex justify-content-between font-weight-bold text-dark" style="font-size: 0.95rem;">
                            <span><i class="fas fa-university text-primary mr-2"></i>Transferencia Bancaria</span>
                            <span>${{ number_format($pagoTransferencia, 2, ',', '.') }} <small class="text-muted">({{ number_format($transferenciaPercent, 1) }}%)</small></span>
                        </div>
                        <div class="progress progress-sm" style="height: 8px; border-radius: 10px;">
                            <div class="progress-bar bg-primary" style="width: {{ $transferenciaPercent }}%; border-radius: 10px;"></div>
                        </div>
                    </div>

                    <!-- Cuenta Corriente -->
                    <div class="progress-group mb-3">
                        <div class="d-flex justify-content-between font-weight-bold text-dark" style="font-size: 0.95rem;">
                            <span><i class="fas fa-users-cog text-warning mr-2"></i>Cuenta Corriente</span>
                            <span>${{ number_format($pagoCtaCte, 2, ',', '.') }} <small class="text-muted">({{ number_format($ctaCtePercent, 1) }}%)</small></span>
                        </div>
                        <div class="progress progress-sm" style="height: 8px; border-radius: 10px;">
                            <div class="progress-bar bg-warning" style="width: {{ $ctaCtePercent }}%; border-radius: 10px;"></div>
                        </div>
                    </div>

                    <!-- Cheques -->
                    <div class="progress-group mb-0">
                        <div class="d-flex justify-content-between font-weight-bold text-dark" style="font-size: 0.95rem;">
                            <span><i class="fas fa-money-check-alt text-secondary mr-2"></i>Cheques</span>
                            <span>${{ number_format($pagoCheque, 2, ',', '.') }} <small class="text-muted">({{ number_format($chequePercent, 1) }}%)</small></span>
                        </div>
                        <div class="progress progress-sm" style="height: 8px; border-radius: 10px;">
                            <div class="progress-bar bg-secondary" style="width: {{ $chequePercent }}%; border-radius: 10px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TARJETAS MAS UTILIZADAS -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0" style="border-radius: 12px; height: calc(100% - 24px);">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="card-title font-weight-bold text-dark m-0"><i class="fas fa-credit-card mr-2 text-indigo"></i>Tarjetas de Crédito Populares</h5>
                    <small class="text-muted">Ranking de tarjetas por cantidad de transacciones</small>
                </div>
                <div class="card-body px-4">
                    @if(count($topCards) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-valign-middle m-0">
                                <thead>
                                    <tr class="text-muted" style="font-size: 0.85rem;">
                                        <th>Tarjeta</th>
                                        <th class="text-center">Operaciones</th>
                                        <th class="text-right">Monto Recaudado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topCards as $index => $card)
                                        <tr>
                                            <td class="font-weight-bold text-dark">
                                                @if($index === 0)
                                                    <span class="badge badge-warning text-white mr-1 shadow-sm"><i class="fas fa-trophy"></i></span>
                                                @else
                                                    <span class="badge badge-light mr-1 font-weight-bold text-muted" style="width: 20px;">{{ $index + 1 }}</span>
                                                @endif
                                                {{ $card->nombre_tarjeta }}
                                            </td>
                                            <td class="text-center text-muted font-weight-bold">{{ $card->count }}</td>
                                            <td class="text-right text-success font-weight-bold">
                                                ${{ number_format($card->total, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center text-muted" style="height: 180px;">
                            <i class="fas fa-credit-card fa-2x mb-2 text-light"></i>
                            <p class="m-0 font-weight-bold">Sin transacciones con tarjeta en este período</p>
                            <small class="text-center">Aquí aparecerán las estadísticas de las tarjetas Visa, Mastercard, etc.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- CHARTS INITIALIZATION SCRIPT -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let initialData = @json($chartData);
            let sum = initialData ? initialData.reduce((a, b) => a + b, 0) : 0;
            if (sum > 0) {
                initializeDashboardChart();
            }
        });

        // Handler for Livewire updates
        document.addEventListener('livewire:init', () => {
            Livewire.on('update-chart', (eventData) => {
                let payload = eventData[0];
                updateChartData(payload.labels, payload.data);
            });
        });

        let salesChartObj = null;

        function initializeDashboardChart(labels = null, data = null) {
            let canvas = document.getElementById('salesChart');
            if (!canvas) return;

            let ctx = canvas.getContext('2d');
            let initialLabels = labels || @json($chartLabels);
            let initialData = data || @json($chartData);

            if (!initialLabels || !initialData || initialLabels.length === 0) {
                return;
            }

            // Create gradient background
            let gradient = ctx.createLinearGradient(0, 0, 0, 220);
            gradient.addColorStop(0, 'rgba(40, 167, 69, 0.4)');
            gradient.addColorStop(1, 'rgba(40, 167, 69, 0.01)');

            salesChartObj = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: initialLabels,
                    datasets: [{
                        label: 'Ventas ($)',
                        data: initialData,
                        backgroundColor: gradient,
                        borderColor: '#28a745',
                        borderWidth: 3,
                        pointBackgroundColor: '#28a745',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(tooltipItem, data) {
                                let val = parseFloat(tooltipItem.yLabel);
                                return ' Ventas: $' + val.toLocaleString('es-AR', { minimumFractionDigits: 2 });
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                fontColor: '#6c757d',
                                fontStyle: 'bold'
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                color: '#f1f1f1',
                                zeroLineColor: '#e9ecef',
                                borderDash: [5, 5]
                            },
                            ticks: {
                                fontColor: '#6c757d',
                                fontStyle: 'bold',
                                callback: function(value) {
                                    return '$' + value.toLocaleString('es-AR');
                                }
                            }
                        }]
                    }
                }
            });
        }

        function updateChartData(labels, data) {
            let sum = data ? data.reduce((a, b) => a + b, 0) : 0;
            let chartContainer = document.getElementById('chartContainer');
            let noDataContainer = document.getElementById('noDataContainer');

            if (sum > 0) {
                if (chartContainer) chartContainer.style.display = 'block';
                if (noDataContainer) noDataContainer.style.setProperty('display', 'none', 'important');

                if (salesChartObj) {
                    salesChartObj.data.labels = labels;
                    salesChartObj.data.datasets[0].data = data;
                    salesChartObj.update();
                } else {
                    // Delay slightly to allow DOM to render canvas if it was hidden
                    setTimeout(() => {
                        initializeDashboardChart(labels, data);
                    }, 50);
                }
            } else {
                if (chartContainer) chartContainer.style.display = 'none';
                if (noDataContainer) {
                    noDataContainer.style.setProperty('display', 'flex', 'important');
                }
            }
        }
    </script>
</div>
