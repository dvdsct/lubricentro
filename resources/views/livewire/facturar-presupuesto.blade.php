<div>
    <div class="info-box bg-primary d-flex align-items-center justify-content-end" style="width: 30%; cursor: pointer;"
        wire:click="$dispatchTo('form-create-order', 'presupuesto', { id: {{ $presupuesto->id }} })" style="cursor: pointer;">
        <span class="info-box-icon"><i class="fas fa-calculator"></i>


</span>
        <div class="info-box-content">
            <h4 class="info-box-text m-0"> <strong> Presupuestar </strong> </h4>
            <span class="info-box-number"></span>
        </div>
    </div>
    @livewire('form-create-order')
</div>
