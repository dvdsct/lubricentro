<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}
    <title>Stock - Rocket</title>

</head>

<style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            table {
                width: 100%;
                border: 1px solid black;
            }

            th, td {
                border: 1px solid black;
                padding: 10px;
            }

            th {
                background-color: #d9d9d9;
            }
        }
    </style>

<body>


    
    <h1 style="font-family: Arial, Helvetica, sans-serif;">Stock Actual de Productos - {{ Carbon\Carbon::parse($fecha)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
    </h1>
    <table>
        <thead>
            <tr>
                <th>Codigo Rocket</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Costo</th>
                <th>Precio Vta</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($stockActual as $item)
            <tr>
                <td>{{$item->productos->id}}</td>
                <td>{{$item->productos->descripcion}} {{$item->productos->codigo}}</td>
                <td>{{ $item->cantidad }}</td>
                <td>  {{ $item->productos->costo }}</td>
                <td>${{$item->productos->precio_venta}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
  
</body>

</html>
