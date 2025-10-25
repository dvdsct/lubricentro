<?php

namespace Database\Seeders;

use App\Models\CategoriaProducto;
use App\Models\Producto;
use App\Models\ProductoXProveedor;
use App\Models\Stock;
use App\Models\SubcategoriaProducto;
use Illuminate\Database\Seeder;

class CargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = base_path('public/seeder-source/carga.csv');

        if (!file_exists($csvPath)) {
            $this->command?->error("CSV no encontrado: {$csvPath}");
            return;
        }

        $handle = fopen($csvPath, 'r');
        if ($handle === false) {
            $this->command?->error('No se pudo abrir el archivo CSV.');
            return;
        }

        $row = 0;
        $imported = 0;
        $sucursalId = 1; // Ajustar si corresponde
        $proveedorId = 1; // Ajustar si corresponde

        while (($data = fgetcsv($handle, 0, ',')) !== false) {
            $row++;
            // Saltar cabecera
            if ($row === 1) {
                continue;
            }

            // Columnas: A=codigo, B=categoria, C=subcategoria, D=descripcion, E=cantidad, F=precio
            $codigo       = isset($data[0]) ? trim((string) $data[0]) : '';
            $categoriaIn  = isset($data[1]) ? trim((string) $data[1]) : '';
            $subcatIn     = isset($data[2]) ? trim((string) $data[2]) : '';
            $descripcion  = isset($data[3]) ? trim((string) $data[3]) : '';
            $cantidadIn   = isset($data[4]) ? trim((string) $data[4]) : '';
            $precioIn     = isset($data[5]) ? trim((string) $data[5]) : '';

            if ($codigo === '') {
                continue; // no podemos identificar producto sin codigo
            }

            $cantidad = $this->normalizeNumber($cantidadIn);
            $precio   = $this->normalizeNumber($precioIn);

            // Resolver categoria y subcategoria (acepta ID numérico o nombre)
            $categoriaId = null;
            if ($categoriaIn !== '') {
                if (ctype_digit(str_replace(['+', '-'], '', $categoriaIn))) {
                    $categoriaId = (int) $categoriaIn;
                } else {
                    $cat = CategoriaProducto::firstOrCreate(['nombre' => $categoriaIn], ['estado' => '1']);
                    $categoriaId = $cat->id;
                }
            }

            $subcategoriaId = null;
            if ($subcatIn !== '') {
                if (ctype_digit(str_replace(['+', '-'], '', $subcatIn))) {
                    $subcategoriaId = (int) $subcatIn;
                } else {
                    $sub = SubcategoriaProducto::firstOrCreate(['nombre' => $subcatIn], ['estado' => '1']);
                    $subcategoriaId = $sub->id;
                }
            }

            // Crear/actualizar producto por codigo
            $producto = Producto::firstOrCreate(
                ['codigo' => $codigo],
                [
                    'descripcion' => $descripcion !== '' ? $descripcion : $codigo,
                    'categoria_producto_id' => $categoriaId,
                    'subcategoria_producto_id' => $subcategoriaId,
                    'precio_venta' => $precio,
                    'estado' => '1',
                ]
            );

            // Actualizaciones si el producto ya existía o llegaron datos nuevos
            $dirty = false;
            if ($descripcion !== '' && $producto->descripcion !== $descripcion) {
                $producto->descripcion = $descripcion;
                $dirty = true;
            }
            if ($categoriaId && $producto->categoria_producto_id !== $categoriaId) {
                $producto->categoria_producto_id = $categoriaId;
                $dirty = true;
            }
            if ($subcategoriaId && $producto->subcategoria_producto_id !== $subcategoriaId) {
                $producto->subcategoria_producto_id = $subcategoriaId;
                $dirty = true;
            }
            if ($precio !== '' && $producto->precio_venta !== $precio) {
                $producto->precio_venta = $precio;
                $dirty = true;
            }
            if ($dirty) {
                $producto->save();
            }

            // Stock por sucursal
            if ($cantidad !== '') {
                $stock = Stock::firstOrCreate(
                    [
                        'producto_id' => $producto->id,
                        'sucursal_id' => $sucursalId,
                    ],
                    [
                        'cantidad' => $cantidad,
                        'estado' => '1',
                    ]
                );
                if ($stock->wasRecentlyCreated === false && $stock->cantidad !== $cantidad) {
                    $stock->cantidad = $cantidad;
                    $stock->save();
                }
            }

            // Relación con proveedor
            if ($proveedorId) {
                ProductoXProveedor::firstOrCreate([
                    'proveedor_id' => $proveedorId,
                    'producto_id' => $producto->id,
                ]);
            }

            $imported++;
        }

        fclose($handle);

        $this->command?->info("Importación finalizada (carga.csv). Filas procesadas: " . ($row - 1) . ", registros importados/actualizados: {$imported}.");
    }

    private function normalizeNumber(string $value): string
    {
        // Quitar espacios y separadores de miles comunes; usar punto como decimal
        $v = str_replace(["\u{00A0}", ' '], '', $value);
        $v = str_replace(['.', '’', "'"], '', $v);
        $v = str_replace([','], '.', $v);
        return $v;
    }
}
