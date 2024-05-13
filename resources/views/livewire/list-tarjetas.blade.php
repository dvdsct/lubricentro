<div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <button type="button" class="btn btn-success" wire:click='modalOn'>
                        <i class="fas fa-plus-circle"></i> Agregar Tarjeta
                    </button>
                </div>
                <div class="input-group" style="width: 300px;">
                    <input type="text" class="form-control" placeholder="Buscar tarjeta">
                    <div class="input-group-append">
                        <button class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Tarjeta</th>
                        <th style="width: 150px">Descuento</th>
                        <th style="width: 150px">Interes</th>
                        <th>Plan Cuotas</th>
                        <th style="width: 100px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($planes as $plan)
                        <tr>
                            <td>1.</td>
                            <td>{{ $plan->tarjetas->nombre_tarjeta }}</td>
                            @if ($plan->estado == 1)
                                <td>
                                    <span class="badge bg-primary ">{{ $plan->descuento }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-danger">{{ $plan->interes }}</span>
                                </td>
                            @elseif ($plan->tarjetas->estado == 2)
                                <td>
                                    <div class="col">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" wire:model='descuento'>
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    @error('descuento')
                                        <span class="text-danger">*{{ $message }}</span>
                                    @enderror
                                </td>
                                <td>
                                    <div class="col">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" wire:model='interes' wire:keydown.enter='stTarjeta({{ $plan->id }})'>
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    @error('interes')
                                        <span class="text-danger">*{{ $message }}</span>
                                    @enderror
                                </td>
                            @endif

                            <td>
                                    {{ $plan->nombre_plan }} -
                                    <strong class="badge bg-secondary"> {{ $plan->descripcion_plan }} </strong>
                            </td>
                            <td class="project-actions text-right">

                                <button class="btn btn-info btn-sm" wire:click='editTarjeta({{ $plan->id }})'>
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
