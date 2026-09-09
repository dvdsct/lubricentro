<div>
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title"> <strong> VEHICULO </strong> </h3>
        </div>

        <div class="card-body">
            <strong> <i class="fas fa-car mr-1"></i> Tipo</strong>
            <p class="text-muted">
                {{ $vehiculo->modelos->tipos->descripcion }}

            </p>
            <hr>
            <strong><i class="fas fa-industry mr-1"></i> Marca</strong>
            <p class="text-muted">
            {{ $vehiculo->modelos->marcas->descripcion }}
            </p>

            <hr>
            <strong><i class="fas fa-cogs mr-1"></i> Modelo</strong>
            <p class="text-muted">
                {{ $vehiculo->modelos->descripcion }}

            </p>
            <hr>
            <strong><i class="far fa-file-alt mr-1"></i> Dominio / Patente</strong>
            <p class="text-muted">
                <span class="badge bg-orange text-white" style="font-size: 0.95rem;">{{ $vehiculo->dominio ?? '-' }}</span>
            </p>
        </div>

        @if ($vehiculo)
        <div class="card-footer p-2 text-center bg-transparent">
            <a href="{{ route('vehiculos.perfil', $vehiculo->id) }}" class="btn btn-sm btn-outline-info btn-block">
                <i class="fas fa-edit mr-1"></i> Modificar Patente / Vehículo
            </a>
        </div>
        @endif
    </div>
</div>
