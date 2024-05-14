<div>
    <div id="{{ $producto->id }}">
        <tr>
            <td>{{ $producto->id }}</td>
            <td>{{ $producto->productos->descripcion . ' - ' . $producto->productos->codigo }}</td>

            @if ($producto->estado == 1)
                <td><input type="text" class="form-control" style="width: 145px;" placeholder="Ingresar cantidad"
                        wire:model='cantidad' wire:keydown.enter='addCantidad({{ $producto->id }})'>
                    @error('cantidad')
                        {{ $message }}
                    @enderror
                </td>

                <td>{{ $producto->precio }}

                </td>
                <td></td>
            @else
                <td>
                    {{ $producto->cantidad }}
                </td>
                <td>
                    $ {{ $producto->precio }}
                </td>
                <td>
                    $ {{ $producto->subtotal }}
                </td>
            @endif



            @if ($producto->estado == 2)
                {{-- Si el producto es estado 2 aun no se a recibido --}}
                <td class="project-actions text-right" style="width: 200px;">
                    <a class="btn btn-info btn-sm" wire:click='editProd({{ $producto->id }})'>
                        <i class="fas fa-pencil-alt">
                        </i>
                        Editar
                    </a>
                    <a class="btn btn-danger btn-sm" wire:click='delProd({{ $producto->id }})'
                        wire:confirm="Si borras este articulo tendras que volver a agregarlo?">
                        <i class="fas fa-trash">
                        </i>
                        Eliminar
                    </a>
                </td>
            @else
                <td class="project-actions text-right">
                    <a class="btn btn-danger btn-sm" wire:click='delProd({{ $producto->id }})'
                        wire:confirm="Si borras este articulo tendras que volver a agregarlo?">
                        <i class="fas fa-trash">
                        </i>
                        Eliminar
                    </a>
            @endif
        </tr>
    </div>
</div>
