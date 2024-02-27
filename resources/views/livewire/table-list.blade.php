<div>
    <table>
        <thead>
            @foreach ($head as $h)
                <th>{{ $h }}</th>
            @endforeach
        </thead>
        <tbody>
            @foreach ($list as $row)
        <tr>

            <td>{{ $row[$head[1]] }}</td>
            <td>{{ $row[$head[0]] }}</td>

        </tr>

            @endforeach
        </tbody>
    </table>


</div>
