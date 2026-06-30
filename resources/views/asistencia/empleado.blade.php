@extends('adminlte::page')

@section('title', 'Perfil de Asistencia - ' . $user->name)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><strong>PERFIL DE ASISTENCIA</strong></h1>
            <p class="text-muted mb-0">Detalles y estadísticas de ingreso/egreso para: <strong>{{ $user->name }}</strong></p>
        </div>
        <div>
            <a href="{{ route('asistencia.control') }}" class="btn btn-outline-secondary font-weight-bold">
                <i class="fas fa-arrow-left mr-1"></i> Volver al panel
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <!-- Panel de Información del Empleado -->
    <div class="col-md-4">
        <div class="card card-outline card-primary shadow">
            <div class="card-body box-profile">
                <div class="text-center mb-3">
                    <div class="d-inline-flex justify-content-center align-items-center bg-primary rounded-circle shadow-sm" style="width: 80px; height: 80px;">
                        <span class="text-white font-weight-bold text-2xl" style="font-size: 2.2rem;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>
                </div>

                <h3 class="profile-username text-center font-weight-bold text-dark mb-1">{{ $user->name }}</h3>
                <p class="text-muted text-center text-sm mb-4">{{ $user->email }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item d-flex justify-content-between">
                        <b>Registros Totales</b> 
                        <span class="badge badge-secondary py-1 px-2 font-weight-bold">{{ count($historial) * 2 }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <b>Miembro desde</b> 
                        <span class="text-secondary font-mono">{{ $user->created_at->format('d/m/Y') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Panel de Estadísticas y Horas -->
    <div class="col-md-8">
        <div class="row">
            <!-- Horas de la Semana -->
            <div class="col-sm-6">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-calendar-week"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-xs uppercase font-weight-bold text-secondary">Horas esta Semana</span>
                        <span class="info-box-number text-2xl font-mono text-dark">{{ $semanaFormateada }}</span>
                        <span class="progress-description text-xs text-muted">Desde el lunes actual</span>
                    </div>
                </div>
            </div>
            <!-- Horas del Mes -->
            <div class="col-sm-6">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-calendar-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-xs uppercase font-weight-bold text-secondary">Horas este Mes</span>
                        <span class="info-box-number text-2xl font-mono text-dark">{{ $mesFormateado }}</span>
                        <span class="progress-description text-xs text-muted">Mes en curso ({{ now()->locale('es')->monthName }})</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla Histórica de Turnos -->
        <div class="card card-outline card-secondary shadow mt-2">
            <div class="card-header">
                <h3 class="card-title font-weight-bold"><i class="fas fa-business-time mr-2 text-secondary"></i>Historial de Turnos y Horas Trabajadas</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>Entrada (Ingreso)</th>
                                <th>Salida (Egreso)</th>
                                <th class="text-center">Tiempo Trabajado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($historial as $turno)
                                <tr>
                                    <!-- Entrada -->
                                    <td>
                                        @if($turno['entrada'])
                                            <div class="font-weight-bold text-success text-sm">
                                                <i class="fas fa-sign-in-alt mr-1"></i>
                                                {{ $turno['entrada']->fecha_hora->setTimezone('America/Argentina/Buenos_Aires')->format('d/m/Y H:i:s') }}
                                            </div>
                                            @if($turno['entrada']->latitud && $turno['entrada']->longitud)
                                                <a href="https://www.google.com/maps?q={{ $turno['entrada']->latitud }},{{ $turno['entrada']->longitud }}" 
                                                   target="_blank" class="text-xs text-muted font-mono" title="Ver en Google Maps">
                                                    <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                                    {{ number_format($turno['entrada']->latitud, 5) }}, {{ number_format($turno['entrada']->longitud, 5) }}
                                                </a>
                                            @endif
                                        @else
                                            <span class="text-muted text-xs font-weight-bold">Falta registro de entrada</span>
                                        @endif
                                    </td>

                                    <!-- Salida -->
                                    <td>
                                        @if($turno['salida'])
                                            <div class="font-weight-bold text-danger text-sm">
                                                <i class="fas fa-sign-out-alt mr-1"></i>
                                                {{ $turno['salida']->fecha_hora->setTimezone('America/Argentina/Buenos_Aires')->format('d/m/Y H:i:s') }}
                                            </div>
                                            @if($turno['salida']->latitud && $turno['salida']->longitud)
                                                <a href="https://www.google.com/maps?q={{ $turno['salida']->latitud }},{{ $turno['salida']->longitud }}" 
                                                   target="_blank" class="text-xs text-muted font-mono" title="Ver en Google Maps">
                                                    <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                                    {{ number_format($turno['salida']->latitud, 5) }}, {{ number_format($turno['salida']->longitud, 5) }}
                                                </a>
                                            @endif
                                        @else
                                            @if($turno['entrada'])
                                                <span class="badge badge-warning px-2 py-1 text-xs uppercase font-weight-bold">
                                                    En curso / Activo
                                                </span>
                                            @else
                                                <span class="text-muted text-xs font-weight-bold">Falta registro de salida</span>
                                            @endif
                                        @endif
                                    </td>

                                    <!-- Duración -->
                                    <td class="align-middle text-center font-weight-bold text-dark font-mono">
                                        @if($turno['entrada'] && $turno['salida'])
                                            <span class="text-indigo">{{ $turno['duracion'] }}</span>
                                        @elseif($turno['entrada'] && !$turno['salida'])
                                            <span class="text-warning text-xs font-weight-bold"><i class="fas fa-spinner fa-spin mr-1"></i>Calculando...</span>
                                        @else
                                            <span class="text-muted font-weight-normal text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted font-weight-bold">
                                        No hay registros de turnos de asistencia para este empleado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
    </style>
@stop
