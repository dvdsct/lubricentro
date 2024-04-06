<div class="pt-3">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"> <strong> STOCK </strong> </h3>
            <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 300px;">
                    <input type="text" wire:model='query' wire:keydown='search' class="form-control float-right"
                        placeholder="Buscar producto">
                    <div class="input-group-append">
                        <button class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Unidad</th>
                        <th>Cantidad</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stock as $p)
                        @if ($p->cantidad < $p->ideal)
                            <tr class="bg-warning">
                        @endif

                        <td>{{ $p->productos->descripcion }}</td>
                        <td>{{ $p->unidad }}</td>

                        <td>
                            @if ($p->estado == '1')
                                {{ $p->cantidad }}
                            @else
                                <input type="text" class="form-control" style="width: 145px;"
                                    placeholder="Ingresar cantidad" wire:model='cantidad'
                                    wire:keydown.enter='addCantidad({{ $p->id }})'>
                                @error('cantidad')
                                    {{ $message }}
                                @enderror
                            @endif
                        </td>

                        <td><a class="btn btn-info" wire:click='editPStock({{ $p->id }})'>
                                Editar</a></td>


                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

    </div>
    @script
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <script>
    var pusher = new Pusher('34bb2b93c35d582e91b2', {
      cluster: 'us2'
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
      alert(JSON.stringify(data));
    });
</script>
    @endscript
</div>
