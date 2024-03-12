<div>

    <div class="col-md-4">


        <div class="info-box mb-3 bg-warning btn" wire:click='$dispatchTo("form-pago","formPago")'>
            <span class="info-box-icon"><i class="fas fa-tag"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Cobrar</span>
                <span class="info-box-number"></span>
            </div>

        </div>
        <a href="{{ route('pdf', $orden->id) }}">

            <div class="info-box mb-3 bg-success">
                <span class="info-box-icon"><i class="fas fa-cloud-download-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Imprimir Orden</span>
                    <span class="info-box-number"></span>
                </div>

            </div>
        </a>
    </div>
</div>
