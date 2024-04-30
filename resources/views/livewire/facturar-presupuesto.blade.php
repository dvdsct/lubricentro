<div style="display: flex; justify-content: end;">

<!-- BOTON PARA COBRAR EL PRESUPUESTO GENERADO -->
    <div class="info-box bg-success d-flex align-items-center justify-content-end" style="width: 25%; cursor: pointer;"
        wire:click="$dispatchTo('form-create-order', 'presupuesto', { id: {{ $presupuesto->id }} })" style="cursor: pointer;">
        <span class="info-box-icon"><i class="fas fa-cash-register"></i></span>

        <div class="info-box-content">
            <h4 class="info-box-text m-0"> <strong> Cobrar </strong> </h4>
            <span class="info-box-number"></span>
        </div>
    </div>

    <!-- BOTON PARA IMPRIMIR  PRESUPUESTO GENERADO  -->
    <div class="info-box bg-warning d-flex align-items-center justify-content-end ml-3" style="width: 25%; cursor: pointer;">
        <span class="info-box-icon"><i class="fas fa-print"></i></span>
        <div class="info-box-content">
            <h4 class="info-box-text m-0"> <strong> Imprimir </strong> </h4>
            <span class="info-box-number"></span>
        </div>
    </div>



    @livewire('form-create-order')
</div>
