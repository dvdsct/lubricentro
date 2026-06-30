@extends('adminlte::page')

@section('title', 'Control de Asistencia - Rocket')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><strong>CONTROL DE ASISTENCIA</strong></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Asistencia</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
<div class="row">
    <!-- Sección de QR -->
    <div class="col-md-4">
        <div class="card card-outline card-primary shadow">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-qrcode mr-2 text-primary"></i>Código QR de Asistencia</h3>
            </div>
            <div class="card-body text-center">
                <p class="text-muted text-sm">
                    Imprime o descarga este código QR. Los empleados deben escanearlo desde sus dispositivos móviles para registrar su ingreso o egreso.
                </p>
                
                <!-- Contenedor del QR -->
                <div class="my-4 p-3 bg-white border rounded d-inline-block shadow-sm">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($scanUrl) }}" 
                         alt="QR Asistencia" 
                         class="img-fluid"
                         style="max-width: 250px; min-height: 250px;">
                </div>
                
                <p class="text-xs text-secondary mb-3">
                    Enlace: <a href="{{ $scanUrl }}" target="_blank" class="font-weight-bold">{{ $scanUrl }}</a>
                </p>

                <!-- Hidden canvas for local offline download -->
                <canvas id="qr-canvas" style="display: none;"></canvas>

                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('asistencia.download-qr') }}" class="btn btn-primary btn-block font-weight-bold shadow-sm mb-2">
                        <i class="fas fa-download mr-2"></i>Descargar QR (1000x1000 PNG)
                    </a>
                    
                    <button type="button" id="btn-download-local" style="display: none;" onclick="window.downloadLocalQr()" class="btn btn-outline-indigo btn-block font-weight-bold border-indigo text-indigo shadow-sm">
                        <i class="fas fa-magic mr-2"></i>Descarga Rápida Local (PNG)
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Logs -->
    <div class="col-md-8">
        <div class="card card-outline card-secondary shadow">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-history mr-2 text-secondary"></i>Registro de Asistencias</h3>
            </div>
            <div class="card-body">
                
                <!-- Filtros de Búsqueda -->
                <form method="GET" action="{{ route('asistencia.control') }}" class="mb-4 bg-light p-3 rounded border">
                    <div class="row">
                        <div class="col-md-5 mb-2 mb-md-0">
                            <label for="empleado" class="text-xs font-weight-bold text-secondary uppercase">Empleado</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" name="empleado" id="empleado" class="form-control" placeholder="Buscar por nombre o correo..." value="{{ request('empleado') }}">
                            </div>
                        </div>
                        <div class="col-md-4 mb-2 mb-md-0">
                            <label for="fecha" class="text-xs font-weight-bold text-secondary uppercase">Fecha</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                                </div>
                                <input type="date" name="fecha" id="fecha" class="form-control" value="{{ request('fecha') }}">
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-secondary mr-2 flex-grow-1 font-weight-bold">
                                <i class="fas fa-search mr-1"></i> Filtrar
                            </button>
                            @if(request()->anyFilled(['empleado', 'fecha']))
                                <a href="{{ route('asistencia.control') }}" class="btn btn-outline-secondary font-weight-bold">
                                    <i class="fas fa-undo"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                <!-- Tabla de Resultados -->
                <div class="table-responsive">
                    <table class="table table-hover table-striped border">
                        <thead class="thead-dark">
                            <tr>
                                <th>Empleado</th>
                                <th>Tipo</th>
                                <th>Fecha y Hora</th>
                                <th>Ubicación</th>
                                <th class="text-center">Mapa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($asistencias as $asistencia)
                                <tr>
                                    <td>
                                        <a href="{{ route('asistencia.empleado-perfil', $asistencia->user->id) }}" class="text-decoration-none" title="Ver perfil e historial de asistencia">
                                            <div class="font-weight-bold text-primary">{{ $asistencia->user->name }}</div>
                                            <div class="text-xs text-muted">{{ $asistencia->user->email }}</div>
                                        </a>
                                    </td>
                                    <td class="align-middle">
                                        @if ($asistencia->tipo === 'entrada')
                                            <span class="badge badge-success px-3 py-2 uppercase font-weight-bold">
                                                <i class="fas fa-sign-in-alt mr-1"></i>Entrada
                                            </span>
                                        @else
                                            <span class="badge badge-danger px-3 py-2 uppercase font-weight-bold">
                                                <i class="fas fa-sign-out-alt mr-1"></i>Salida
                                            </span>
                                        @endif
                                    </td>
                                    <td class="align-middle font-mono text-sm">
                                        {{ $asistencia->fecha_hora->setTimezone('America/Argentina/Buenos_Aires')->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="align-middle text-xs font-mono">
                                        @if($asistencia->latitud && $asistencia->longitud)
                                            {{ number_format($asistencia->latitud, 6) }}, {{ number_format($asistencia->longitud, 6) }}
                                        @else
                                            <span class="text-muted">No registrada</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        @if($asistencia->latitud && $asistencia->longitud)
                                            <a href="https://www.google.com/maps?q={{ $asistencia->latitud }},{{ $asistencia->longitud }}" 
                                               target="_blank" 
                                               class="btn btn-outline-primary btn-sm rounded-circle shadow-sm"
                                               title="Ver ubicación en Google Maps">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted font-weight-bold">
                                        No se encontraron registros de asistencia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $asistencias->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <style>
        .font-mono {
            font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }
        .text-indigo {
            color: #6610f2;
        }
        .btn-outline-indigo {
            color: #6610f2;
            border-color: #6610f2;
        }
        .btn-outline-indigo:hover {
            color: #fff;
            background-color: #6610f2;
            border-color: #6610f2;
        }
        .border-indigo {
            border-color: #6610f2;
        }
    </style>
@stop

@section('js')
    <!-- Cargamos Qrious para generar el QR localmente en alta definición offline -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js" integrity="sha512-pUh3VzRpd566sP4c+3W/wK5zVf39h27VjPzR4Z/G89F0x1e36Hl10Y6oZ3p0nEwWp1U38vFvS/f7d45s7r2oJw==" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var canvas = document.getElementById('qr-canvas');
            if (typeof QRious !== 'undefined') {
                var qr = new QRious({
                    element: canvas,
                    value: "{{ $scanUrl }}",
                    size: 1000,
                    level: 'H'
                });
                
                window.downloadLocalQr = function() {
                    var link = document.createElement('a');
                    link.download = 'qr_asistencia_1000x1000.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                }
                
                // Mostrar botón de descarga local si la librería cargó correctamente
                document.getElementById('btn-download-local').style.display = 'inline-block';
            }
        });
    </script>
@stop
