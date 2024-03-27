<!DOCTYPE html>
<html>

<head>    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Orden de Trabajo</title>


</head>

<body>

   <div >
    <h1>Orden de Trabajo</h1>


    <p><strong>Fecha y Hora:</strong> {{ $fecha }}</p>
    <p><strong>Encargado:</strong> {{ $encargado->nombre }} {{ $encargado->apellido }}</p>
    <p><strong>Vendedor:</strong> {{ $vendedor->name }}</p>
   </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Item</th>
                <th>Cantidad</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item['id'] }}</td>
                    <td>{{ $item['cantidad'] }}</td>
                    <td>{{ $item->productos->descripcion }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>
    <br>
    <br>
    <div class="row">
        <div class="col-6">
            <p>___________________________</p>
            <p>Encargado</p>
        </div>

    </div>







    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
</body>

</html>
