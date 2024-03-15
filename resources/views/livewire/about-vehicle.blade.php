<div>
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Vehiculo</h3>
        </div>

        <div class="card-body">
            <strong><i class="fas fa-book mr-1"></i> Tipo</strong>
            <p class="text-muted">
                {{ $vehiculo->tipoVehiculos->descripcion }} </p>
            <hr>
            <strong><i class="fas fa-map-marker-alt mr-1"></i> Marca</strong>
            <p class="text-muted">{{ $vehiculo->marcas->descripcion }}</p>
            <hr>
            <strong><i class="fas fa-pencil-alt mr-1"></i> Modelo</strong>
            <p class="text-muted">
                {{ $vehiculo->modelos->descripcion }}
            </p>
            <hr>
            <strong><i class="far fa-file-alt mr-1"></i> Dominio</strong>
            <p class="text-muted">{{ $vehiculo->dominio }}
            </p>
        </div>

    </div>
</div>
