<div>

    <div class="row pb-3" style="display:flex; align-items:flex-end">
        <div class='col-md-2'><button type="button" class="btn btn-block btn-success"
            wire:click="$dispatchTo('add-supplier-order', 'modalSupOrder')">Nuevo Pedido</button>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fixed Header Table</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" wire:keydown='search' wire:model='query'
                                class="form-control float-right" placeholder="Search">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body table-responsive p-0" style="height: 300px;">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pedidos as $p)
                                <tr>
                                    <td>{{ $p->id }}</td>
                                    <td>{{ $p->proveedores->perfiles->personas->nombre . ' ' . $p->proveedores->perfiles->personas->apellido }}
                                    </td>
                                    <td>{{ $p->fecha_ingreso }}</td>
                                    <td><span class="tag tag-success">{{ $p->estado }}</span></td>
                                    <td>{{ $p->descripcion }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>
</div>
