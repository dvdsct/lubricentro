<div>

    <div class="row pb-3" style="display:flex; align-items:flex-end">
        <div class='col-md-2'><button type="button" class="btn btn-block btn-success"
                wire:click="$dispatchTo('add-supplier-order', 'modalSupOrder')"> <i class="fas fa-plus-circle"></i> Nuevo
                Pedido</button>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lista de pedidos</h3>
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
                                <th>PROVEEDOR</th>
                                <th>FECHA PEDIDO</th>
                                <th>ESTADO</th>
                                <th>DESCRIPCION</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pedidos as $p)
                                <tr>
                                    <td>{{ $p->id }}</td>
                                    <td>{{ $p->proveedores->perfiles->personas->nombre . ' ' . $p->proveedores->perfiles->personas->apellido }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($p->fecha_ingreso)->format('d/m/Y') }}</td>

                                    <td><span class="tag tag-success">{{ $p->estado }}</span></td>
                                    <td>{{ $p->descripcion }}</td>
                                    <td class="project-actions text-right">
                                        <a class="btn btn-primary btn-sm" href="{{route('pedidos.show',$p->id)}}">
                                            <i class="fas fa-list">
                                            </i>
                                        </a>
                                        <a class="btn btn-info btn-sm" href="{{route('pedidos.edit',$p->id)}}">
                                        <i class="fas fa-truck"></i>
                                        </a>
                                        <a class="btn btn-danger btn-sm" href="{{route('pedidos.destroy',$p->id   )}}">
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
</div>
