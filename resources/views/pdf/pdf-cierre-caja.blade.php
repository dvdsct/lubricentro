<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Cierre de Caja</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group span {
            display: block;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f8f8f8;
        }
     
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f8f8f8;
        }

      .subtotal{
        font-weight: bold;
        background: rgb(215, 215, 215);

            
}
        .total {
            font-weight: bold;
        }
        /* .signature {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        } */
        .signature div {
            width: 45%;
            text-align: center;
            padding-top: 10px;
            margin-top: 10px;
            border-top: 1px solid #000;
        }
    </style>

</head>
<body>

<div class="container">
    <h2>Ficha de Cierre de Caja</h2>
    
    <table>
        <thead>

            <th>
                <label for="fecha">Fecha:</label>
            </th>
            
            <th>
                <label for="turno">Turno:</label>
            </th>
            
            <th>
                <label for="cajero">Nombre del Cajero:</label>
            </th>
        </thead>
        <tbody>
            <tr>     
                <td><span id="fecha">{{$fechaApertura}} - {{$fechaCierre}}</span></td>

                <td><span id="turno">{{$turno}}</span></td>

               <td> <span id="cajero">{{$cajero}}</span></td>
</tr>
        </tbody>
    </table>
    
    <h3>Resumen de Ingresos y Egresos</h3>
    <table>
        <thead>
            <tr>
                <th>Concepto</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Monto Inicial</td>
                <td>$ {{$montoInicial}}</td>
            </tr>
            <tr>
                <td>Efectivo</td>
                <td>$ {{$pagosEfectivo}}</td>
            </tr>
            <tr>
                <td>Tarjeta de Crédito</td>
                <td>$ {{$pagosTarjeta}}</td>
            </tr>
            <tr>
                <td>Transferencia</td>
                <td>$ {{$pagosTrans}}</td>
            </tr>
            <tr>
                <td>Cheques</td>
                <td>$ {{$pagosCheques}}</td>
            </tr>
            <tr>
                <td>Egresos</td>
                <td>$ {{$gastosEfectivo}}</td>
            </tr>
            <tr class="subtotal" >
                <td>SUBTOTAL</td>
                <td>$ {{$totalv}}</td>
            </tr>
        </tbody>
    </table>
    
    <div class="form-group total">
        <label for="total_ingresos">Total Ingresos del Día:</label>
        <span id="total_ingresos">$ {{$ingresos}}</span>
    </div>
    
    <div class="form-group total">
        <label for="total_egresos">Total Egresos del Día:</label>
        <span id="total_egresos">$ {{$gastos}}</span>
    </div>
    
    <div class="form-group total">
        <label for="total_neto">Efectivo en caja:</label>
        <span id="total_neto">$ {{$rendicion}}</span>
    </div>
    
    <div class="signature">
        <div>
            Firma del Cajero
        </div>
        <div>
            Firma del Supervisor
        </div>
    </div>
</div>

</body>
</html>
