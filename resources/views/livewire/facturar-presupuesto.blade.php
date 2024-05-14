<div>
    <div style="display: flex; justify-content: end;">

        <!-- BOTON PARA COBRAR EL PRESUPUESTO GENERADO -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success" style="cursor: pointer;"
                wire:click="$dispatchTo('form-create-order', 'presupuesto', { id: {{ $presupuesto->id }} })">
                <div class="inner">
                    <h3 class="m-0">Cobrar</h3>
                    <p>Unique Visitors</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cash-register"></i>
                </div>
            </div>
        </div>




        <!-- BOTON PARA IMPRIMIR  PRESUPUESTO GENERADO  -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning" style="cursor: pointer;"
                wire:click="$dispatch( 'printPresupuesto')">
                <div class="inner">
                    <h3 class="m-0">Imprimir</h3>
                    <p>Unique Visitors</p>
                </div>
                <div class="icon">
                    <i class="fas fa-print"></i>
                </div>
            </div>
        </div>





        @livewire('form-create-order')
    </div>
</div>
