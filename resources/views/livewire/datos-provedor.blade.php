<div>
    <div class="card card-outline card-info shadow-sm mb-3">
        <div class="card-header py-2 d-flex align-items-center justify-content-between">
            <h3 class="card-title font-weight-bold mb-0">
                <i class="fas fa-truck mr-1"></i>
                PROVEEDOR: {{ mb_strtoupper($proveedor?->nombre_fantasia ?: ($proveedor?->perfiles?->personas?->nombre ?? 'PROVEEDOR')) }}
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body py-2 px-3">
            <div class="row text-sm">
                <div class="col-md-2 col-sm-6 mb-1">
                    <span class="text-muted d-block"><strong>CUIT:</strong></span>
                    <span>{{ $proveedor?->cuit ?: '-' }}</span>
                </div>
                <div class="col-md-2 col-sm-6 mb-1">
                    <span class="text-muted d-block"><strong>RUBRO / TIPO:</strong></span>
                    <span>{{ $proveedor?->rubro ?: ($proveedor?->tipo ?: '-') }}</span>
                </div>
                <div class="col-md-2 col-sm-6 mb-1">
                    <span class="text-muted d-block"><strong>TELÉFONO:</strong></span>
                    <span>{{ $proveedor?->telefono ?: '-' }}</span>
                </div>
                <div class="col-md-3 col-sm-6 mb-1">
                    <span class="text-muted d-block"><strong>EMAIL:</strong></span>
                    <span>{{ $proveedor?->email ?: ($proveedor?->perfiles?->first()?->personas?->correos?->first()?->direccion ?? '-') }}</span>
                </div>
                <div class="col-md-3 col-sm-12 mb-1">
                    <span class="text-muted d-block"><strong>DIRECCIÓN:</strong></span>
                    <span>{{ $proveedor?->direccion ?: '-' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
