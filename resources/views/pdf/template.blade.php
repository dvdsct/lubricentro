<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Trabajo</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; line-height: 1.2; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #000; padding: 5px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .logo { max-width: 200px; height: auto; }
    </style>
</head>


<body>

    <div style="margin-bottom: 6px;">
        <table style="border-collapse: collapse; width: 100%;">

            <tr>
                <td colspan="2" class="logo" style="border: 1px solid black; padding: 8px; text-align: left; font-family: Arial , Helevetica, sans-serif">
                    <h1>Orden de Trabajo N° 0000-{{ $orden->id }}</h1>   <!-- AGREGAR ID DE LA ORDEN -->
                </td>

                <td style="border: 1px solid black; text-align: center;">
                    @if(isset($logo) && $logo)
                        <img src="{{ $logo }}" alt="Logo" class="logo">
                    @endif
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

    <div style="margin-top: 20px;">
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Cantidad</th>
                    <th>Descripción</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-center">{{ $item->cantidad ?? 0 }}</td>
                    <td>{{ ($item->productos->descripcion ?? '') . ($item->productos->codigo ? ' - ' . $item->productos->codigo : '') }}</td>
                    <td class="text-right">$ {{ number_format($item->subtotal ?? 0, 2, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No hay ítems</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="text-right" style="margin-top: 20px;">
            <h3 class="bold">TOTAL $ {{ number_format($total ?? 0, 2, ',', '.') }}</h3>
        </div>
    </div>

    <div style="margin-top: 30px;">
        <div>
            <p><strong>Observaciones:</strong></p>
            <p>________________________________________________________________________________________</p>
            <p>________________________________________________________________________________________</p>
            <p>________________________________________________________________________________________</p>
        </div>
    </div>
    
    <div style="margin-top: 40px; text-align: center;">
        <p>_________________________________</p>
        <p>Firma del Cliente</p>
    </div>
    <br>
    <div>
        <div style="display: inline-block;">
            <p>___________________________</p>
            <p style="text-align: center;">FIRMA OPERARIO</p>
        </div>
    </div>

</body>

</html>
