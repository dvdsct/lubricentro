<div>
    <div class="row">
        <div class="col-6">
            <table>
                <thead>
                    <th>Vehiculo</th>
                    <th></th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach ($turnlav as $t)
                        <tr>
                            <td>{{ $t->vehiculos }}</td>
                            <td>{{ $t->servicios }}</td>
                            <td>{{ $t->vehiculos }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-6">
            <table>
                <thead>
                    <th></th>
                    <th></th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach ($turnlav as $t)
                        <tr>
                            <td>{{ $t->vehiculos }}</td>
                            <td>{{ $t->servicios }}</td>
                            <td>{{ $t->vehiculos }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
