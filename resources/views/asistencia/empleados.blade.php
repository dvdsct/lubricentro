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
                        <th>Roles</th>
                        <th class="text-center" style="width: 200px;">Acción</th>
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
                                {{ $user->name }}
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
                            <td colspan="5" class="text-center py-5 text-muted font-weight-bold">
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
