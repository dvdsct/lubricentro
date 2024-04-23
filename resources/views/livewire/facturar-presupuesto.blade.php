<div>
    <div class="info-box bg-warning d-flex align-items-center justify-content-center"
        wire:click="$dispatchTo('form-create-order', 'presupuesto', { id: {{ $presupuesto->id }} })" style="cursor: pointer;">
        <span class="info-box-icon"><i class="fas fa-cash-register"></i></span>
        <div class="info-box-content">
            <h4 class="info-box-text m-0"> <strong> Crear Orden </strong> </h4>
            <span class="info-box-number"></span>
        </div>
    </div>
    @livewire('form-create-order')
</div>
