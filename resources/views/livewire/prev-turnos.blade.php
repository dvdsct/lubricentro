<div>

    <div class="row d-flex justify-content-between" style="padding-top: 20px;">
        <div class="col-3 d-flex align-items-center">

            <button wire:click='change_day("yes")' class="btn btn-info btn-sm">
                <i class="fas fa-arrow-left"></i></button>
            <input type="date" wire:model.lazy="fecha" class="form-control">
            <button wire:click='change_day("tmw")' class="btn btn-info btn-sm"><i
                    class="fas fa-arrow-right"></i></button>


        </div>
        <div class="col-3">
            <h1> <strong>{{ ucfirst(Carbon\Carbon::now()->locale('es')->isoFormat('dddd DD ')) }} </strong></h1>
        </div>
        <div class="col-2 pt-2 mr-2">
                <button type="button" class="btn btn-block btn-info" data-target="modal-default" wire:click='openModal'>
                    Nuevo Turno</button>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Striped Full Width Table</h3>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Task</th>
                        <th>Progress</th>
                        <th style="width: 40px">Label</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1.</td>
                        <td>Update software</td>
                        <td>
                            <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-danger">55%</span></td>
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td>Clean database</td>
                        <td>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-warning" style="width: 70%"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-warning">70%</span></td>

                </tbody>
            </table>
        </div>

    </div>
</div>
