<div>

    @if ($modal == true)
        <div class="modal fade show" id="modal-lg" style="display: block; padding-right: 17px;" aria-modal="true"
            role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Large Modal</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 row">
                            <label for="inputCliente" class="col-sm-2 col-form-label">Cliente</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputCliente">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>

            </div>

        </div>
    @endif






</div>
