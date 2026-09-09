<div>
    <!-- BARRA DE FILTROS -->
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
        <div class="card-header bg-white border-0 pt-3 pb-2">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="font-weight-bold text-dark mb-2 mb-md-0">
                    <i class="fas fa-filter text-primary mr-2"></i>Filtros de Búsqueda y Segmentación
                </h5>
                <div>
                    <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="resetFilters" title="Restablecer filtros">
                        <i class="fas fa-undo mr-1"></i> Limpiar Filtros
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body pt-1 pb-3">
            <div class="row">
                <!-- SELECTOR CATEGORÍA -->
                <div class="col-12 col-sm-6 col-md-3 mb-3">
                    <label class="font-weight-bold text-muted small text-uppercase mb-1">
                        <i class="fas fa-tags mr-1"></i> Categoría
                    </label>
                    <select class="form-control custom-select shadow-sm" wire:model.live="selectedCategoria" style="border-radius: 8px;">
                        <option value="">Todas las Categorías</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->descripcion }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- SELECTOR SUBCATEGORÍA -->
                <div class="col-12 col-sm-6 col-md-3 mb-3">
                    <label class="font-weight-bold text-muted small text-uppercase mb-1">
                        <i class="fas fa-tag mr-1"></i> Subcategoría
                    </label>
                    <select class="form-control custom-select shadow-sm" wire:model.live="selectedSubcategoria" style="border-radius: 8px;">
                        <option value="">Todas las Subcategorías</option>
                        @foreach($subcategorias as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->descripcion }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- PERÍODO -->
                <div class="col-12 col-sm-6 col-md-3 mb-3">
                    <label class="font-weight-bold text-muted small text-uppercase mb-1">
                        <i class="fas fa-calendar-alt mr-1"></i> Período
                    </label>
                    <select class="form-control custom-select shadow-sm" wire:model.live="period" style="border-radius: 8px;">
                        <option value="today">Hoy</option>
                        <option value="this_week">Esta Semana</option>
                        <option value="this_month">Este Mes</option>
                        <option value="this_year">Este Año</option>
                        <option value="custom">Rango Personalizado</option>
                        <option value="all_time">Histórico (Todo)</option>
                    </select>
                </div>

                <!-- MÉTRICA Y RANKING -->
                <div class="col-12 col-sm-6 col-md-3 mb-3">
                    <label class="font-weight-bold text-muted small text-uppercase mb-1">
                        <i class="fas fa-chart-line mr-1"></i> Métrica del Gráfico
                    </label>
                    <select class="form-control custom-select shadow-sm" wire:model.live="metric" style="border-radius: 8px;">
                        <option value="cantidad">Por Unidades Vendidas</option>
                        <option value="total">Por Recaudación ($)</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <!-- RANGO DE FECHAS PERSONALIZADO -->
                @if($period === 'custom')
                    <div class="col-12 col-md-6 mb-3">
                        <label class="font-weight-bold text-muted small text-uppercase mb-1">
                            <i class="far fa-calendar-check mr-1"></i> Rango de Fechas
                        </label>
                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <input type="date" class="form-control shadow-sm" wire:model.live="startDate" style="border-radius: 8px;">
                            <span class="text-muted font-weight-bold">a</span>
                            <input type="date" class="form-control shadow-sm" wire:model.live="endDate" style="border-radius: 8px;">
                        </div>
                    </div>
                @endif

                <!-- BÚSQUEDA Y TOP N -->
                <div class="col-12 {{ $period === 'custom' ? 'col-md-4' : 'col-md-8' }} mb-3">
                    <label class="font-weight-bold text-muted small text-uppercase mb-1">
                        <i class="fas fa-search mr-1"></i> Buscar Producto
                    </label>
                    <div class="input-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" class="form-control border-left-0" wire:model.live.debounce.300ms="searchQuery" placeholder="Filtrar por código, descripción o código de barras...">
                    </div>
                </div>

                <div class="col-12 {{ $period === 'custom' ? 'col-md-2' : 'col-md-4' }} mb-3">
                    <label class="font-weight-bold text-muted small text-uppercase mb-1">
                        <i class="fas fa-list-ol mr-1"></i> Mostrar Top
                    </label>
                    <select class="form-control custom-select shadow-sm" wire:model.live="limit" style="border-radius: 8px;">
                        <option value="5">Top 5</option>
                        <option value="10">Top 10</option>
                        <option value="15">Top 15</option>
                        <option value="20">Top 20</option>
                        <option value="50">Top 50</option>
                        <option value="100">Top 100</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- TARJETAS DE RESUMEN (KPIS) -->
    <div class="row">
        <!-- TOTAL UNIDADES -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="info-box shadow-sm border-0 bg-gradient-info text-white position-relative overflow-hidden mb-4" style="border-radius: 12px;">
                <span class="info-box-icon bg-white text-info rounded-circle m-2 shadow-sm" style="width: 55px; height: 55px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-boxes fa-lg"></i>
                </span>
                <div class="info-box-content p-3">
                    <span class="info-box-text text-uppercase font-weight-bold" style="font-size: 0.8rem; letter-spacing: 0.5px; opacity: 0.9;">Unidades Vendidas</span>
                    <span class="info-box-number font-weight-bold" style="font-size: 1.6rem;">{{ number_format($totalUnitsSold, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- TOTAL RECAUDADO -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="info-box shadow-sm border-0 bg-gradient-success text-white position-relative overflow-hidden mb-4" style="border-radius: 12px;">
                <span class="info-box-icon bg-white text-success rounded-circle m-2 shadow-sm" style="width: 55px; height: 55px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-dollar-sign fa-lg"></i>
                </span>
                <div class="info-box-content p-3">
                    <span class="info-box-text text-uppercase font-weight-bold" style="font-size: 0.8rem; letter-spacing: 0.5px; opacity: 0.9;">Facturación Total</span>
                    <span class="info-box-number font-weight-bold" style="font-size: 1.6rem;">${{ number_format($totalRevenue, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- PRODUCTO ESTRELLA #1 -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="info-box shadow-sm border-0 bg-gradient-warning text-white position-relative overflow-hidden mb-4" style="border-radius: 12px;">
                <span class="info-box-icon bg-white text-warning rounded-circle m-2 shadow-sm" style="width: 55px; height: 55px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-trophy fa-lg"></i>
                </span>
                <div class="info-box-content p-3" style="min-width: 0;">
                    <span class="info-box-text text-uppercase font-weight-bold" style="font-size: 0.8rem; letter-spacing: 0.5px; opacity: 0.9;">Producto Más Vendido</span>
                    <span class="info-box-number font-weight-bold text-truncate" title="{{ $topProductName }}" style="font-size: 1.1rem; max-width: 100%; display: block;">
                        {{ $topProductName }}
                    </span>
                    <small style="opacity: 0.95;">
                        @if($topProductQty > 0)
                            <strong>{{ number_format($topProductQty, 0, ',', '.') }} un.</strong> (${{ number_format($topProductRevenue, 2, ',', '.') }})
                        @else
                            -
                        @endif
                    </small>
                </div>
            </div>
        </div>

        <!-- VARIEDAD DE PRODUCTOS -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="info-box shadow-sm border-0 bg-gradient-indigo text-white position-relative overflow-hidden mb-4" style="border-radius: 12px; background: linear-gradient(135deg, #6610f2, #6f42c1);">
                <span class="info-box-icon bg-white rounded-circle m-2 shadow-sm" style="width: 55px; height: 55px; display: flex; align-items: center; justify-content: center; color: #6610f2;">
                    <i class="fas fa-layer-group fa-lg"></i>
                </span>
                <div class="info-box-content p-3">
                    <span class="info-box-text text-uppercase font-weight-bold" style="font-size: 0.8rem; letter-spacing: 0.5px; opacity: 0.9;">Variedad Vendida</span>
                    <span class="info-box-number font-weight-bold" style="font-size: 1.6rem;">{{ $distinctProductsCount }} <small style="font-size: 0.9rem; font-weight: normal;">ítems</small></span>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN DE GRÁFICO -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h5 class="card-title font-weight-bold text-dark m-0">
                            <i class="fas fa-chart-bar mr-2 text-info"></i>Ranking de Productos Más Vendidos
                        </h5>
                        <br>
                        <small class="text-muted">
                            Mostrando el top {{ is_numeric($limit) ? $limit : 'completo' }} ordenado por
                            <strong>{{ $metric === 'total' ? 'monto total facturado ($)' : 'cantidad de unidades vendidas' }}</strong>
                        </small>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <span class="badge badge-light px-3 py-2 border shadow-sm" style="border-radius: 20px; font-size: 0.85rem;">
                            <i class="fas fa-info-circle text-primary mr-1"></i>
                            {{ count($chartData) }} productos graficados
                        </span>
                    </div>
                </div>
                <div class="card-body px-4 pb-4">
                    <div wire:ignore class="position-relative" style="min-height: 320px; width: 100%;">
                        <div class="chart position-relative" id="productChartContainer" style="min-height: 320px; height: 320px; width: 100%;">
                            <canvas id="topProductsChart" style="height: 320px; max-height: 320px; display: block; width: 100%;"></canvas>
                        </div>

                        <div class="flex-column align-items-center justify-content-center text-muted" id="noProductDataContainer" style="min-height: 260px; height: 320px; display: none;">
                            <i class="fas fa-box-open fa-3x mb-3 text-secondary" style="opacity: 0.4;"></i>
                            <p class="m-0 font-weight-bold text-dark">No se encontraron ventas para los filtros seleccionados</p>
                            <small class="text-muted mt-1">Prueba seleccionando otro período, categoría o ajustando la búsqueda.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLA DE DETALLE DE RANKING -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h5 class="card-title font-weight-bold text-dark m-0">
                            <i class="fas fa-table mr-2 text-primary"></i>Detalle de Ventas por Producto
                        </h5>
                        <br>
                        <small class="text-muted">Desglose completo de unidades, facturación y participación de mercado</small>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <span class="badge badge-primary px-3 py-2" style="border-radius: 20px; font-size: 0.85rem;">
                            {{ count($rankingList) }} registros encontrados
                        </span>
                    </div>
                </div>

                <div class="card-body table-responsive p-0">
                    @if(count($rankingList) > 0)
                        <table class="table table-hover table-striped align-middle m-0">
                            <thead class="bg-light">
                                <tr class="text-muted small text-uppercase" style="letter-spacing: 0.5px;">
                                    <th style="width: 60px;" class="text-center">#</th>
                                    <th style="width: 130px;">Código</th>
                                    <th>Producto</th>
                                    <th>Categoría</th>
                                    <th>Subcategoría</th>
                                    <th class="text-center" style="width: 170px;">Unidades Vendidas</th>
                                    <th class="text-right" style="width: 130px;">Precio Unit.</th>
                                    <th class="text-right" style="width: 160px;">Total Recaudado</th>
                                    <th class="text-center" style="width: 110px;">% Part.</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rankingList as $row)
                                    <tr>
                                        <!-- POSICIÓN -->
                                        <td class="text-center font-weight-bold">
                                            @if($row['pos'] === 1)
                                                <span class="badge badge-warning text-white shadow-sm" style="font-size: 0.9rem; border-radius: 50%; width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-crown"></i>
                                                </span>
                                            @elseif($row['pos'] === 2)
                                                <span class="badge badge-secondary shadow-sm" style="font-size: 0.9rem; border-radius: 50%; width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center;">2</span>
                                            @elseif($row['pos'] === 3)
                                                <span class="badge badge-info shadow-sm" style="font-size: 0.9rem; border-radius: 50%; width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center;">3</span>
                                            @else
                                                <span class="text-muted">{{ $row['pos'] }}</span>
                                            @endif
                                        </td>

                                        <!-- CÓDIGO -->
                                        <td>
                                            <span class="badge badge-light border text-dark font-weight-bold">
                                                {{ $row['codigo'] }}
                                            </span>
                                        </td>

                                        <!-- DESCRIPCIÓN -->
                                        <td class="font-weight-bold text-dark">
                                            {{ $row['descripcion'] }}
                                        </td>

                                        <!-- CATEGORÍA -->
                                        <td>
                                            <span class="badge badge-pill badge-light border text-secondary">
                                                {{ $row['categoria'] }}
                                            </span>
                                        </td>

                                        <!-- SUBCATEGORÍA -->
                                        <td>
                                            <span class="badge badge-pill badge-light border text-muted">
                                                {{ $row['subcategoria'] }}
                                            </span>
                                        </td>

                                        <!-- UNIDADES VENDIDAS Y BARRA -->
                                        <td class="text-center">
                                            <div class="d-flex flex-column align-items-center">
                                                <span class="font-weight-bold text-dark" style="font-size: 1rem;">
                                                    {{ number_format($row['cantidad'], 0, ',', '.') }} un.
                                                </span>
                                                <div class="progress mt-1" style="height: 5px; width: 100px; border-radius: 4px;">
                                                    <div class="progress-bar bg-info" style="width: {{ $row['perc_units'] }}%;"></div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- PRECIO UNITARIO -->
                                        <td class="text-right text-muted">
                                            ${{ number_format($row['precio_venta'], 2, ',', '.') }}
                                        </td>

                                        <!-- TOTAL RECAUDADO -->
                                        <td class="text-right font-weight-bold text-success" style="font-size: 1.05rem;">
                                            ${{ number_format($row['recaudado'], 2, ',', '.') }}
                                        </td>

                                        <!-- PARTICIPACIÓN -->
                                        <td class="text-center">
                                            <span class="badge badge-success px-2 py-1" style="border-radius: 12px;">
                                                {{ $metric === 'total' ? $row['perc_revenue'] : $row['perc_units'] }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-5 text-center text-muted">
                            <i class="fas fa-inbox fa-3x mb-3 text-secondary" style="opacity: 0.3;"></i>
                            <h6 class="font-weight-bold">Sin resultados para mostrar</h6>
                            <p class="small text-muted mb-0">Modifica los filtros de categoría o período para ver las estadísticas de ventas.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT DE CHART.JS -->
    <script>
        let productsChartObj = null;

        function renderOrUpdateProductsChart(labels, data, metric) {
            let canvas = document.getElementById('topProductsChart');
            if (!canvas) return;

            let sum = (data && Array.isArray(data)) ? data.reduce((a, b) => a + Number(b), 0) : 0;
            let chartContainer = document.getElementById('productChartContainer');
            let noDataContainer = document.getElementById('noProductDataContainer');

            if (!data || data.length === 0 || sum === 0) {
                if (chartContainer) chartContainer.style.setProperty('display', 'none', 'important');
                if (noDataContainer) {
                    noDataContainer.style.removeProperty('display');
                    noDataContainer.style.setProperty('display', 'flex', 'important');
                }
                if (productsChartObj) {
                    productsChartObj.destroy();
                    productsChartObj = null;
                }
                return;
            }

            if (chartContainer) {
                chartContainer.style.removeProperty('display');
                chartContainer.style.setProperty('display', 'block', 'important');
            }
            if (noDataContainer) {
                noDataContainer.style.setProperty('display', 'none', 'important');
            }

            let barColors = [
                '#17a2b8', '#20c997', '#28a745', '#ffc107', '#fd7e14',
                '#e83e8c', '#6f42c1', '#007bff', '#6610f2', '#343a40',
                '#36a2eb', '#4bc0c0', '#9966ff', '#ff9f40', '#ff6384'
            ];
            let bgColors = labels.map((_, i) => barColors[i % barColors.length]);

            if (productsChartObj) {
                productsChartObj.data.labels = labels;
                productsChartObj.data.datasets[0].data = data;
                productsChartObj.data.datasets[0].backgroundColor = bgColors;
                productsChartObj.data.datasets[0].borderColor = bgColors;
                productsChartObj.data.datasets[0].label = metric === 'total' ? 'Recaudación ($)' : 'Unidades Vendidas';

                if (productsChartObj.options.scales && productsChartObj.options.scales.yAxes) {
                    productsChartObj.options.scales.yAxes[0].ticks.callback = function(value) {
                        return metric === 'total' ? '$' + value.toLocaleString('es-AR') : value.toLocaleString('es-AR');
                    };
                }
                if (productsChartObj.options.tooltips) {
                    productsChartObj.options.tooltips.callbacks.label = function(tooltipItem) {
                        let val = parseFloat(tooltipItem.yLabel !== undefined ? tooltipItem.yLabel : tooltipItem.value);
                        if (metric === 'total') {
                            return ' Recaudado: $' + val.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        } else {
                            return ' Unidades vendidas: ' + val.toLocaleString('es-AR') + ' un.';
                        }
                    };
                }
                productsChartObj.update();
            } else {
                let ctx = canvas.getContext('2d');
                productsChartObj = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: metric === 'total' ? 'Recaudación ($)' : 'Unidades Vendidas',
                            data: data,
                            backgroundColor: bgColors,
                            borderColor: bgColors,
                            borderWidth: 1,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        animation: {
                            duration: 350
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    let val = parseFloat(tooltipItem.yLabel !== undefined ? tooltipItem.yLabel : tooltipItem.value);
                                    if (metric === 'total') {
                                        return ' Recaudado: $' + val.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                    } else {
                                        return ' Unidades vendidas: ' + val.toLocaleString('es-AR') + ' un.';
                                    }
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
                                    fontStyle: 'bold',
                                    autoSkip: false,
                                    maxRotation: 45,
                                    minRotation: 0
                                }
                            }],
                            yAxes: [{
                                gridLines: {
                                    color: '#f1f1f1',
                                    zeroLineColor: '#e9ecef',
                                    borderDash: [5, 5]
                                },
                                ticks: {
                                    beginAtZero: true,
                                    fontColor: '#6c757d',
                                    fontStyle: 'bold',
                                    callback: function(value) {
                                        if (metric === 'total') {
                                            return '$' + value.toLocaleString('es-AR');
                                        } else {
                                            return value.toLocaleString('es-AR');
                                        }
                                    }
                                }
                            }]
                        }
                    }
                });
            }
        }

        function initChartFromState() {
            let initialLabels = @json($chartLabels);
            let initialData = @json($chartData);
            let initialMetric = @json($metric);
            renderOrUpdateProductsChart(initialLabels, initialData, initialMetric);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initChartFromState);
        } else {
            initChartFromState();
        }

        document.addEventListener('livewire:init', () => {
            Livewire.on('update-products-chart', (eventData) => {
                let payload = Array.isArray(eventData) ? eventData[0] : (eventData.detail ? eventData.detail : eventData);
                if (payload) {
                    renderOrUpdateProductsChart(payload.labels, payload.data, payload.metric);
                }
            });
        });
    </script>
</div>
