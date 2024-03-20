<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Proveedores;
use App\Models\Stock;

class ProductoSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [

            ['Aceite TOTAL7000 x 4lt', 38.000, '5', '87685736'],
            ['Aceite TOTAL9000 x 4lt', 61.999, '5', '768587736'],
            ['Aceite HELIX x 4lt 5w 30', 34.249, '5', '7368768'],
            ['Kit Wega FIAT Cronos argo', 41.797, '5', '86768736'],
            ['Kit Wega Suran FOX', 41.797, '5', '345678736'],
            ['Kit AMAROK', 63.193, '5', '5678736'],

        ];




        foreach ($productos as $prod) {

            $p = Producto::create([
                'proveedor_id' => '1',
                'costo' => $prod[1],
                'descripcion' => $prod[0],
                'codigo_de_barras' => $prod[3],
                'estado' => '1',
            ]);

            Stock::create([

                'producto_id' => $p->id,
                'cantidad' => '5',
                'unidad' => 'un',
                'estado' => '1',
                'ideal' => '8',
                'escaso' => '3',
            ]);
        }
    }
}
