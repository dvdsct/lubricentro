<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Orden de Trabajo</title>


</head>

<body>

    <div class="mb-6">
        <table style="border-collapse: collapse; width: 100%;">
            <tr>
                <td colspan="2" class="logo" style="border: 1px solid black; padding: 8px; text-align: left;">
                    <h1>Orden de Trabajo N° 32132132132</h1>   <!-- AGREGAR ID DE LA ORDEN -->
                </td>

                <td class="logo" style="border: 1px solid black; display:flex; justify-content:center;">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo Rocket" style="width: 200px;">
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid black;">
                    <p><strong>Cliente:</strong> </p>   <!-- AGREGAR NOMBRE Y APELLIDO DE CLIENTE  -->
                </td>

                <td style="border: 1px solid black;">
                    <p> <strong>Vehiculo: </strong> </p> <!-- AGREGAR MARCA MODELO Y PATENTE DE AUTO DEL CLIENTE  -->
                </td>

                <td style="border: 1px solid black;">
                    <p><strong>Fecha y Hora:</strong> {{ $fecha }}</p>
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid black;">
                    <p><strong>Operario:</strong> {{ $encargado->nombre }} {{ $encargado->apellido }}</p>
                </td>

                <td style="border: 1px solid black;">
                    <p><strong>Vendedor:</strong> {{ $vendedor->name }}</p>
                </td>

                <td style="border: 1px solid black;">
                    <p><strong>Sector:</strong> </p>   <!-- AGREGAR SECTOR DE LA ORDEN -->
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 100px;">
    <table class="table table-striped" style="border: 1px solid black; border-radius: 20px;">
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
    </div>

    <hr>
    <br>
    <br>
    <div class="row" style="justify-content: end;">
        <div class="col-6" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
            <p>___________________________</p>
            <p>Firma operario</p>
        </div>

    </div>







    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
</body>

</html>