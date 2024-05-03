<div>
    <!-- MODAL PARA ABRIR CAJA DEL DIA -->
    @can('caja')
        @if ($modalAbrirCaja)
            <div class="modal fade show" id="modal-default" aria-modal="true" role="dialog"
                style="padding-right: 17px; display: block;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-info">
                            <h4 class="modal-title"> <strong>NUEVA CAJA </strong></h4>
                            <button type="button" class="close" aria-label="Close" wire:click="cerrarModal">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div>
                                <h3 class="mb-3"><strong>Cajero: </strong> {{ $perfil->first()->personas->nombre }}</h3>


                                {{ $step }}

                                @if ($step == 1)
                                    <button type="button" class="btn btn-block bg-gradient-success btn-lg mb-3"
                                        wire:click='abrirCaja'>Abrir caja</button>
                                @endif

                                @if ($step == 2)
                                    <!-- INPUT PARA INDICAR MONTO INICIAL DE DINERO -->
                                    <div class="form-group mt-3">
                                        <label for="exampleInputEmail1">Indique monto inicial</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control" wire:model='montoInicial' wire:keydown.enter='abrirCaja'>
                                        </div>
                                    </div>
                                @endif


                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" wire:click="cerrarModal">Cancelar</button>
                            <button type="button" class="btn btn-primary">Aceptar</button>
                        </div>
                    </div>

                </div>

            </div>
        @endif
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
