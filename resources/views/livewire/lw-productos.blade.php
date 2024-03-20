<div>

    <table class="table table-striped">
        <thead>
            <th>codigo</th>
                <th>Descripcion</th>
                <th>Precio</th>
                <th>Proveedor</th>
        </thead>
        <tbody>
            @foreach ($productos as $p)
        <tr>

            <td>{{ $p->codigo }}</td>
            <td>{{ $p->descripcion }}</td>
            <td>{{ $p->costo }}</td>
            <td>{{ $p->proveedores->perfiles->personas->nombre }}</td>

        </tr>

            @endforeach
        </tbody>
    </table>

</div>
