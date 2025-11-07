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

    <div class="row" style="display: flex; justify-content: end; margin: 15px 0;">
        <div class="col-lg-4" style="padding: 0 5px;">
            <a href="{{ route('pdf.orden', $orden->id) }}" target="_blank" class="text-decoration-none">
                <div class="small-box bg-warning" style="cursor: pointer; height: 100%;">
                    <div class="inner" style="padding: 15px 10px;">
                        <h4 style="margin: 0 0 5px 0; font-size: 1.2rem;">Imprimir</h4>
                        <p style="margin: 0; font-size: 0.9rem;">Orden de trabajo</p>
                    </div>
                    <div class="icon" style="font-size: 2.5rem;">
                        <i class="fas fa-print"></i>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4" style="padding: 0 5px;">
            <a href="{{ route('pdf.orden.limpia') }}" target="_blank" class="text-decoration-none">
                <div class="small-box bg-info" style="cursor: pointer; height: 100%;">
                    <div class="inner" style="padding: 15px 10px;">
                        <h4 style="margin: 0 0 5px 0; font-size: 1.2rem;">Imprimir</h4>
                        <p style="margin: 0; font-size: 0.9rem;">Orden Limpia</p>
                    </div>
                    <div class="icon" style="font-size: 2.5rem;">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
