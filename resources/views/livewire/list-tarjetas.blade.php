<div>
    <div class="card">
        <div class="card-header">
            <!--  <h3 class="card-title">Tarjetas</h3> -->
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Tarjeta</th>
                        <th style="width: 200px">Descuento</th>
                        <th style="width: 200px">Interes</th>
                        <th>Plan Cuotas</th>
                        <th style="width: 100px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tarjetas as $tarjeta)
                        <tr>
                            <td>1.</td>
                            <td>{{ $tarjeta->nombre_tarjeta }}</td>
                            @if ($tarjeta->estado == 1)
                                <td>
                                    <span class="badge bg-primary ">{{ $tarjeta->descuento }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-danger">{{ $tarjeta->interes }}</span>
                                </td>
                            @elseif ($tarjeta->estado == 2)
                                <td>
                                    <div class="col">
                                        <input type="text" class="form-control" placeholder="descuento"
                                            wire:model='descuento' wire:keydown.enter='stTarjeta({{ $tarjeta->id }})'>
                                    </div>
                                    @error('descuento')
                                    <span class="text-danger">*{{ $message }}</span>
                                @enderror
                                </td>
                                <td>
                                    <div class="col">
                                        <input type="text" class="form-control" placeholder="interes"
                                            wire:model='interes' wire:keydown.enter='stTarjeta({{ $tarjeta->id }})'>
                                    </div>
                                    @error('interes')
                                        <span class="text-danger">*{{ $message }}</span>
                                    @enderror
                                </td>
                            @endif

                            <td>
                                @foreach ($tarjeta->planes as $plan)
                                    {{ $plan->nombre_plan }} -
                                    <strong class="badge bg-secondary"> {{ $plan->descripcion_plan }} </strong>
                                @endforeach
                            </td>
                            <td class="project-actions text-right">

                                <button class="btn btn-info btn-sm" wire:click='editTarjeta({{ $tarjeta->id }})'>
                                    <i class="fas fa-pencil-alt">
                                    </i>

                                </button>
                                <a class="btn btn-danger btn-sm" wire:click='delTarjeta'>
                                    <i class="fas fa-trash">
                                    </i>

                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            <ul class="pagination pagination-sm m-0 float-right">
                <li class="page-item"><a class="page-link" href="#">«</a></li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">»</a></li>
            </ul>
        </div>
    </div>
</div>
