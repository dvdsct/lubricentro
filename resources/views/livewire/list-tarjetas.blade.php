<div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <!-- Botón para abrir el modal -->
                    <button type="button" class="btn btn-success" wire:click="abrirModal">
                        <i class="fas fa-plus-circle"></i> Agregar Tarjeta
                    </button>
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
                        <td>{{$plan->id}}</td>

                        <td>{{ $plan->tarjetas->nombre_tarjeta }}</td>

                        {{-- Muetra descuento e interes --}}
                        @if ($plan->estado == 1)
                        <td>
                            <span class="badge bg-primary ">{{ $plan->descuento }} %</span>
                        </td>
                        <td>
                            <span class="badge bg-danger">{{ $plan->interes }} %</span>
                        </td>

                        @else
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
                            <a class="btn btn-danger btn-sm" wire:click='delTarjeta({{ $plan->id }})' wire:confirm="¿Esta seguro de que desea eliminar este registro?">
                                <i class="fas fa-trash"></i>
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

    <!-- MODAL PARA AGREGAR NUEVA TARJETA -->

    @if ($modal == true)
    <div class="modal fade show" id="modal-default" style="display: block; padding-right: 17px;" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title"> <strong> AGREGAR NUEVA TARJETA </strong></h4>
                    <button type="button" class="close" wire:click="cerrarModal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tarjeta">Tarjeta </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-credit-card"></i></span>
                            </div>

                            <input type="text" class="form-control" id="tarjeta">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tarjeta">Descuento </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="tarjeta">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tarjeta">Interes </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="tarjeta">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tarjeta">Plan </label>
                                <input type="text" class="form-control" id="tarjeta">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tarjeta">Cuotas </label>
                                <input type="text" class="form-control" id="tarjeta">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" wire:click="cerrarModal">Cancelar</button>
                    <button type="button" class="btn btn-success">Aceptar</button>
                </div>
            </div>

        </div>

    </div>
    @endif
</div>