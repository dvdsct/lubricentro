<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Orden de Trabajo - Formato Limpio</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 10px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }
        .logo-container {
            width: 30%;
        }
        .logo {
            max-width: 200px;
            max-height: 80px;
            object-fit: contain;
        }
        .fecha {
            text-align: right;
            width: 30%;
        }
        .titulo {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 15px 0;
            text-decoration: underline;
        }
        .seccion {
            margin: 15px 0;
        }
        .seccion-titulo {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .linea-punteada {
            border-bottom: 1px dashed #000;
            margin: 5px 0 15px 0;
            padding-bottom: 5px;
            min-height: 20px;
        }
        .linea-inline {
            display: inline-block;
            vertical-align: middle;
            border-bottom: 1px dotted #000;
            height: 1px; /* altura explícita para evitar colapso en DomPDF */
            line-height: 1px;
            margin: 0;
            padding: 0;
        }
        .dos-columnas {
            display: flex;
            justify-content: space-between;
        }
        .columna {
            width: 48%;
        }
        .firma {
            margin-top: 50px;
            text-align: center;
        }
        .firma-linea {
            width: 60%;
            border-top: 1px solid #000;
            margin: 0 auto;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            @if(isset($logo) && $logo)
                <img src="{{ $logo }}" alt="Logo" class="logo">
            @endif
        </div>
        <div class="fecha">
            Fecha: <span class="linea-punteada">{{ $fecha ?? '' }}</span>
        </div>
    </div>

    <div class="titulo">ORDEN DE TRABAJO</div>

    <div class="seccion">
        <table style="width:100%; border-collapse: collapse;">
            <tr>
                <td style="width: 110px; padding: 0;">
                    <span class="seccion-titulo">CLIENTE:</span>
                </td>
                <td style="border-bottom: 1px dashed #000; height: 16px; padding: 0;"></td>
            </tr>
        </table>
    </div>

    <div class="dos-columnas">
        <div class="columna">
            <div class="seccion">
                <table style="width:100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 110px; padding: 0;">
                            <span class="seccion-titulo">VEHÍCULO:</span>
                        </td>
                        <td style="border-bottom: 1px dashed #000; height: 16px; padding: 0;"></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="columna">
            <div class="seccion">
                <table style="width:100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 110px; padding: 0;">
                            <span class="seccion-titulo">DOMINIO:</span>
                        </td>
                        <td style="border-bottom: 1px dashed #000; height: 16px; padding: 0;"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="seccion">
        <div class="seccion-titulo">TRABAJO A REALIZAR:</div>
        <div class="linea-punteada" style="min-height: 10px; margin: 10px 0 20px 0;"></div>
        
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <!-- Columna Izquierda -->
                <td style="width: 50%; vertical-align: top; padding-right: 20px;">
                    <!-- Filtro de Aceite -->
                    <div style="margin-bottom: 25px; white-space: nowrap;">
                        <span style="display: inline-block; width: 120px; vertical-align: middle;">Filtro de Aceite:</span>
                        <div style="width: 25px; height: 25px; border: 2px solid #000; margin-right: 10px; display: inline-block; vertical-align: middle;"></div>
                        <div class="linea-inline" style="width: 150px;"></div>
                    </div>
                    
                    <!-- Filtro de Aire -->
                    <div style="margin-bottom: 25px; white-space: nowrap;">
                        <span style="display: inline-block; width: 120px; vertical-align: middle;">Filtro de Aire:</span>
                        <div style="width: 25px; height: 25px; border: 2px solid #000; margin-right: 10px; display: inline-block; vertical-align: middle;"></div>
                        <div class="linea-inline" style="width: 150px;"></div>
                    </div>
                </td>
                
                <!-- Columna Derecha -->
                <td style="width: 50%; vertical-align: top; padding-left: 20px;">
                    <!-- Filtro de Combustible -->
                    <div style="margin-bottom: 25px; white-space: nowrap;">
                        <span style="display: inline-block; width: 120px; vertical-align: middle;">Filtro Combustible:</span>
                        <div style="width: 25px; height: 25px; border: 2px solid #000; margin-right: 10px; display: inline-block; vertical-align: middle;"></div>
                        <div class="linea-inline" style="width: 150px;"></div>
                    </div>
                    
                    <!-- Filtro de Habitáculo -->
                    <div style="margin-bottom: 25px; white-space: nowrap;">
                        <span style="display: inline-block; width: 120px; vertical-align: middle;">Filtro Habitáculo:</span>
                        <div style="width: 25px; height: 25px; border: 2px solid #000; margin-right: 10px; display: inline-block; vertical-align: middle;"></div>
                        <div class="linea-inline" style="width: 150px;"></div>
                    </div>
                </td>
            </tr>
        </table>

        <div style="width: 100%; margin: 5px 0 15px 0; white-space: nowrap;">
            <span style="display: inline-block; width: 120px; vertical-align: middle;">Aceite de Motor:</span>
            <div class="linea-inline" style="width: calc(100% - 140px);"></div>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin: 0 0 20px 0;">
            <tr>
                <td style="width: 33.33%; padding-right: 10px;">
                    <div style="white-space: nowrap;">
                        <span style="display: inline-block; width: 90px; vertical-align: middle;">Envasado:</span>
                        <div style="width: 25px; height: 25px; border: 2px solid #000; display: inline-block; vertical-align: middle;"></div>
                    </div>
                </td>
                <td style="width: 33.33%; padding: 0 5px;">
                    <div style="white-space: nowrap;">
                        <span style="display: inline-block; width: 90px; vertical-align: middle;">Suelto:</span>
                        <div style="width: 25px; height: 25px; border: 2px solid #000; display: inline-block; vertical-align: middle;"></div>
                    </div>
                </td>
                <td style="width: 33.33%; padding-left: 10px;">
                    <div style="white-space: nowrap;">
                        <span style="display: inline-block; width: 90px; vertical-align: middle;">Lavado:</span>
                        <div style="width: 25px; height: 25px; border: 2px solid #000; display: inline-block; vertical-align: middle;"></div>
                    </div>
                </td>
            </tr>
        </table>

        <div style="width: 100%; margin: 5px 0 20px 0; white-space: nowrap;">
            <span style="display: inline-block; width: 120px; vertical-align: middle;">Control:</span>
            <div class="linea-inline" style="width: calc(100% - 140px);"></div>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin: 0 0 20px 0;">
            <tr>
                <td style="width: 33.33%; padding-right: 10px;">
                    <div style="white-space: nowrap;">
                        <span style="display: inline-block; width: 120px; vertical-align: middle;">Escaneo:</span>
                        <div style="width: 25px; height: 25px; border: 2px solid #000; display: inline-block; vertical-align: middle;"></div>
                    </div>
                </td>
                <td style="width: 33.33%; padding: 0 5px;">
                    <div style="white-space: nowrap;">
                        <span style="display: inline-block; width: 120px; vertical-align: middle;">Frenos:</span>
                        <div style="width: 25px; height: 25px; border: 2px solid #000; display: inline-block; vertical-align: middle;"></div>
                    </div>
                </td>
                <td style="width: 33.33%; padding-left: 10px;">
                    <div style="white-space: nowrap;">
                        <span style="display: inline-block; width: 120px; vertical-align: middle;">Escobillas:</span>
                        <div style="width: 25px; height: 25px; border: 2px solid #000; display: inline-block; vertical-align: middle;"></div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 33.33%; padding-right: 10px;">
                    <div style="white-space: nowrap;">
                        <span style="display: inline-block; width: 120px; vertical-align: middle;">Reseteo:</span>
                        <div style="width: 25px; height: 25px; border: 2px solid #000; display: inline-block; vertical-align: middle;"></div>
                    </div>
                </td>
                <td style="width: 33.33%; padding: 0 5px;">
                    <div style="white-space: nowrap;">
                        <span style="display: inline-block; width: 120px; vertical-align: middle;">T. Delantero:</span>
                        <div style="width: 25px; height: 25px; border: 2px solid #000; display: inline-block; vertical-align: middle;"></div>
                    </div>
                </td>
                <td style="width: 33.33%; padding-left: 10px;">
                    <div style="white-space: nowrap;">
                        <span style="display: inline-block; width: 120px; vertical-align: middle;">Luces:</span>
                        <div style="width: 25px; height: 25px; border: 2px solid #000; display: inline-block; vertical-align: middle;"></div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 33.33%; padding-right: 10px;">
                    <div style="white-space: nowrap;">
                        <span style="display: inline-block; width: 120px; vertical-align: middle;">Liq. Frenos:</span>
                        <div style="width: 25px; height: 25px; border: 2px solid #000; display: inline-block; vertical-align: middle;"></div>
                    </div>
                </td>
                <td style="width: 33.33%; padding: 0 5px;">
                    <div style="white-space: nowrap;">
                        <span style="display: inline-block; width: 120px; vertical-align: middle;">Liq. Refrig.:</span>
                        <div style="width: 25px; height: 25px; border: 2px solid #000; display: inline-block; vertical-align: middle;"></div>
                    </div>
                </td>
                <td style="width: 33.33%; padding-left: 10px;">
                    
                </td>
            </tr>
        </table>

        <table style="width:100%; border-collapse: collapse; margin: 10px 0;">
            <tr>
                <td style="width: 150px; padding: 0;">
                    <span class="seccion-titulo">OBSERVACIONES:</span>
                </td>
                <td style="border-bottom: 1px dashed #000; height: 16px; padding: 0;"></td>
            </tr>
        </table>
    </div>

    <div class="seccion">
        <table style="width:100%; border-collapse: collapse;">
            <tr>
                <td style="width: 150px; padding: 0;">
                    <span class="seccion-titulo">Recibir Producto para cambio:</span>
                </td>
                <td style="border-bottom: 1px dashed #000; height: 16px; padding: 0;"></td>
            </tr>
        </table>
    </div>

    <div class="seccion">
        <table style="width:100%; border-collapse: collapse;">
            <tr>
                <td style="width: 180px; padding: 0;">
                    <span class="seccion-titulo">Operador responsable:</span>
                </td>
                <td style="border-bottom: 1px dashed #000; height: 16px; padding: 0;"></td>
            </tr>
        </table>
    </div>

    <div class="seccion">
        <div style="white-space: nowrap;">
            <span class="seccion-titulo" style="display: inline-block; width: 130px; vertical-align: middle;">Control final:</span>
            <div style="width: 25px; height: 25px; border: 2px solid #000; display: inline-block; vertical-align: middle;"></div>
        </div>
    </div>

    <div class="firma">
        <div class="firma-linea">
            Firma del Cliente
        </div>
    </div>
</body>
</html>
