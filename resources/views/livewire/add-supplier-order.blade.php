<div>
    @if ($modal == true)



    <div class="modal fade show" id="modal-default" style="display: block; padding-right: 17px;" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="supplierOrderModalLabel">Add Supplier Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <form wire:submit.prevent="continueForm">
                        <div class="mb-3">
                            <label for="provider" class="form-label">Select Provider</label>
                            <select id="provider" class="form-select" wire:model="selectedProvider">
                                <option value="">Select a provider</option>
                                @for ($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">Provider {{ $i }}</option>
                                @endfor
                            </select>
                            @error('selectedProvider')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="date_ingreso" class="form-label">Date of Ingress</label>
                            <input type="date" class="form-control" id="date_ingreso" wire:model="date_ingreso">
                            @error('date_ingreso')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Select Type</label>
                            <select id="type" class="form-select" wire:model="selectedType">
                                <option value="">Select a type</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}">Type {{ $i }}</option>
                                @endfor
                            </select>
                            @error('selectedType')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="supplierOrderForm" wire:click="continueForm">Continue</button>
                </div>
            </div>
        </div>
    </div>

    @endif
</div>
