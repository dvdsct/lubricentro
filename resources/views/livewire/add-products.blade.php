<div>


    <div class="card">
        <div class="card-header">



            <button type="button" class="btn btn-tool" wire:click='modalProdOn'>
                <i class="fas fa-minus"></i>++
            </button>

        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Repuesto</th>
                        <th>Codigo</th>
                        <th>Cantidad</th>
                        <th style="width: 40px">Precio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orden->items as $i)
                        <tr>
                            <td>{{ $i->id }}</td>
                            <td>{{$i->productos->descripcion}}</td>
                            <td>
                                WO-059

                            </td>
                            @if ($i->estado == 1)
                                <td><input type="text" wire:model='cantidad'
                                        wire:keydown.enter='addCantidad({{ $i->id }})'></td>
                            @endif
                                    <td>
                                       {{ $i->cantidad}}
                                    </td>
                                    
                                    
                                    <td>
                                        
                                        {{ $i->subtotal}}
                            </td>
                        </tr>
                    @endforeach
          
                </tbody>
        
            </table>

            

        </div>
        <div class="card-header justify-content-between">
            <h3>TOTAL</h3>
            <h3>{{$total}}</h3>

        </div>

    </div>



    @if ($modal == true)
        <div class="modal fade show" id="modal-lg" style="display: block; padding-right: 17px;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text"wire:model='query' wire:keydown='search'
                                class="form-control float-right" placeholder="Search">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="close" wire:click='modalProdOff'>
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <table class="table table-bordered  table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Repuesto</th>
                                    <th>Codigo</th>
                                    <th>Cantidad</th>
                                    <th style="width: 40px">Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stock as $i)
                                    <tr wire:click='addedProduct({{ $i->id }})'>
                                        <td>{{ $i->id }}</td>
                                        <td>{{ $i->descripcion }}</td>
                                        <td>{{ $i->costo }}</td>
                                        <td>{{ $i->codigo }}</td>

                                        <td><span class="badge bg-success">{{ $i->cantidad }}</span></td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>












                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" wire:click='modalProdOff'>Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>

            </div>

        </div>
    @endif



</div>
