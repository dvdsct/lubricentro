<div>
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
                    <input type="text" class="form-control" placeholder="descuento" wire:model='descuento'
                        wire:keydown.enter='stTarjeta'>
                </div>
            </td>
            <td>
                <div class="col">
                    <input type="text" class="form-control" placeholder="interes" wire:model='interes'
                        wire:keydown.enter='stTarjeta'>
                </div>
            </td>
        @endif

        <td>
            @foreach ($tarjeta->planes as $plan)
                {{ $plan->nombre_plan }} -
                <strong class="badge bg-secondary"> {{ $plan->descripcion_plan }} </strong>
            @endforeach
        </td>
        <td class="project-actions text-right">

            <a class="btn btn-info btn-sm" wire:click='editTarjeta'>
                <i class="fas fa-pencil-alt">
                </i>

            </a>
            <a class="btn btn-danger btn-sm" wire:click='delTarjeta'>
                <i class="fas fa-trash">
                </i>

            </a>
        </td>
    </tr>
</div>
