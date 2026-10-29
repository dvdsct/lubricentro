<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Orden de Trabajo #{{ $ordenTrabajo->id }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 11px;
            line-height: 1.35;
            margin: 0;
            padding: 10px 15px;
            color: #111;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table {
            margin-bottom: 12px;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
        }
        .logo {
            max-width: 180px;
            max-height: 65px;
        }
        .titulo {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 8px 0 14px 0;
            text-decoration: underline;
            letter-spacing: 1px;
        }
        .seccion-titulo {
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        .campo-valor {
            font-weight: bold;
            color: #000;
            font-size: 11.5px;
        }
        .linea-dato {
            border-bottom: 1px dashed #444;
            padding: 3px 6px;
            min-height: 16px;
        }
        .box {
            width: 16px;
            height: 16px;
            border: 1.5px solid #000;
            display: inline-block;
            vertical-align: middle;
            text-align: center;
            line-height: 15px;
            font-size: 12px;
            font-weight: bold;
            margin-right: 4px;
        }
        .box-checked {
            background-color: #eee;
        }
        .table-items td {
            padding: 4px 2px;
            vertical-align: middle;
        }
        .firma-container {
            margin-top: 35px;
            text-align: center;
        }
        .firma-linea {
            width: 250px;
            border-top: 1px solid #000;
            margin: 0 auto;
            padding-top: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-km {
            font-size: 12px;
            font-weight: bold;
            background: #f0f0f0;
            padding: 2px 6px;
            border: 1px solid #bbb;
        }
    </style>
</head>
<body>
    <!-- ENCABEZADO -->
    <table class="header-table">
        <tr>
            <td style="width: 50%; vertical-align: middle;">
                @if(isset($logo) && $logo)
                    <img src="{{ $logo }}" alt="Rocket Lubricentro" class="logo">
                @else
                    <strong style="font-size: 20px; color: #d9534f;">ROCKET</strong><br>
                    <small style="letter-spacing: 2px;">LUBRICENTRO</small>
                @endif
            </td>
            <td style="width: 50%; text-align: right; vertical-align: middle;">
                <div style="font-size: 13px; font-weight: bold; margin-bottom: 4px;">
                    ORDEN DE TRABAJO #{{ $ordenTrabajo->id }}
                </div>
                <div>Fecha: <strong>{{ $ordenTrabajo->fecha ? $ordenTrabajo->fecha->format('d/m/Y') : date('d/m/Y') }}</strong></div>
            </td>
        </tr>
    </table>

    <div class="titulo">ORDEN DE TRABAJO</div>

    <!-- DATOS DEL CLIENTE Y VEHICULO -->
    <table style="margin-bottom: 8px;">
        <tr>
            <td style="width: 80px;" class="seccion-titulo">CLIENTE:</td>
            <td class="linea-dato campo-valor">
                @if($ordenTrabajo->cliente && $ordenTrabajo->cliente->perfiles && $ordenTrabajo->cliente->perfiles->personas)
                    {{ $ordenTrabajo->cliente->perfiles->personas->nombre }} {{ $ordenTrabajo->cliente->perfiles->personas->apellido }}
                    @if($ordenTrabajo->cliente->perfiles->personas->DNI)
                        - DNI: {{ $ordenTrabajo->cliente->perfiles->personas->DNI }}
                    @endif
                    @if($ordenTrabajo->cliente->perfiles->personas->numero_telefono)
                        - Tel: {{ $ordenTrabajo->cliente->perfiles->personas->numero_telefono }}
                    @endif
                @else
                    {{ $clienteNombre ?? 'Consumidor Final' }}
                @endif
            </td>
        </tr>
    </table>

    <table style="margin-bottom: 8px;">
        <tr>
            <td style="width: 80px;" class="seccion-titulo">VEHÍCULO:</td>
            <td class="linea-dato campo-valor" style="width: 55%;">
                @php
                    $v = $ordenTrabajo->vehiculo;
                    $marca = optional(optional($v)->modelos)->marcas->descripcion ?? '';
                    $modelo = optional(optional($v)->modelos)->descripcion ?? '';
                    $version = $v->version ?? '';
                    $anio = $v->año ?? '';
                    $descVehiculo = trim("$marca $modelo $version $anio");
                @endphp
                {{ $descVehiculo ?: '-' }}
                @if($v && $v->color)
                    - Color: {{ $v->color }}
                @endif
            </td>
            <td style="width: 70px; text-align: right; padding-right: 8px;" class="seccion-titulo">DOMINIO:</td>
            <td class="linea-dato campo-valor" style="text-align: center; width: 25%; font-size: 13px;">
                {{ $v->dominio ?? '-' }}
            </td>
        </tr>
    </table>

    <!-- KILOMETRAJE: ENTRADA Y PROXIMO SERVICE -->
    <table style="margin-bottom: 12px; background: #fafafa; border: 1px solid #ddd; padding: 6px;">
        <tr>
            <td style="width: 20%;" class="seccion-titulo">KM ENTRADA:</td>
            <td style="width: 30%;" class="campo-valor">
                <span class="badge-km">{{ $ordenTrabajo->km_entrada ? number_format((float)str_replace(['.', ','], '', $ordenTrabajo->km_entrada), 0, '', '.') . ' km' : ($ordenTrabajo->km_entrada ?: '-') }}</span>
            </td>
            <td style="width: 25%; text-align: right; padding-right: 8px;" class="seccion-titulo">KM PRÓX. SERVICE:</td>
            <td style="width: 25%;" class="campo-valor">
                <span class="badge-km" style="color: #b30000;">{{ $ordenTrabajo->km_proximo_service ? number_format((float)str_replace(['.', ','], '', $ordenTrabajo->km_proximo_service), 0, '', '.') . ' km' : ($ordenTrabajo->km_proximo_service ?: '-') }}</span>
            </td>
        </tr>
    </table>

    <!-- TRABAJO A REALIZAR -->
    <table style="margin-bottom: 12px;">
        <tr>
            <td style="width: 140px; vertical-align: top;" class="seccion-titulo">TRABAJO A REALIZAR:</td>
            <td class="linea-dato campo-valor" style="vertical-align: top;">
                {{ $ordenTrabajo->trabajo_realizar ?: 'Service general / Cambio de aceite y filtros' }}
            </td>
        </tr>
    </table>

    <!-- FILTROS -->
    <table class="table-items" style="margin-bottom: 10px;">
        <tr>
            <!-- Filtro de Aceite -->
            <td style="width: 50%;">
                <span class="box {{ $ordenTrabajo->filtro_aceite ? 'box-checked' : '' }}">{{ $ordenTrabajo->filtro_aceite ? 'X' : '' }}</span>
                <span style="font-weight: bold; width: 110px; display: inline-block;">Filtro de Aceite:</span>
                <span class="linea-dato" style="display: inline-block; width: 180px;">{{ $ordenTrabajo->filtro_aceite_detalle ?: '' }}</span>
            </td>
            <!-- Filtro de Combustible -->
            <td style="width: 50%;">
                <span class="box {{ $ordenTrabajo->filtro_combustible ? 'box-checked' : '' }}">{{ $ordenTrabajo->filtro_combustible ? 'X' : '' }}</span>
                <span style="font-weight: bold; width: 130px; display: inline-block;">Filtro Combustible:</span>
                <span class="linea-dato" style="display: inline-block; width: 160px;">{{ $ordenTrabajo->filtro_combustible_detalle ?: '' }}</span>
            </td>
        </tr>
        <tr>
            <!-- Filtro de Aire -->
            <td style="width: 50%;">
                <span class="box {{ $ordenTrabajo->filtro_aire ? 'box-checked' : '' }}">{{ $ordenTrabajo->filtro_aire ? 'X' : '' }}</span>
                <span style="font-weight: bold; width: 110px; display: inline-block;">Filtro de Aire:</span>
                <span class="linea-dato" style="display: inline-block; width: 180px;">{{ $ordenTrabajo->filtro_aire_detalle ?: '' }}</span>
            </td>
            <!-- Filtro Habitaculo -->
            <td style="width: 50%;">
                <span class="box {{ $ordenTrabajo->filtro_habitaculo ? 'box-checked' : '' }}">{{ $ordenTrabajo->filtro_habitaculo ? 'X' : '' }}</span>
                <span style="font-weight: bold; width: 130px; display: inline-block;">Filtro Habitáculo:</span>
                <span class="linea-dato" style="display: inline-block; width: 160px;">{{ $ordenTrabajo->filtro_habitaculo_detalle ?: '' }}</span>
            </td>
        </tr>
    </table>

    <!-- ACEITE DE MOTOR -->
    <table style="margin-bottom: 12px; background: #fdfdfd; border: 1px solid #ccc; padding: 6px;">
        <tr>
            <td style="width: 110px;" class="seccion-titulo">ACEITE MOTOR:</td>
            <td style="width: 45%;">
                Tipo: <strong class="campo-valor">{{ $ordenTrabajo->tipo_aceite ?: '-' }}</strong>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                Litros: <strong class="campo-valor">{{ $ordenTrabajo->litros_aceite ? $ordenTrabajo->litros_aceite . ' Lts' : '-' }}</strong>
            </td>
            <td style="width: 45%; text-align: right;">
                <span class="box {{ $ordenTrabajo->aceite_envasado ? 'box-checked' : '' }}">{{ $ordenTrabajo->aceite_envasado ? 'X' : '' }}</span> Envasado
                &nbsp;&nbsp;
                <span class="box {{ $ordenTrabajo->aceite_suelto ? 'box-checked' : '' }}">{{ $ordenTrabajo->aceite_suelto ? 'X' : '' }}</span> Suelto
                &nbsp;&nbsp;
                <span class="box {{ $ordenTrabajo->aceite_lavado ? 'box-checked' : '' }}">{{ $ordenTrabajo->aceite_lavado ? 'X' : '' }}</span> Lavado
            </td>
        </tr>
    </table>

    <!-- CONTROL / INSPECCIÓN GENERAL -->
    <div style="font-weight: bold; margin-bottom: 4px; text-decoration: underline;" class="seccion-titulo">CONTROL:</div>
    @php
        $c = $ordenTrabajo->controles ?? [];
    @endphp
    <table class="table-items" style="margin-bottom: 12px;">
        <tr>
            <td style="width: 33%;">
                <span class="box {{ !empty($c['escaneo']) ? 'box-checked' : '' }}">{{ !empty($c['escaneo']) ? 'X' : '' }}</span> Escaneo
            </td>
            <td style="width: 33%;">
                <span class="box {{ !empty($c['frenos']) ? 'box-checked' : '' }}">{{ !empty($c['frenos']) ? 'X' : '' }}</span> Frenos
            </td>
            <td style="width: 33%;">
                <span class="box {{ !empty($c['escobillas']) ? 'box-checked' : '' }}">{{ !empty($c['escobillas']) ? 'X' : '' }}</span> Escobillas
            </td>
        </tr>
        <tr>
            <td>
                <span class="box {{ !empty($c['reseteo']) ? 'box-checked' : '' }}">{{ !empty($c['reseteo']) ? 'X' : '' }}</span> Reseteo
            </td>
            <td>
                <span class="box {{ !empty($c['t_delantero']) ? 'box-checked' : '' }}">{{ !empty($c['t_delantero']) ? 'X' : '' }}</span> T. Delantero
            </td>
            <td>
                <span class="box {{ !empty($c['luces']) ? 'box-checked' : '' }}">{{ !empty($c['luces']) ? 'X' : '' }}</span> Luces
            </td>
        </tr>
        <tr>
            <td>
                <span class="box {{ !empty($c['liq_frenos']) ? 'box-checked' : '' }}">{{ !empty($c['liq_frenos']) ? 'X' : '' }}</span> Liq. Frenos
            </td>
            <td>
                <span class="box {{ !empty($c['liq_refrig']) ? 'box-checked' : '' }}">{{ !empty($c['liq_refrig']) ? 'X' : '' }}</span> Liq. Refrig.
            </td>
            <td></td>
        </tr>
    </table>

    <!-- OBSERVACIONES -->
    <table style="margin-bottom: 8px;">
        <tr>
            <td style="width: 120px; vertical-align: top;" class="seccion-titulo">OBSERVACIONES:</td>
            <td class="linea-dato campo-valor" style="min-height: 24px;">
                {{ $ordenTrabajo->observaciones ?: '-' }}
            </td>
        </tr>
    </table>

    <!-- RECIBIR PRODUCTO PARA CAMBIO -->
    <table style="margin-bottom: 8px;">
        <tr>
            <td style="width: 190px;" class="seccion-titulo">RECIBIR PRODUCTO PARA CAMBIO:</td>
            <td class="linea-dato campo-valor">
                {{ $ordenTrabajo->recibir_producto ?: '-' }}
            </td>
        </tr>
    </table>

    <!-- OPERADOR RESPONSABLE & CONTROL FINAL -->
    <table style="margin-bottom: 25px;">
        <tr>
            <td style="width: 170px;" class="seccion-titulo">OPERADOR RESPONSABLE:</td>
            <td class="linea-dato campo-valor" style="width: 45%;">
                {{ $ordenTrabajo->operador_responsable ?: (optional($ordenTrabajo->user)->name ?? '-') }}
            </td>
            <td style="text-align: right;">
                <span class="seccion-titulo">Control final:</span>
                <span class="box {{ $ordenTrabajo->control_final ? 'box-checked' : '' }}" style="margin-left: 6px;">{{ $ordenTrabajo->control_final ? 'X' : '' }}</span>
            </td>
        </tr>
    </table>

    <!-- FIRMA DEL CLIENTE -->
    <div class="firma-container">
        <div class="firma-linea">
            Firma del Cliente
        </div>
    </div>
</body>
</html>
