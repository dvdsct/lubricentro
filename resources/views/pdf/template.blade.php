<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}
    <title>Orden de Trabajo</title>


</head>


<body>

    <div style="margin-bottom: 6px;">
        <table style="border-collapse: collapse; width: 100%;">

            <tr>
                <td colspan="2" class="logo" style="border: 1px solid black; padding: 8px; text-align: left; font-family: Arial , Helevetica, sans-serif">
                    <h1>Orden de Trabajo N° 0000-{{ $orden->id }}</h1>   <!-- AGREGAR ID DE LA ORDEN -->
                </td>

                <td class="logo" style="border: 1px solid black; display:flex; justify-content:center;">
                    <img src="{{ asset('img/logo.svg') }}" alt="Logo Rocket" style="width: 200px;">
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid black;">
                    <p><strong>Cliente: </strong>{{ $encargado->nombre }} {{ $encargado->apellido }} </p> <!-- AGREGAR NOMBRE Y APELLIDO DE CLIENTE  -->
                </td>

                <td style="border: 1px solid black;">
                    <p> <strong>Vehiculo: </strong> {{ $vehiculo }}</p> <!-- AGREGAR MARCA MODELO Y PATENTE DE AUTO DEL CLIENTE  -->
                </td>

                <td style="border: 1px solid black;">
                    <p><strong>Fecha y Hora:</strong> {{ $fecha }} {{$horario}}</p>
                </td>
            </tr>

            <tr>
                <td style="border: 1px solid black;">
                    <p><strong>Operario:</strong> </p>
                </td>

                <td style="border: 1px solid black;">
                    <p><strong>Vendedor:</strong> {{ $vendedor->name }}</p>
                </td>

                <td style="border: 1px solid black;">
                    <p><strong>Sector: </strong> {{ $sector }} </p> <!-- AGREGAR SECTOR DE LA ORDEN -->
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 100px;">
        <table class="table table-striped" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="border: 1px solid black;">Item</th>
                    <th style="border: 1px solid black;">Cantidad</th>
                    <th style="border: 1px solid black;">Descripción</th>
                    <th style="border: 1px solid black;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $index =>$item)
                <tr>
                    <td style="border: 1px solid black;">{{ $index + 1 }}</td>
                    <td style="border: 1px solid black;">{{ $item['cantidad'] }}</td>
                    <td style="border: 1px solid black;">{{ $item->productos->descripcion .'- '. $item->productos->codigo }}</td>
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
            <p style="">Observaciones</p>
            <p>________________________________________________________________________________________</p>
            <p>________________________________________________________________________________________</p>
            <p>________________________________________________________________________________________</p>
            <p>________________________________________________________________________________________</p>
            <p>________________________________________________________________________________________</p>
        </div>
    </div>
    <br>
    <br>
    <div>
        <div style="display: inline-block;">
            <p>___________________________</p>
            <p style="text-align: center;">FIRMA OPERARIO</p>
        </div>
    </div>

</body>

</html>
