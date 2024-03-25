<div>
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title"> <strong> VEHICULO </strong> </h3>
        </div>

        <div class="card-body">
            <strong> <i class="fas fa-car mr-1"></i> Tipo</strong>
            <p class="text-muted">
                {{ $vehiculo->modelos->descripcion }}
            </p>
            <hr>
            <strong><i class="fas fa-industry mr-1"></i> Marca</strong>
            <p class="text-muted"></p>                {{ $vehiculo->modelos->marca->descripcion }}

            <hr>
            <strong><i class="fas fa-cogs mr-1"></i> Modelo</strong>
            <p class="text-muted">
                {{ $vehiculo->modelos->tipos->descripcion }}
            </p>
            <hr>
            <strong><i class="far fa-file-alt mr-1"></i> Dominio</strong>
            <p class="text-muted">{{ $vehiculo->dominio }}
            </p>
        </div>

    </div>
</div>
