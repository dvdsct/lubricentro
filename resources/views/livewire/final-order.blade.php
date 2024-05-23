<div>




    <div class="row" style="display: flex; justify-content: end;">
        @if ($orden->estado == 100)
        .
        @else
        <div class="col-lg-8">
            <div class="small-box bg-success" style="cursor: pointer;" wire:click='$dispatchTo("form-pago","formPago",{ tipo: "orden" })'>
                <div class="inner">
                    <h3 class="m-0">Cobrar</h3>
                    <p> ${{$total}}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cash-register"></i>
                </div>
            </div>
        </div>

        @endif
    </div>

    <div class="row" style="display: flex; justify-content: end;">
        <div class="col-lg-8">
            <a href="{{ route('pdf.orden', $orden->id) }}" target="_blank">
                <div class="small-box bg-warning" style="cursor: pointer;">
                    <div class="inner">
                        <h3 class="m-0">Imprimir</h3>
                        <p>Orden de trabajo</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-print"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
