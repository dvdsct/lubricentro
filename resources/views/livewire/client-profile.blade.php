<div>
    <div class="row">
        <div class="col-12">
            <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left mr-1"></i> Volver a Clientes
            </a>
        </div>
        
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center mb-3">
                        <i class="fas fa-user-circle fa-5x text-muted"></i>
                    </div>
                    <h3 class="profile-username text-center">
                        <strong>
                            {{ optional(optional($cliente->perfiles)->personas)->nombre }}
                            {{ optional(optional($cliente->perfiles)->personas)->apellido }}
                        </strong>
                    </h3>
                    <p class="text-muted text-center">Cliente registrado</p>
                    
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>DNI</b> <a class="float-right text-dark">{{ optional(optional($cliente->perfiles)->personas)->DNI ?? '-' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Teléfono</b> <a class="float-right text-dark">{{ optional(optional($cliente->perfiles)->personas)->numero_telefono ?? 'No registrado' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Fecha de Nacimiento</b> 
                            <a class="float-right text-dark">
                                {{ optional(optional($cliente->perfiles)->personas)->fecha_nac ? \Carbon\Carbon::parse($cliente->perfiles->personas->fecha_nac)->format('d/m/Y') : 'No registrada' }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title m-0"><strong>Vehículos Registrados</strong></h4>
                </div>
                <div class="card-body p-0">
                    @if ($cliente->vehiculos->count())
                        <div class="table-responsive">
                            <table class="table table-hover table-striped m-0">
                                <thead>
                                    <tr>
                                        <th>Vehículo</th>
                                        <th>Año</th>
                                        <th>Patente (Dominio)</th>
                                        <th class="text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cliente->vehiculos as $v)
                                        <tr>
                                            <td class="align-middle">
                                                <strong>{{ optional(optional($v->modelos)->marcas)->descripcion ?? '' }}</strong>
                                                {{ optional($v->modelos)->descripcion ?? '' }}
                                            </td>
                                            <td class="align-middle">{{ $v->año ?? '-' }}</td>
                                            <td class="align-middle">
                                                <span class="badge bg-orange text-white" style="font-size: 0.9rem; padding: 5px 10px;">
                                                    {{ $v->dominio }}
                                                </span>
                                            </td>
                                            <td class="text-right align-middle">
                                                <a href="{{ route('vehiculos.perfil', $v->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-history mr-1"></i> Historial y Detalles
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-car-crash fa-3x mb-3 text-gray-300"></i>
                            <p class="m-0">No se encontraron vehículos registrados para este cliente.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
