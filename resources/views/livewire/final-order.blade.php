<div>

        @if ($orden->estado == 100)
        .
        @else
        <div class="info-box bg-warning d-flex align-items-center justify-content-center" wire:click='$dispatchTo("form-pago","formPago")' style="cursor: pointer;">
            <span class="info-box-icon"><i class="fas fa-cash-register"></i></span>
            <div class="info-box-content">
                <h4 class="info-box-text m-0"> <strong> Cobrar </strong> </h4>
                <span class="info-box-number"></span>
            </div>
        </div>
    @endif

            <a href="{{ route('pdf', $orden->id) }}" target="_blank">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-print"></i></span>
                    <div class="info-box-content">
                        <h4 class="info-box-text m-0"> <strong> Imprimir Orden </strong> </h4>
                        <span class="info-box-number"></span>
                    </div>
                </div>
            </a>
</div>
