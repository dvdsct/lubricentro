<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}
    <title>PDF - Presupuesto</title>


</head>


<body>
    <div style="margin-bottom: 6px;">
        <table style="border-collapse: collapse; width: 100%;">
            <tr>
                <td colspan="2" class="logo" style="border: 1px solid black; padding: 8px; text-align: left;">
                    <h1 style="font-family: Arial, Helvetica, sans-serif;">Presupuesto N° X</h1> <!-- AGREGAR ID DEL PRESUPUESTO -->
                </td>

                <td class="logo" style="border: 1px solid black; display:flex; justify-content:center;">
                       <img src="{{ asset('img/logo.png') }}" alt="Logo Rocket" style="width: 200px;">
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid black;">
                    <p><strong>Cliente:</strong> </p> <!-- AGREGAR NOMBRE Y APELLIDO DE CLIENTE  -->
                </td>

                <td style="border: 1px solid black;">
                    <p> <strong>Vendedor: </strong> </p> <!-- AGREGAR CAJERO  -->
                </td>

                <td style="border: 1px solid black;">
                    <p><strong>Fecha de emision:</strong> {{ $fecha }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 100px;">
    <table class="table table-striped" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="border: 1px solid black;">Item</th>
            <th style="border: 1px solid black;">Producto</th>
            <th style="border: 1px solid black;">Cantidad</th>
            <th style="border: 1px solid black;">Precio unitario</th>
            <th style="border: 1px solid black;">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
        <tr>
            <td style="border: 1px solid black;">{{ $item['id'] }}</td>
            <td style="border: 1px solid black;">{{ $item->productos->descripcion }} {{ $item->productos->codigo }}</td>
            <td style="border: 1px solid black;">{{ $item['cantidad'] }}</td>
            <td style="border: 1px solid black;">$ {{ $item->productos->precio_venta}}</td>
            <td style="border: 1px solid black;">$ {{ $item->subtotal }}</td>
        </tr>
        @endforeach
    </tbody>
</table>


        <div>
            <h2 style="text-align: right; font-family: Arial, Helvetica, sans-serif;"><strong>TOTAL ${{ $total ?? '0.00' }}</strong></h2>
        </div>
    </div>

    <br>
    <br>
    <div>
    <div style="display: inline-block;">
                <p>___________________________</p>
                <p style="text-align: center;">FIRMA VENDEDOR</p>
            </div>
    </div>


</body>

</html>