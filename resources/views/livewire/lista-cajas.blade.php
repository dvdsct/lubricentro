<div>

    @can('caja')
        <div class="modal fade show" id="modal-default" aria-modal="true" role="dialog"
            style="padding-right: 17px; display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Default Modal</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <h3>{{ $perfil->first()->personas->nombre }}</h3>

                        <div class="info-box mb-3 bg-warning" wire:click='abrirCaja'>
                            <span class="info-box-icon"><i class="fas fa-tag"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Inventory</span>
                                <span class="info-box-number">5,200</span>
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
    @endcan

    @can('admin')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">

                        <div class="card-tools">
                            <div class="input-group input-group-sm" style="width: 300px;">
                                <input type="text" wire:model='query' class="form-control float-right"
                                    placeholder="Buscar pedido" wire:keydown='search'>
                                <div class="input-group-append">
                                    <button wire:keydown.enter='search' class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card-body table-responsive p-0">
                        <table class="table table-striped projects">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>FECHA</th>
                                    <th>CAJERO</th>
                                    <th>ESTADO</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cajas as $c)
                                    <tr>
                                        <td>{{ $c->id }}</td>
                                        <td>{{ \Carbon\Carbon::parse($c->created_at)->format('d/m/Y') }}</td>

                                        <td><span class="tag tag-success">{{ $c->estado }}</span></td>
                                        <td>{{ $c->ingresos }}</td>
                                        <td class="project-actions text-right">
                                            <a class="btn btn-primary btn-sm" href="{{ route('venta.show', $c->id) }}">
                                                <i class="fas fa-list">
                                                </i>
                                            </a>
                                            <a class="btn btn-info btn-sm" href="{{ route('venta.edit', $c->id) }}">
                                                <i class="fas fa-truck"></i>
                                            </a>
                                            <a class="btn btn-danger btn-sm" href="{{ route('venta.destroy', $c->id) }}">
                                                <i class="fas fa-trash">
                                                </i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    @endcan


</div>