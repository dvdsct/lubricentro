<div>
    <table class="table table-striped">
        <thead>
            <th>Orden</th>
            <th>Cliente</th>
            <th>Medio de Pago</th>
            <th></th> 
        </thead>
        <tbody>
            @foreach ($facturas as $f)
            <tr>
                <td>{{$f->orden_id}}</td>
                <td>{{$f->ordenes->clientes->perfiles->personas->nombre}} {{$f->ordenes->clientes->perfiles->personas->apellido}}</td>
                <td>{{$f->pagos->first()->medios->descripcion ?? $f->pagos->first()->tipos->descripcion}}</td>
                <td>{{$f->total}}</td>
            </tr>
                
            @endforeach
        </tbody>
    </table>

    <div>
        <h2>TOTAL {{$totalv}}</h2>
    </div>
    </div>
