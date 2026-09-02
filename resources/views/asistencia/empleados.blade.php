@extends('adminlte::page')

@section('title', 'Control de Empleados - Rocket')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><strong>EMPLEADOS Y ASISTENCIA</strong></h1>
            <p class="text-muted mb-0">Listado de usuarios registrados y acceso a sus perfiles de asistencia.</p>
        </div>
    </div>
@stop

@section('content')
<div class="card shadow border-0">
    <div class="card-header bg-dark d-flex align-items-center py-3">
        <h3 class="card-title font-weight-bold text-white mb-0">
            <i class="fas fa-users mr-2"></i>Todos los Empleados Registrados
        </h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 80px;"></th>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Último Registro</th>
                        <th>Geolocalización</th>
                        <th>Roles</th>
                        <th class="text-center" style="width: 180px;">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <!-- Initials Avatar -->
                            <td class="align-middle text-center py-3">
                                <div class="d-inline-flex justify-content-center align-items-center bg-secondary text-white font-weight-bold rounded-circle shadow-sm" style="width: 42px; height: 42px; font-size: 1.1rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            </td>
                            
                            <!-- Name -->
                            <td class="align-middle font-weight-bold text-dark text-lg">
                                <div>{{ $user->name }}</div>
                                <div class="text-xs text-muted font-weight-normal">{{ $user->email }}</div>
                            </td>
                            
                            <!-- Estado -->
                            <td class="align-middle font-weight-bold">
                                @if ($user->ultimaAsistencia && $user->ultimaAsistencia->tipo === 'entrada')
                                    <span class="badge badge-success py-1.5 px-2.5 font-weight-bold text-xs uppercase shadow-xs">
                                        <i class="fas fa-briefcase mr-1"></i> Trabajando
                                    </span>
                                @else
                                    <span class="badge badge-secondary py-1.5 px-2.5 font-weight-bold text-xs uppercase shadow-xs">
                                        <i class="fas fa-sign-out-alt mr-1"></i> Fuera de Servicio
                                    </span>
                                @endif
                            </td>

                            <!-- Último Registro (Fecha y Hora) -->
                            <td class="align-middle">
                                @if ($user->ultimaAsistencia)
                                    <div class="font-mono text-sm font-weight-bold text-dark">
                                        {{ $user->ultimaAsistencia->fecha_hora->setTimezone('America/Argentina/Buenos_Aires')->format('d/m/Y H:i:s') }}
                                    </div>
                                    <div class="text-xs text-muted">
                                        @if ($user->ultimaAsistencia->tipo === 'entrada')
                                            <span class="text-success font-weight-bold"><i class="fas fa-sign-in-alt mr-1"></i>Entrada</span>
                                        @else
                                            <span class="text-danger font-weight-bold"><i class="fas fa-sign-out-alt mr-1"></i>Salida</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted text-xs font-italic">Sin registros</span>
                                @endif
                            </td>

                            <!-- Geolocalización con Link a Google Maps -->
                            <td class="align-middle">
                                @if ($user->ultimaAsistencia && $user->ultimaAsistencia->latitud && $user->ultimaAsistencia->longitud)
                                    <a href="https://www.google.com/maps?q={{ $user->ultimaAsistencia->latitud }},{{ $user->ultimaAsistencia->longitud }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-danger font-weight-bold shadow-xs d-inline-flex align-items-center"
                                       title="Ver ubicación en Google Maps">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        <span>Ver en Mapa</span>
                                    </a>
                                    <div class="text-xs text-muted font-mono mt-1">
                                        {{ number_format($user->ultimaAsistencia->latitud, 5) }}, {{ number_format($user->ultimaAsistencia->longitud, 5) }}
                                    </div>
                                @else
                                    <span class="text-muted text-xs font-italic">No disponible</span>
                                @endif
                            </td>
                            
                            <!-- Roles -->
                            <td class="align-middle">
                                @forelse ($user->roles as $role)
                                    <span class="badge badge-info py-1 px-2 font-weight-bold text-xs uppercase mr-1 shadow-xs">
                                        {{ $role->name }}
                                    </span>
                                @empty
                                    <span class="badge badge-secondary py-1 px-2 font-weight-bold text-xs uppercase mr-1">
                                        Sin Rol
                                    </span>
                                @endforelse
                            </td>
                            
                            <!-- Action button -->
                            <td class="align-middle text-center">
                                <a href="{{ route('asistencia.empleado-perfil', $user->id) }}" 
                                   class="btn btn-primary btn-sm font-weight-bold px-3 shadow-sm">
                                    <i class="fas fa-clock mr-1"></i> Ver Asistencia
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted font-weight-bold">
                                No se encontraron usuarios registrados en el sistema.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if ($users->hasPages())
        <div class="card-footer bg-white d-flex justify-content-center py-3">
            {{ $users->links() }}
        </div>
    @endif
</div>
@stop

@section('css')
    <style>
        .font-mono {
            font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }
    </style>
@stop
