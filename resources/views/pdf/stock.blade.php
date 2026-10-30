<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Planilla de Stock - Rocket</title>
    <style>
        @page {
            margin: 70px 20px 30px 20px;
        }
        header {
            position: fixed;
            top: -55px;
            left: 0px;
            right: 0px;
            height: 50px;
            border-bottom: 2px solid #222;
        }
        footer {
            position: fixed;
            bottom: -20px;
            left: 0px;
            right: 0px;
            height: 20px;
            font-size: 8px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 3px;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            line-height: 1.25;
            color: #111;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        .logo {
            max-width: 130px;
            max-height: 42px;
        }
        table.table-stock {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 2px;
        }
        table.table-stock th {
            background-color: #2c3e50;
            color: #ffffff;
            font-size: 8.5px;
            font-weight: bold;
            padding: 4px 5px;
            text-align: left;
            border: 1px solid #2c3e50;
        }
        table.table-stock td {
            border-bottom: 1px solid #e0e0e0;
            border-left: 1px solid #f0f0f0;
            border-right: 1px solid #f0f0f0;
            padding: 3.5px 5px;
            font-size: 8px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        table.table-stock tr:nth-child(even) {
            background-color: #f9fbfd;
        }
        .page-break {
            page-break-after: always;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .page-number:before {
            content: counter(page);
        }
    </style>
</head>
<body>
    <header>
        <table class="header-table">
            <tr>
                <td style="width: 25%;">
                    @if(isset($logo) && $logo)
                        <img src="{{ $logo }}" class="logo" alt="Rocket">
                    @else
                        <strong style="font-size: 16px; color: #d9534f;">ROCKET</strong><br>
                        <small style="letter-spacing: 1px; font-weight: bold;">LUBRICENTRO</small>
                    @endif
                </td>
                <td style="width: 45%; text-align: center;">
                    <div style="font-size: 13px; font-weight: bold; text-transform: uppercase; color: #222;">
                        Planilla de Stock Actual
                    </div>
                    <div style="font-size: 8.5px; color: #555; margin-top: 2px;">
                        @if($subcategoriaNombre)
                            Subcategoría: <strong>{{ $subcategoriaNombre }}</strong> &nbsp;|&nbsp;
                        @endif
                        @if($categoriaNombre)
                            Categoría: <strong>{{ $categoriaNombre }}</strong> &nbsp;|&nbsp;
                        @endif
                        @if($searchQuery)
                            Búsqueda: <strong>"{{ $searchQuery }}"</strong> &nbsp;|&nbsp;
                        @endif
                        {{ $fecha_str }}
                    </div>
                </td>
                <td style="width: 30%; text-align: right; font-size: 8.5px; color: #444;">
                    <div>Artículos listados: <strong>{{ number_format($totalItems, 0, '', '.') }}</strong></div>
                    <div>Stock total: <strong>{{ number_format($totalCantidad, 2, ',', '.') }}</strong></div>
                </td>
            </tr>
        </table>
    </header>

    <footer>
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; padding: 0; text-align: left; font-size: 8px; color: #777;">
                    Rocket Lubricentro &bull; Impreso el {{ date('d/m/Y H:i') }} hs
                </td>
                <td style="border: none; padding: 0; text-align: right; font-size: 8px; color: #777;">
                    Página <span class="page-number"></span>
                </td>
            </tr>
        </table>
    </footer>

    @forelse ($chunks as $index => $chunk)
        <table class="table-stock">
            <thead>
                <tr>
                    <th style="width: 7%;" class="text-center">Cód.</th>
                    <th style="width: 18%;">Código Fábrica</th>
                    <th style="width: 45%;">Descripción del Producto</th>
                    <th style="width: 9%;" class="text-right">Stock</th>
                    <th style="width: 10%;" class="text-right">P. Costo</th>
                    <th style="width: 11%;" class="text-right">P. Venta</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($chunk as $item)
                    <tr>
                        <td class="text-center">{{ $item->producto_id }}</td>
                        <td>{{ $item->codigo ?: '-' }}</td>
                        <td>{{ $item->descripcion }}</td>
                        <td class="text-right">
                            <strong>{{ is_numeric($item->cantidad) ? number_format($item->cantidad, 2, ',', '.') : $item->cantidad }}</strong>
                        </td>
                        <td class="text-right">
                            ${{ is_numeric($item->costo) ? number_format((float)$item->costo, 2, ',', '.') : ($item->costo ?: '0,00') }}
                        </td>
                        <td class="text-right">
                            ${{ is_numeric($item->precio_venta) ? number_format((float)$item->precio_venta, 2, ',', '.') : ($item->precio_venta ?: '0,00') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @empty
        <div style="text-align: center; padding: 40px; color: #888; font-size: 12px;">
            No se encontraron productos en stock con los filtros seleccionados.
        </div>
    @endforelse
</body>
</html>
